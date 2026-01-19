<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\NotificationService;

class DirectorController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isDirector()) {
                abort(403, 'Access denied. Directors only.');
            }
            return $next($request);
        });
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Director-specific statistics
        $pendingEndorsements = Activity::where('workflow_status', 'reviewed_by_psg')->count();
        $totalActivities = Activity::count();
        $endorsedActivities = Activity::where('director_endorsed_by', $user->id)->count();
        $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

        // Activities awaiting director endorsement
        $activitiesAwaitingEndorsement = Activity::with(['user'])
            ->where('workflow_status', 'reviewed_by_psg')
            ->orderBy('psg_reviewed_at', 'desc')
            ->limit(10)
            ->get();

        // Recent institutional activities
        $recentInstitutionalActivities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('director.dashboard', compact(
            'pendingEndorsements',
            'totalActivities',
            'endorsedActivities',
            'thisMonthActivities',
            'activitiesAwaitingEndorsement',
            'recentInstitutionalActivities'
        ));
    }

    public function profile()
    {
        return view('director.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('director.profile')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('director.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $activities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('director.activities', compact('activities'));
    }

    public function pendingEndorsements()
    {
        $activities = Activity::with(['user'])
            ->where('workflow_status', 'reviewed_by_psg')
            ->orderBy('psg_reviewed_at', 'desc')
            ->paginate(15);

        return view('director.pending-endorsements', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        return view('director.show-activity', compact('activity'));
    }

    public function endorseActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'action' => 'required|in:endorse,reject'
        ]);

        try {
            if ($request->action === 'reject') {
                $activity->update([
                    'workflow_status' => 'rejected',
                    'rejected_by' => $user->id,
                    'rejected_at' => now(),
                    'rejection_reason' => $request->notes,
                    'status' => 'rejected',
                ]);

                // Create activity log
                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'action' => 'rejected',
                    'previous_status' => 'reviewed_by_psg',
                    'new_status' => 'rejected',
                    'comments' => $request->notes,
                ]);

                // Send notifications
                $this->notificationService->notifyActivityRejected($activity, $user, $request->notes);

                $message = 'Activity rejected successfully!';
            } else {
                $activity->update([
                    'workflow_status' => 'endorsed_by_director',
                    'director_endorsed_by' => $user->id,
                    'director_endorsed_at' => now(),
                    'director_notes' => $request->notes,
                ]);

                // Create activity log
                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'action' => 'endorsed',
                    'previous_status' => 'reviewed_by_psg',
                    'new_status' => 'endorsed_by_director',
                    'comments' => $request->notes ?? 'Activity endorsed and forwarded to VP',
                ]);

                // Send notifications
                $this->notificationService->notifyActivityApproved($activity, $user, $request->notes);

                $message = 'Activity endorsed and forwarded to VP successfully!';
            }

            return redirect()->route('director.pending-endorsements')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process activity. Please try again.');
        }
    }
}
