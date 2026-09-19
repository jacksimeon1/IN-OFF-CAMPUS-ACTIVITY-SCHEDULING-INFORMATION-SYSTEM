<?php

namespace App\Http\Controllers\Vp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\NotificationService;

class VpController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isVp()) {
                abort(403, 'Access denied. VPs only.');
            }
            return $next($request);
        });
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // VP-specific statistics
        $pendingFinalApprovals = Activity::where('workflow_status', 'endorsed_by_director')->count();
        $totalActivities = Activity::count();
        $approvedActivities = Activity::where('vp_approved_by', $user->id)->count();
        $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

        // Activities awaiting VP final approval
        $activitiesAwaitingFinalApproval = Activity::with(['user'])
            ->where('workflow_status', 'endorsed_by_director')
            ->orderBy('director_endorsed_at', 'desc')
            ->limit(10)
            ->get();

        // Recent approved activities
        $recentApprovedActivities = Activity::with(['user'])
            ->where('workflow_status', 'approved_by_vp')
            ->orderBy('vp_approved_at', 'desc')
            ->limit(8)
            ->get();

        return view('vp.dashboard', compact(
            'pendingFinalApprovals',
            'totalActivities',
            'approvedActivities',
            'thisMonthActivities',
            'activitiesAwaitingFinalApproval',
            'recentApprovedActivities'
        ));
    }

    public function profile()
    {
        return view('vp.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('vp.profile')->with('status', 'profile-updated');
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

        return redirect()->route('vp.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $activities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('vp.activities', compact('activities'));
    }

    public function pendingApprovals()
    {
        $activities = Activity::with(['user'])
            ->where('workflow_status', 'endorsed_by_director')
            ->orderBy('director_endorsed_at', 'desc')
            ->paginate(15);

        return view('vp.pending-approvals', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        return view('vp.show-activity', compact('activity'));
    }

    public function approveActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'action' => 'required|in:approve,reject'
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
                    'previous_status' => 'endorsed_by_director',
                    'new_status' => 'rejected',
                    'comments' => $request->notes,
                ]);

                // Send notifications
                $this->notificationService->notifyActivityRejected($activity, $user, $request->notes);

                $message = 'Activity rejected successfully!';
            } else {
                $activity->update([
                    'workflow_status' => 'approved_by_vp',
                    'vp_approved_by' => $user->id,
                    'vp_approved_at' => now(),
                    'vp_notes' => $request->notes,
                    'status' => 'approved',
                ]);

                // Create activity log
                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'action' => 'approved',
                    'previous_status' => 'endorsed_by_director',
                    'new_status' => 'approved_by_vp',
                    'comments' => $request->notes ?? 'Activity given final approval by VP',
                ]);

                // Send notifications
                $this->notificationService->notifyActivityApproved($activity, $user, $request->notes);

                $message = 'Activity given final approval successfully!';
            }

            return redirect()->route('vp.pending-approvals')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process activity. Please try again.');
        }
    }
}
