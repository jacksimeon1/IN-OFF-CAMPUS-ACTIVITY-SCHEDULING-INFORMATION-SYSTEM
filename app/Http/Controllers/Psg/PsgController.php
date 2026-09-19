<?php

namespace App\Http\Controllers\Psg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\NotificationService;

class PsgController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isPsgAdviser()) {
                abort(403, 'Access denied. PSG Advisers only.');
            }
            return $next($request);
        });
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // PSG-specific statistics
        $pendingPsgReviews = Activity::where('workflow_status', 'noted_by_dean')->count();
        $totalStudentActivities = Activity::count();
        $reviewedActivities = Activity::where('psg_reviewed_by', $user->id)->count();
        $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

        // Activities awaiting PSG review
        $activitiesAwaitingPsgReview = Activity::with(['user'])
            ->where('workflow_status', 'noted_by_dean')
            ->orderBy('dean_noted_at', 'desc')
            ->limit(10)
            ->get();

        // Recent student activities
        $recentStudentActivities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('psg.dashboard', compact(
            'pendingPsgReviews',
            'totalStudentActivities',
            'reviewedActivities',
            'thisMonthActivities',
            'activitiesAwaitingPsgReview',
            'recentStudentActivities'
        ));
    }

    public function profile()
    {
        return view('psg.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('psg.profile')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('psg.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $activities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('psg.activities', compact('activities'));
    }

    public function pendingReviews()
    {
        $activities = Activity::with(['user'])
            ->where('workflow_status', 'noted_by_dean')
            ->orderBy('dean_noted_at', 'desc')
            ->paginate(15);

        return view('psg.pending-reviews', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        return view('psg.show-activity', compact('activity'));
    }

    public function reviewActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'action' => 'required|in:review,reject'
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
                    'previous_status' => 'noted_by_dean',
                    'new_status' => 'rejected',
                    'comments' => $request->notes,
                ]);

                // Send notifications
                $this->notificationService->notifyActivityRejected($activity, $user, $request->notes);

                $message = 'Activity rejected successfully!';
            } else {
                $activity->update([
                    'workflow_status' => 'reviewed_by_psg',
                    'psg_reviewed_by' => $user->id,
                    'psg_reviewed_at' => now(),
                    'psg_notes' => $request->notes,
                ]);

                // Create activity log
                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'action' => 'reviewed',
                    'previous_status' => 'noted_by_dean',
                    'new_status' => 'reviewed_by_psg',
                    'comments' => $request->notes ?? 'Activity reviewed and forwarded to Director',
                ]);

                // Send notifications
                $this->notificationService->notifyActivityApproved($activity, $user, $request->notes);

                $message = 'Activity reviewed and forwarded to Director successfully!';
            }

            return redirect()->route('psg.pending-reviews')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process activity. Please try again.');
        }
    }
}
