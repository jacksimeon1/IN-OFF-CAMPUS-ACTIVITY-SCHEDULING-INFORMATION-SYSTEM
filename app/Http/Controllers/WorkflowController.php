<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Show approval form for the current user's role
     */
    public function showApprovalForm(Activity $activity)
    {
        $user = Auth::user();
        
        // Check if user can take action on this activity
        if (!$activity->canUserTakeAction($user)) {
            return redirect()->back()->with('error', 'You are not authorized to approve this activity at this stage.');
        }

        // Determine which view to show based on user role
        $viewName = match($user->role) {
            'adviser' => 'approvals.adviser',
            'dean' => 'approvals.dean',
            'psg_adviser' => 'approvals.psg',
            'director' => 'approvals.director',
            'vp' => 'approvals.vp',
            default => 'approvals.generic'
        };

        return view($viewName, compact('activity'));
    }

    /**
     * Process approval action
     */
    public function processApproval(Request $request, Activity $activity)
    {
        $user = Auth::user();
        
        // Validate request
        $request->validate([
            'action' => 'required|in:note,review,endorse,approve,reject',
            'comments' => 'nullable|string|max:1000',
        ]);

        // Check if user can take action
        if (!$activity->canUserTakeAction($user)) {
            return redirect()->back()->with('error', 'You are not authorized to approve this activity at this stage.');
        }

        DB::beginTransaction();
        
        try {
            $action = $request->input('action');
            $comments = $request->input('comments');
            
            // Advance workflow
            $success = $activity->advanceWorkflow($user, $comments, $action);
            
            if (!$success) {
                throw new \Exception('Failed to process approval');
            }

            // Send notifications using the notification service
            if ($action === 'reject') {
                $this->notificationService->notifyActivityRejected($activity, $user, $comments);
                $message = 'Activity rejected.';
            } else {
                // For note, review, endorse, approve actions - notify next approver
                $this->notificationService->notifyNextApprover($activity);

                $message = match($action) {
                    'note' => 'Activity noted and forwarded successfully!',
                    'review' => 'Activity reviewed and forwarded successfully!',
                    'endorse' => 'Activity endorsed and forwarded successfully!',
                    'approve' => 'Activity approved successfully!',
                    default => 'Activity processed successfully!'
                };
            }

            DB::commit();

            return redirect()->route('dashboard')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'An error occurred while processing the approval: ' . $e->getMessage());
        }
    }



    /**
     * Get activities pending approval for current user
     */
    public function getPendingActivities()
    {
        $user = Auth::user();
        $userLevel = $user->getApprovalLevel();

        if (!$userLevel) {
            $activities = collect();
        } else {
            // Get activities that need approval at this user's level
            $activities = Activity::where(function($query) use ($userLevel) {
                switch ($userLevel) {
                    case 'adviser_noted':
                        $query->whereIn('workflow_status', ['draft', 'submitted']);
                        break;
                    case 'dean_noted':
                        $query->where('workflow_status', 'noted_by_adviser');
                        break;
                    case 'psg_reviewed':
                        $query->where('workflow_status', 'noted_by_dean');
                        break;
                    case 'director_endorsed':
                        $query->where('workflow_status', 'reviewed_by_psg');
                        break;
                    case 'vp_approved':
                        $query->where('workflow_status', 'endorsed_by_director');
                        break;
                }
            })
            ->with(['user', 'logs'])
            ->orderBy('created_at', 'desc')
            ->get();
        }

        // Return appropriate view based on user role (focus on approval workflow roles)
        $viewName = match($user->role) {
            'adviser' => 'approvals.adviser-list',
            'dean' => 'approvals.dean-list',
            'psg_adviser' => 'approvals.psg-list',
            'director' => 'approvals.director-list',
            'vp' => 'approvals.vp-list',
            // Other roles get generic view
            'admin', 'student', 'osa' => 'approvals.activities-list',
            default => 'approvals.activities-list'
        };

        return view($viewName, compact('activities', 'user'));
    }

    /**
     * Show activity details for approval
     */
    public function showActivityDetails(Activity $activity)
    {
        $user = Auth::user();
        
        // Check if user has permission to view this activity
        if (!$activity->canUserTakeAction($user) && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'You do not have permission to view this activity.');
        }

        return view('approvals.activity-details', compact('activity'));
    }
}
