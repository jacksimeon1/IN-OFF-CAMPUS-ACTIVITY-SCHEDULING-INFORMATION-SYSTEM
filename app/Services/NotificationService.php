<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification when activity is submitted
     */
    public function notifyActivitySubmitted(Activity $activity)
    {
        // Notify advisers whose department matches the activity's organization
        $advisers = User::where('role', 'adviser')
            ->where('department', $activity->organization)
            ->where('is_active', true)
            ->get();

        foreach ($advisers as $adviser) {
            // Create database notification only
            $this->createNotification(
                $adviser,
                $activity,
                "📋 New Activity Submission",
                "A new activity '{$activity->title}' has been submitted by {$activity->user->name} and is awaiting your review.\n\nActivity Date: {$activity->activity_date->format('M d, Y')}\nLocation: {$activity->location}\nOrganization: {$activity->organization}",
                'info'
            );
        }

        // Notify activity owner
        $this->createNotification(
            $activity->user,
            $activity,
            "✅ Activity Submitted Successfully",
            "Your activity '{$activity->title}' has been submitted successfully and is now in the approval workflow.\n\nNext step: Adviser Review",
            'success'
        );
    }

    /**
     * Send notification when activity is approved at any stage
     */
    public function notifyActivityApproved(Activity $activity, User $approver, string $comments = null)
    {
        $roleName = $approver->getRoleDisplayName();

        // Notify activity owner
        $message = "Your activity '{$activity->title}' has been approved by {$approver->name} ({$roleName}).";

        if ($comments) {
            $message .= "\n\nComments: {$comments}";
        }

        // Check if this is final approval
        if ($activity->workflow_status === 'approved_by_vp') {
            $message .= "\n\n✅ Your activity has been fully approved and is ready to proceed!";
            $title = "✅ Activity Fully Approved!";
        } else {
            $nextRole = $this->getNextApproverRoleName($activity);
            $message .= "\n\nNext step: Approval from {$nextRole}";
            $title = "✅ Activity Approved by {$roleName}";
        }

        // Create database notification
        $this->createNotification(
            $activity->user,
            $activity,
            $title,
            $message,
            'success'
        );

        // Notify next approver if not final step
        if ($activity->workflow_status !== 'approved_by_vp') {
            $this->notifyNextApprover($activity);
        }
    }

    /**
     * Send notification when activity is rejected
     */
    public function notifyActivityRejected(Activity $activity, User $rejector, string $reason = null)
    {
        $roleName = $rejector->getRoleDisplayName();

        $message = "Your activity '{$activity->title}' has been rejected by {$rejector->name} ({$roleName}).";

        if ($reason) {
            $message .= "\n\nReason: {$reason}";
        }

        $message .= "\n\nYou may revise and resubmit your activity if needed.";

        // Create database notification
        $this->createNotification(
            $activity->user,
            $activity,
            "❌ Activity Rejected by {$roleName}",
            $message,
            'error'
        );
    }

    /**
     * Notify the specific rejector that the activity has been revised and resubmitted
     */
    public function notifyResubmissionToRejector(Activity $activity, User $rejector): void
    {
        $student = $activity->user;
        $this->createNotification(
            $rejector,
            $activity,
            "✏️ Resubmission from {$student->name}",
            "The activity '{$activity->title}' has been revised and resubmitted by {$student->name}. It is awaiting your review again.",
            'info'
        );
    }

    /**
     * Notify the owner that their activity was resubmitted to the appropriate role
     */
    public function notifyOwnerResubmitted(Activity $activity, ?User $rejector): void
    {
        $target = $rejector ? ($rejector->name . ' (' . $rejector->getRoleDisplayName() . ')') : $this->getNextApproverRoleName($activity);
        $message = "Your activity '{$activity->title}' has been resubmitted successfully.";
        if ($rejector) {
            $message .= "\n\nNext step: Review by {$target}";
        } else {
            $message .= "\n\nNext step: " . $this->getNextApproverRoleName($activity);
        }

        $this->createNotification(
            $activity->user,
            $activity,
            '✅ Activity Resubmitted',
            $message,
            'success'
        );
    }

    /**
     * Notify next approver in the workflow
     */
    public function notifyNextApprover(Activity $activity)
    {
        $nextRole = $activity->getNextApproverRole();

        if (!$nextRole) {
            return;
        }

        // Special logic for dean notifications - only notify dean of matching department
        if ($nextRole === 'dean') {
            $nextApprovers = User::where('role', 'dean')
                ->where('department', $activity->organization)
                ->where('is_active', true)
                ->get();
        } else {
            $nextApprovers = User::where('role', $nextRole)->where('is_active', true)->get();
        }

        foreach ($nextApprovers as $approver) {
            // Create database notification only
            $this->createNotification(
                $approver,
                $activity,
                "📋 Activity Awaiting Your {$this->getActionName($nextRole)}",
                "Activity '{$activity->title}' by {$activity->user->name} is awaiting your {$this->getActionName($nextRole)}.\n\nActivity Date: {$activity->activity_date->format('M d, Y')}\nLocation: {$activity->location}\nOrganization: {$activity->organization}",
                'info'
            );
        }
    }

    /**
     * Send reminder notifications for pending approvals
     */
    public function sendPendingApprovalReminders()
    {
        // Get activities pending for more than 3 days
        $pendingActivities = Activity::where('created_at', '<', now()->subDays(3))
            ->whereNotIn('workflow_status', ['approved_by_vp', 'rejected'])
            ->get();

        foreach ($pendingActivities as $activity) {
            $nextRole = $activity->getNextApproverRole();
            
            if ($nextRole) {
                $approvers = User::where('role', $nextRole)->where('is_active', true)->get();
                
                foreach ($approvers as $approver) {
                    $daysPending = $activity->created_at->diffInDays(now());
                    
                    $this->createNotification(
                        $approver,
                        $activity,
                        "⏰ Reminder: Activity Pending Approval",
                        "Activity '{$activity->title}' has been pending your approval for {$daysPending} days.\n\nPlease review and take action when convenient.",
                        'warning'
                    );
                }
            }
        }
    }

    /**
     * Create a notification record
     */
    private function createNotification(User $user, Activity $activity, string $title, string $message, string $type)
    {
        Notification::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    /**
     * Get next approver role display name
     */
    private function getNextApproverRoleName(Activity $activity): string
    {
        $nextRole = $activity->getNextApproverRole();
        
        return match($nextRole) {
            'dean' => 'Dean/Unit Head',
            'psg_adviser' => 'PSG Council Adviser',
            'director' => 'Director of Student Affairs',
            'vp' => 'Vice President for Academics',
            default => 'Next Approver'
        };
    }

    /**
     * Get role display name
     */
    private function getRoleDisplayName(string $role): string
    {
        return match($role) {
            'adviser' => 'Adviser',
            'dean' => 'Dean/Unit Head',
            'psg_adviser' => 'PSG Council Adviser',
            'director' => 'Director of Student Affairs',
            'vp' => 'Vice President for Academics',
            default => ucfirst($role)
        };
    }

    /**
     * Get action name for role
     */
    private function getActionName(string $role): string
    {
        return match($role) {
            'adviser' => 'approval',
            'dean' => 'review',
            'psg_adviser' => 'review',
            'director' => 'endorsement',
            'vp' => 'final approval',
            default => 'action'
        };
    }

    /**
     * Send email notification (optional - can be implemented later)
     */
    public function sendEmailNotification(User $user, string $subject, string $message)
    {
        // Email notification implementation can be added here
        // For now, we'll just use dashboard notifications
    }

    /**
     * Notify about upcoming activity deadlines
     */
    public function notifyUpcomingDeadlines()
    {
        // Get activities happening in the next 7 days
        $upcomingActivities = Activity::where('workflow_status', 'approved_by_vp')
            ->where('activity_date', '>=', now())
            ->where('activity_date', '<=', now()->addDays(7))
            ->get();

        foreach ($upcomingActivities as $activity) {
            $daysUntil = now()->diffInDays($activity->activity_date);
            
            $this->createNotification(
                $activity->user,
                $activity,
                "📅 Upcoming Activity Reminder",
                "Your approved activity '{$activity->title}' is scheduled in {$daysUntil} day(s) on {$activity->activity_date->format('M d, Y')}.\n\nLocation: {$activity->location}\nTime: {$activity->start_time->format('g:i A')} - {$activity->end_time->format('g:i A')}",
                'info'
            );
        }
    }

    /**
     * Clean up old notifications
     */
    public function cleanupOldNotifications()
    {
        // Delete read notifications older than 30 days
        Notification::where('is_read', true)
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        // Delete unread notifications older than 90 days
        Notification::where('is_read', false)
            ->where('created_at', '<', now()->subDays(90))
            ->delete();
    }
}
