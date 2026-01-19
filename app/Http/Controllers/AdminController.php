<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function dispatch;

class AdminController extends Controller
{
    /**
     * Display users management page
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_id' => 'nullable|string|max:255|unique:users',
            'department' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:255',
            'role' => 'required|in:admin,student,adviser,dean,psg_adviser,director,vp,osa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'student_id' => 'nullable|string|max:255|unique:users,student_id,' . $user->id,
            'department' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:255',
            'role' => 'required|in:admin,student,adviser,dean,psg_adviser,director,vp,osa',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Display organizations management page
     */
    public function organizations(Request $request)
    {
        $query = Organization::with('adviser');

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $organizations = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Show create organization form
     */
    public function createOrganization()
    {
        $advisers = User::where('role', 'adviser')->get();
        return view('admin.organizations.create', compact('advisers'));
    }

    /**
     * Store new organization
     */
    public function storeOrganization(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'required|string|max:255',
            'adviser_id' => 'nullable|exists:users,id',
        ]);

        Organization::create($validated);

        return redirect()->route('admin.organizations')->with('success', 'Organization created successfully!');
    }

    /**
     * Show edit organization form
     */
    public function editOrganization(Organization $organization)
    {
        $advisers = User::where('role', 'adviser')->get();
        return view('admin.organizations.edit', compact('organization', 'advisers'));
    }

    /**
     * Update organization
     */
    public function updateOrganization(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'required|string|max:255',
            'adviser_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $organization->update($validated);

        return redirect()->route('admin.organizations')->with('success', 'Organization updated successfully!');
    }

    /**
     * Delete organization
     */
    public function deleteOrganization(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('admin.organizations')->with('success', 'Organization deleted successfully!');
    }

    /**
     * Display activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with(['activity', 'user']);

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.logs.index', compact('logs'));
    }

    /**
     * Display activities management page for admin
     */
    public function activities(Request $request)
    {
        $query = Activity::with(['user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Show activity details for admin
     */
    public function showActivity(Activity $activity)
    {
        $activity->load(['user', 'logs.user']);
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Show form to edit activity status only
     */
    public function editActivityStatus(Activity $activity)
    {
        return view('admin.activities.edit-status', compact('activity'));
    }

    /**
     * Update activity status only (admin cannot edit student content)
     */
    public function updateActivityStatus(Request $request, Activity $activity)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500'
        ]);

        $oldStatus = $activity->status;

        // Admin can only update the status
        $activity->update([
            'status' => $request->status
        ]);

        // Prepare comments for the log
        $comments = "Status changed from {$oldStatus} to {$request->status}";
        if ($request->admin_notes) {
            $comments .= "\nAdmin Notes: " . $request->admin_notes;
        }
        if ($request->status === 'rejected' && $request->rejection_reason) {
            $comments .= "\nRejection Reason: " . $request->rejection_reason;
        }

        // Log the status change
        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => auth()->id(),
            'action' => 'status_updated',
            'previous_status' => $oldStatus,
            'new_status' => $request->status,
            'comments' => $comments
        ]);

        // Create notification for the student who submitted the activity
        $notificationTitle = match($request->status) {
            'approved' => 'Activity Approved! 🎉',
            'rejected' => 'Activity Rejected',
            'recommended' => 'Activity Recommended',
            'pending' => 'Activity Status Updated',
            default => 'Activity Status Updated'
        };

        $notificationMessage = "Your activity '{$activity->title}' status has been changed from " .
                              ucfirst($oldStatus) . " to " . ucfirst($request->status) . " by an administrator.";

        if ($request->admin_notes) {
            $notificationMessage .= "\n\nAdmin Notes: " . $request->admin_notes;
        }

        if ($request->status === 'rejected' && $request->rejection_reason) {
            $notificationMessage .= "\n\nReason: " . $request->rejection_reason;
        }

        $notificationType = match($request->status) {
            'approved' => 'success',
            'rejected' => 'error',
            'recommended' => 'info',
            default => 'info'
        };

        Notification::create([
            'user_id' => $activity->user_id,
            'activity_id' => $activity->id,
            'title' => $notificationTitle,
            'message' => $notificationMessage,
            'type' => $notificationType,
        ]);

        return redirect()->route('admin.activities')
            ->with('success', 'Activity status updated successfully and notification sent to student.');
    }

    /**
     * Download activity file for admin
     */
    public function downloadActivityFile(Activity $activity, $fileType)
    {
        // Validate file type
        if (!in_array($fileType, ['budget_file', 'permit_file', 'supporting_documents'])) {
            abort(404);
        }

        // Get the file path from the activity
        $filePath = $activity->{$fileType};

        if (!$filePath) {
            abort(404, 'File not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found in storage');
        }

        // Extract original filename from stored filename
        $filename = basename($filePath);
        $downloadFilename = $filename;
        if (preg_match('/^\d+_(.+)$/', $filename, $matches)) {
            // If filename follows the new format (timestamp_originalname.ext), use the original name
            $downloadFilename = $matches[1];
        }

        // Return download response with original filename
        return Storage::disk('public')->download($filePath, $downloadFilename);
    }

    /**
     * Delete an activity
     */
    public function deleteActivity(Request $request, Activity $activity)
    {
        try {
            // Collect file paths first to delete after response
            $filesToDelete = [];
            if ($activity->budget_file) {
                $filesToDelete[] = $activity->budget_file;
            }
            if ($activity->permit_file) {
                $filesToDelete[] = $activity->permit_file;
            }
            if ($activity->supporting_documents) {
                $filesToDelete[] = $activity->supporting_documents;
            }

            // Delete the activity quickly (DB-only)
            $activity->delete();

            // Defer file deletions until after response is sent to keep UI snappy
            if (!empty($filesToDelete)) {
                dispatch(function () use ($filesToDelete) {
                    try {
                        foreach ($filesToDelete as $path) {
                            if ($path) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    } catch (\Throwable $t) {
                        // Swallow any storage errors silently for UI responsiveness
                    }
                })->afterResponse();
            }

            // Return no-content for AJAX/JSON to avoid any rendering/redirect
            if ($request->wantsJson() || $request->ajax()) {
                return response()->noContent(); // 204
            }

            return redirect()->route('admin.dashboard', ['tab' => 'activities'])
                ->with('success', 'Activity deleted successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete activity.'], 500);
            }
            return redirect()->route('admin.dashboard', ['tab' => 'activities'])
                ->with('error', 'Failed to delete activity. Please try again.');
        }
    }
}
