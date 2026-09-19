<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\NotificationService;

class AdviserController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdviser()) {
                abort(403, 'Access denied. Advisers only.');
            }
            return $next($request);
        });
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Adviser-specific statistics
        $pendingApprovals = Activity::where('workflow_status', 'draft')
            ->where('organization', $user->department)
            ->count();
        $approvedByMe = Activity::where('adviser_noted_by', $user->id)
            ->where('organization', $user->department)
            ->count();
        $totalStudentActivities = Activity::where('organization', $user->department)->count();
        $thisMonthActivities = Activity::where('organization', $user->department)
            ->whereMonth('created_at', now()->month)->count();

        // Activities awaiting adviser approval
        $activitiesAwaitingApproval = Activity::with(['user'])
            ->where('workflow_status', 'draft')
            ->where('organization', $user->department)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent department activities
        $recentDepartmentActivities = Activity::with(['user'])
            ->where('organization', $user->department)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('adviser.dashboard', compact(
            'pendingApprovals',
            'approvedByMe',
            'totalStudentActivities',
            'thisMonthActivities',
            'activitiesAwaitingApproval',
            'recentDepartmentActivities'
        ));
    }

    public function profile()
    {
        return view('adviser.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'department' => ['required', 'string'],
        ]);

        $user->update($request->only(['name', 'email', 'department']));

        return redirect()->route('adviser.profile')->with('status', 'profile-updated');
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

        return redirect()->route('adviser.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $user = Auth::user();

        $activities = Activity::with(['user'])
            ->where('organization', $user->department)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('adviser.activities', compact('activities'));
    }

    public function pendingApprovals()
    {
        $user = Auth::user();

        $activities = Activity::with(['user'])
            ->where('workflow_status', 'draft')
            ->where('organization', $user->department)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('adviser.pending-approvals', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        $user = Auth::user();

        // Ensure adviser can only view activities from their assigned school/department
        if ($activity->organization !== $user->department) {
            abort(403, 'Access denied. You can only view activities from your assigned school.');
        }

        return view('adviser.show-activity', compact('activity'));
    }

    public function approveActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Ensure adviser can only approve activities from their assigned school/department
        if ($activity->organization !== $user->department) {
            abort(403, 'Access denied. You can only approve activities from your assigned school.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'action' => 'required|in:note,reject'
        ]);

        if ($request->action === 'reject') {
            $activity->update([
                'workflow_status' => 'rejected',
                'rejected_by' => $user->id,
                'rejected_at' => now(),
                'rejection_reason' => $request->notes,
            ]);
        } else {
            $activity->update([
                'workflow_status' => 'noted_by_adviser',
                'adviser_noted_by' => $user->id,
                'adviser_noted_at' => now(),
                'adviser_notes' => $request->notes,
            ]);
        }

        return redirect()->route('adviser.pending-approvals')
            ->with('success', 'Activity ' . ($request->action === 'reject' ? 'rejected' : 'approved') . ' successfully!');
    }

    /**
     * Note and forward activity to dean
     */
    public function noteActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Ensure adviser can only note activities from their assigned school/department
        if ($activity->organization !== $user->department) {
            abort(403, 'Access denied. You can only note activities from your assigned school.');
        }

        // Validate request
        $request->validate([
            'action' => 'required|in:note,reject',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($request->action === 'reject') {
            $request->validate([
                'notes' => 'required|string|max:1000',
            ]);
        }

        try {
            if ($request->action === 'note') {
                // Note and forward to dean
                $activity->update([
                    'workflow_status' => 'noted_by_adviser',
                    'adviser_noted_by' => $user->id,
                    'adviser_noted_at' => now(),
                    'adviser_notes' => $request->notes,
                ]);

                // Create activity log
                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => $user->id,
                    'action' => 'noted',
                    'previous_status' => 'draft',
                    'new_status' => 'noted_by_adviser',
                    'comments' => $request->notes ?? 'Activity noted and forwarded to dean',
                ]);

                // Send notifications
                $this->notificationService->notifyActivityApproved($activity, $user, $request->notes);

                $message = 'Activity noted and forwarded to dean successfully!';
            } else {
                // Reject activity
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
                    'previous_status' => 'draft',
                    'new_status' => 'rejected',
                    'comments' => $request->notes,
                ]);

                // Send notifications
                $this->notificationService->notifyActivityRejected($activity, $user, $request->notes);

                $message = 'Activity rejected successfully!';
            }

            return redirect()->route('adviser.pending-approvals')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process activity. Please try again.');
        }
    }
}
