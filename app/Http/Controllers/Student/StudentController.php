<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\User;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isStudent()) {
                abort(403, 'Access denied. Student Officers only.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Student Officer-specific statistics
        $totalActivities = Activity::where('user_id', $user->id)->count();
        $approvedActivities = Activity::where('user_id', $user->id)
            ->where('workflow_status', 'approved_by_vp')
            ->count();
        $pendingActivities = Activity::where('user_id', $user->id)
            ->whereNotIn('workflow_status', ['approved_by_vp', 'rejected'])
            ->count();
        $rejectedActivities = Activity::where('user_id', $user->id)
            ->where('workflow_status', 'rejected')
            ->count();

        // This month activities
        $thisMonthActivities = Activity::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->count();

        return view('student.dashboard', compact(
            'totalActivities',
            'approvedActivities',
            'pendingActivities',
            'rejectedActivities',
            'thisMonthActivities'
        ));
    }

    public function profile()
    {
        return view('student.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('student.profile')->with('status', 'profile-updated');
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

        return redirect()->route('student.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $activities = Activity::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.activities', compact('activities'));
    }

    public function calendar()
    {
        // Get all approved activities for calendar display (not just user's activities)
        $calendarActivities = Activity::select('id', 'title', 'activity_date', 'end_date', 'start_time', 'end_time', 'location', 'workflow_status')
            ->where('workflow_status', 'approved_by_vp')
            ->orderBy('activity_date')
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'activity_date' => $activity->activity_date->format('Y-m-d'),
                    'end_date' => $activity->end_date->format('Y-m-d'),
                    'start_time' => \Carbon\Carbon::parse($activity->start_time)->format('g:i A'),
                    'end_time' => \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),
                    'location' => $activity->location,
                    'status' => 'approved'
                ];
            });

        return view('student.calendar', compact('calendarActivities'));
    }

    public function createActivity()
    {
        return view('student.create-activity');
    }

    public function storeActivity(Request $request)
    {
        // Activity creation logic here
        // This would be moved from the main ActivityController
        return redirect()->route('student.activities')->with('success', 'Activity created successfully!');
    }

    public function showActivity(Activity $activity)
    {
        // Ensure student officer can only view their own activities
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        return view('student.show-activity', compact('activity'));
    }

    public function editActivity(Activity $activity)
    {
        // Ensure student officer can only edit their own activities
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        // Only allow editing if activity is still in draft or was rejected
        if (!in_array($activity->workflow_status, ['draft', 'rejected'])) {
            return redirect()->route('student.activities')
                ->with('error', 'You can only edit activities in Draft or Rejected status.');
        }

        // Reuse the main activities edit view
        $organizations = \App\Models\Organization::active()->get();
        return view('activities.edit', compact('activity', 'organizations'));
    }

    public function updateActivity(Request $request, Activity $activity)
    {
        // Activity update logic here
        return redirect()->route('student.activities')->with('success', 'Activity updated successfully!');
    }

    public function deleteActivity(Activity $activity)
    {
        // Ensure student officer can only delete their own activities
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Access denied.');
        }

        // Only allow deletion if activity is still in draft
        if ($activity->workflow_status !== 'draft') {
            return redirect()->route('student.activities')
                ->with('error', 'Cannot delete activity that has been submitted for approval.');
        }

        $activity->delete();
        return redirect()->route('student.activities')->with('success', 'Activity deleted successfully!');
    }
}
