<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\NotificationService;

class DeanController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isDean()) {
                abort(403, 'Access denied. Deans only.');
            }
            return $next($request);
        });
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Dean-specific statistics - filter by organization matching dean's department
        if ($user->department) {
            $pendingDeanNotes = Activity::where('workflow_status', 'noted_by_adviser')
                ->where('organization', $user->department)
                ->count();

            $departmentActivities = Activity::where('organization', $user->department)
                ->count();

            $notedActivities = Activity::where('workflow_status', 'noted_by_dean')
                ->where('organization', $user->department)
                ->count();

            $thisMonthActivities = Activity::where('organization', $user->department)
                ->whereMonth('created_at', now()->month)
                ->count();

            // Activities awaiting dean note
            $activitiesAwaitingNote = Activity::with(['user'])
                ->where('workflow_status', 'noted_by_adviser')
                ->where('organization', $user->department)
                ->orderBy('adviser_noted_at', 'desc')
                ->limit(10)
                ->get();

            // Recent department activities
            $recentDepartmentActivities = Activity::with(['user'])
                ->where('organization', $user->department)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        } else {
            // Show all activities if dean doesn't have specific department
            $pendingDeanNotes = Activity::where('workflow_status', 'noted_by_adviser')->count();
            $departmentActivities = Activity::count();
            $notedActivities = Activity::where('workflow_status', 'noted_by_dean')->count();
            $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

            $activitiesAwaitingNote = Activity::with(['user'])
                ->where('workflow_status', 'noted_by_adviser')
                ->orderBy('adviser_noted_at', 'desc')
                ->limit(10)
                ->get();

            $recentDepartmentActivities = Activity::with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        }

        return view('dean.dashboard', compact(
            'pendingDeanNotes',
            'departmentActivities',
            'notedActivities',
            'thisMonthActivities',
            'activitiesAwaitingNote',
            'recentDepartmentActivities'
        ));
    }

    public function profile()
    {
        return view('dean.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'department' => ['nullable', 'string'],
        ]);

        $user->update($request->only(['name', 'email', 'department']));

        return redirect()->route('dean.profile')->with('status', 'profile-updated');
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

        return redirect()->route('dean.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $user = Auth::user();

        if ($user->department) {
            $activities = Activity::with(['user'])
                ->where('organization', $user->department)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $activities = Activity::with(['user'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('dean.activities', compact('activities'));
    }

    public function pendingReviews()
    {
        $user = Auth::user();

        if ($user->department) {
            $activities = Activity::with(['user'])
                ->where('workflow_status', 'noted_by_adviser')
                ->where('organization', $user->department)
                ->orderBy('adviser_noted_at', 'desc')
                ->paginate(15);
        } else {
            $activities = Activity::with(['user'])
                ->where('workflow_status', 'noted_by_adviser')
                ->orderBy('adviser_noted_at', 'desc')
                ->paginate(15);
        }

        return view('dean.pending-reviews', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        $user = Auth::user();

        // Ensure dean can only view activities from their assigned school/department
        if ($user->department && $activity->organization !== $user->department) {
            abort(403, 'Access denied. You can only view activities from your assigned school.');
        }

        return view('dean.show-activity', compact('activity'));
    }

    public function reviewActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Ensure dean can only review activities from their assigned school/department
        if ($user->department && $activity->organization !== $user->department) {
            abort(403, 'Access denied. You can only review activities from your assigned school.');
        }

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
                    'previous_status' => 'noted_by_adviser',
                    'new_status' => 'rejected',
                    'comments' => $request->notes,
                ]);

                // Send notifications
                $this->notificationService->notifyActivityRejected($activity, $user, $request->notes);

                $message = 'Activity rejected successfully!';
            } else {
                $activity->update([
                    'workflow_status' => 'noted_by_dean',
                    'dean_noted_by' => $user->id,
                    'dean_noted_at' => now(),
                    'dean_notes' => $request->notes,
                ]);

                // Create activity log
                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'action' => 'reviewed',
                    'previous_status' => 'noted_by_adviser',
                    'new_status' => 'noted_by_dean',
                    'comments' => $request->notes ?? 'Activity reviewed and forwarded to PSG',
                ]);

                // Send notifications
                $this->notificationService->notifyActivityApproved($activity, $user, $request->notes);

                $message = 'Activity reviewed and forwarded to PSG successfully!';
            }

            return redirect()->route('dean.pending-reviews')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process activity. Please try again.');
        }
    }
}
