<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    /**
     * Adviser recommendation
     */
    public function adviserRecommend(Request $request, Activity $activity)
    {
        // Check if user is adviser and activity is pending
        if (!Auth::user()->isAdviser() || $activity->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:recommend,reject',
            'comments' => 'required|string|max:1000',
        ]);

        $previousStatus = $activity->status;
        $newStatus = $validated['action'] === 'recommend' ? 'recommended' : 'rejected';

        $activity->update([
            'status' => $newStatus,
            'adviser_id' => Auth::id(),
            'adviser_comments' => $validated['comments'],
            'adviser_reviewed_at' => now(),
        ]);

        // Create activity log
        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => Auth::id(),
            'action' => $validated['action'] === 'recommend' ? 'recommended' : 'rejected',
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'comments' => $validated['comments'],
        ]);

        // Create notification for student
        Notification::create([
            'user_id' => $activity->user_id,
            'activity_id' => $activity->id,
            'title' => $newStatus === 'recommended' ? 'Activity Recommended' : 'Activity Rejected',
            'message' => "Your activity '{$activity->title}' has been {$newStatus} by the adviser.",
            'type' => $newStatus === 'recommended' ? 'success' : 'error',
        ]);

        // If recommended, notify OSA
        if ($newStatus === 'recommended') {
            $this->notifyOsa($activity);
        }

        return redirect()->back()->with('success', "Activity {$newStatus} successfully!");
    }

    /**
     * OSA approval/rejection
     */
    public function osaDecision(Request $request, Activity $activity)
    {
        // Check if user is OSA and activity is recommended
        if (!Auth::user()->isOsa() || $activity->status !== 'recommended') {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'comments' => 'required|string|max:1000',
        ]);

        $previousStatus = $activity->status;
        $newStatus = $validated['action'] === 'approve' ? 'approved' : 'rejected';

        $activity->update([
            'status' => $newStatus,
            'osa_id' => Auth::id(),
            'osa_comments' => $validated['comments'],
            'osa_reviewed_at' => now(),
        ]);

        // Create activity log
        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => Auth::id(),
            'action' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'comments' => $validated['comments'],
        ]);

        // Create notification for student
        Notification::create([
            'user_id' => $activity->user_id,
            'activity_id' => $activity->id,
            'title' => $newStatus === 'approved' ? 'Activity Approved' : 'Activity Rejected',
            'message' => "Your activity '{$activity->title}' has been {$newStatus} by OSA.",
            'type' => $newStatus === 'approved' ? 'success' : 'error',
        ]);

        // Create notification for adviser
        if ($activity->adviser_id) {
            Notification::create([
                'user_id' => $activity->adviser_id,
                'activity_id' => $activity->id,
                'title' => "Activity {$newStatus}",
                'message' => "The activity '{$activity->title}' you recommended has been {$newStatus} by OSA.",
                'type' => $newStatus === 'approved' ? 'success' : 'warning',
            ]);
        }

        return redirect()->back()->with('success', "Activity {$newStatus} successfully!");
    }

    /**
     * Show approval form for adviser
     */
    public function showAdviserForm(Activity $activity)
    {
        if (!Auth::user()->isAdviser() || $activity->status !== 'pending') {
            abort(403);
        }

        return view('approvals.adviser', compact('activity'));
    }

    /**
     * Show approval form for OSA
     */
    public function showOsaForm(Activity $activity)
    {
        if (!Auth::user()->isOsa() || $activity->status !== 'recommended') {
            abort(403);
        }

        return view('approvals.osa', compact('activity'));
    }

    /**
     * Check for conflicts and warnings
     */
    public function checkConflicts(Activity $activity)
    {
        $conflicts = [];
        $warnings = [];

        // Check date conflicts
        if ($activity->hasDateConflict()) {
            $conflicts[] = 'Another activity is scheduled at the same date and location.';
        }

        // Check budget warnings
        if ($activity->budget && $activity->budget < 1000) {
            $warnings[] = 'Budget seems low for this type of activity.';
        }

        // Check if activity is too close to submission date
        $daysDifference = now()->diffInDays($activity->activity_date);
        if ($daysDifference < 5) {
            $warnings[] = 'Activity is scheduled less than 5 days from now.';
        }

        return response()->json([
            'conflicts' => $conflicts,
            'warnings' => $warnings,
        ]);
    }

    /**
     * Notify OSA about recommended activity
     */
    private function notifyOsa(Activity $activity)
    {
        $osaUsers = \App\Models\User::where('role', 'osa')->get();

        foreach ($osaUsers as $osa) {
            Notification::create([
                'user_id' => $osa->id,
                'activity_id' => $activity->id,
                'title' => 'Activity Recommended for Approval',
                'message' => "Activity '{$activity->title}' has been recommended by an adviser and requires your approval.",
                'type' => 'info',
            ]);
        }
    }
}
