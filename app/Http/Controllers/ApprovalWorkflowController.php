<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalWorkflowController extends Controller
{
    /**
     * Submit activity to OSA (start workflow)
     */
    public function submitToOsa(Request $request, Activity $activity)
    {
        // Check if user owns the activity
        if ($activity->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check deadline requirement
        if (!$activity->checkDeadlineRequirement()) {
            return back()->withErrors([
                'deadline' => 'Activity must be submitted at least 5 days before the scheduled date.'
            ]);
        }

        DB::transaction(function () use ($activity) {
            $activity->update([
                'workflow_status' => 'draft',
                'submitted_at' => now(),
            ]);

            // Create activity log
            ActivityLog::create([
                'activity_id' => $activity->id,
                'user_id' => Auth::id(),
                'action' => 'submitted_to_osa',
                'previous_status' => $activity->getOriginal('workflow_status'),
                'new_status' => 'draft',
                'comments' => 'Activity submitted to OSA for approval workflow',
            ]);

            // Create notifications for relevant approvers
            $this->createWorkflowNotifications($activity, 'submitted');
        });

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity successfully submitted to OSA. The approval workflow has begun.');
    }

    /**
     * Adviser notes the activity
     */
    public function adviserNote(Request $request, Activity $activity)
    {
        if (!Auth::user()->isAdviser() && !Auth::user()->isAdmin()) {
            abort(403, 'Only advisers can note activities.');
        }

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'action' => 'required|in:note,reject'
        ]);

        DB::transaction(function () use ($request, $activity) {
            if ($request->action === 'reject') {
                $activity->update([
                    'workflow_status' => 'rejected',
                    'rejected_by' => Auth::id(),
                    'rejected_at' => now(),
                    'rejection_reason' => $request->notes,
                ]);

                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => Auth::id(),
                    'action' => 'rejected_by_adviser',
                    'previous_status' => $activity->getOriginal('workflow_status'),
                    'new_status' => 'rejected',
                    'comments' => 'Activity rejected by adviser: ' . $request->notes,
                ]);
            } else {
                $activity->update([
                    'workflow_status' => 'noted_by_adviser',
                    'adviser_noted_by' => Auth::id(),
                    'adviser_noted_at' => now(),
                    'adviser_notes' => $request->notes,
                ]);

                ActivityLog::create([
                    'activity_id' => $activity->id,
                    'user_id' => Auth::id(),
                    'action' => 'noted_by_adviser',
                    'previous_status' => $activity->getOriginal('workflow_status'),
                    'new_status' => 'noted_by_adviser',
                    'comments' => 'Activity noted by adviser',
                ]);
            }

            $this->createWorkflowNotifications($activity, $request->action === 'reject' ? 'rejected' : 'noted_by_adviser');
        });

        $message = $request->action === 'reject'
            ? 'Activity has been rejected.'
            : 'Activity has been noted and forwarded to Dean/Unit Head.';

        return redirect()->route('activities.show', $activity)->with('success', $message);
    }









    /**
     * Create workflow notifications
     */
    private function createWorkflowNotifications(Activity $activity, string $stage)
    {
        $notifications = [];

        switch ($stage) {
            case 'submitted':
                // Notify adviser whose department matches the activity's organization
                $adviser = \App\Models\User::where('role', 'adviser')
                    ->where('department', $activity->organization)
                    ->where('is_active', true)
                    ->first();
                if ($adviser) {
                    $notifications[] = [
                        'user_id' => $adviser->id,
                        'title' => 'Activity Awaiting Your Note',
                        'message' => "Activity '{$activity->title}' from {$activity->organization} has been submitted and requires your acknowledgment.",
                    ];
                }
                break;

            case 'noted_by_adviser':
                // Notify dean whose department matches the activity's organization/school
                $dean = \App\Models\User::where('role', 'dean')
                    ->where('department', $activity->organization)
                    ->where('is_active', true)
                    ->first();
                if ($dean) {
                    $notifications[] = [
                        'user_id' => $dean->id,
                        'title' => 'Activity Awaiting Your Note',
                        'message' => "Activity '{$activity->title}' from {$activity->organization} has been noted by adviser and requires your acknowledgment.",
                    ];
                }
                break;

            case 'noted_by_dean':
                // Notify PSG adviser
                $psgAdviser = \App\Models\User::where('role', 'psg_adviser')->first();
                if ($psgAdviser) {
                    $notifications[] = [
                        'user_id' => $psgAdviser->id,
                        'title' => 'Activity Awaiting Your Review',
                        'message' => "Activity '{$activity->title}' has been noted by dean and requires your review.",
                    ];
                }
                break;

            case 'reviewed_by_psg':
                // Notify director
                $director = \App\Models\User::where('role', 'director')->where('is_active', true)->first();
                if ($director) {
                    $notifications[] = [
                        'user_id' => $director->id,
                        'title' => 'Activity Awaiting Your Endorsement',
                        'message' => "Activity '{$activity->title}' has been reviewed by PSG and requires your endorsement.",
                    ];
                }
                break;

            case 'endorsed_by_director':
                // Notify VP
                $vp = \App\Models\User::where('role', 'vp')->where('is_active', true)->first();
                if ($vp) {
                    $notifications[] = [
                        'user_id' => $vp->id,
                        'title' => 'Activity Awaiting Final Approval',
                        'message' => "Activity '{$activity->title}' has been endorsed and requires your final approval.",
                    ];
                }
                break;

            case 'approved_by_vp':
                // Notify submitter of approval
                $notifications[] = [
                    'user_id' => $activity->user_id,
                    'title' => 'Activity Approved!',
                    'message' => "Your activity '{$activity->title}' has been approved by VP for Academics.",
                ];
                break;

            case 'rejected':
                // Notify submitter of rejection
                $notifications[] = [
                    'user_id' => $activity->user_id,
                    'title' => 'Activity Rejected',
                    'message' => "Your activity '{$activity->title}' has been rejected. Please check the comments for details.",
                ];
                break;
        }

        foreach ($notifications as $notification) {
            Notification::create([
                'activity_id' => $activity->id,
                'user_id' => $notification['user_id'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'type' => 'workflow',
            ]);
        }
    }

    /**
     * Distribute copies to required offices
     */
    private function distributeCopies(Activity $activity)
    {
        $copyDistribution = [
            'osa' => [
                'office' => 'Office of Student Affairs',
                'distributed_at' => now(),
                'status' => 'distributed'
            ],
            'dean' => [
                'office' => 'Dean/Unit Head',
                'distributed_at' => now(),
                'status' => 'distributed'
            ],
            'director' => [
                'office' => 'Director of Student Affairs and Academic Support Services',
                'distributed_at' => now(),
                'status' => 'distributed'
            ],
            'vp' => [
                'office' => 'Vice President for Academics and Quality Assurance',
                'distributed_at' => now(),
                'status' => 'distributed'
            ]
        ];

        $activity->update([
            'copy_distribution' => $copyDistribution,
            'copies_distributed' => true,
        ]);
    }
}
