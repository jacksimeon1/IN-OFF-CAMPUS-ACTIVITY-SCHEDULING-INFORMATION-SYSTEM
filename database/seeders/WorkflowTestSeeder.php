<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WorkflowTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for testing
        $student = User::where('role', 'student')->first();
        $adviser = User::where('role', 'adviser')->first();
        $dean = User::where('role', 'dean')->first();
        $psgAdviser = User::where('role', 'psg_adviser')->first();
        $director = User::where('role', 'director')->first();
        $vp = User::where('role', 'vp')->first();

        if (!$student || !$adviser || !$dean || !$psgAdviser || !$director || !$vp) {
            $this->command->error('Required users not found. Please run UserSeeder first.');
            return;
        }

        // Create activities at different workflow stages
        $this->createActivityAtStage('draft', $student);
        $this->createActivityAtStage('noted_by_adviser', $student, $adviser);
        $this->createActivityAtStage('noted_by_dean', $student, $adviser, $dean);
        $this->createActivityAtStage('reviewed_by_psg', $student, $adviser, $dean, $psgAdviser);
        $this->createActivityAtStage('endorsed_by_director', $student, $adviser, $dean, $psgAdviser, $director);
        $this->createActivityAtStage('approved_by_vp', $student, $adviser, $dean, $psgAdviser, $director, $vp);
        $this->createActivityAtStage('rejected', $student, $adviser);

        $this->command->info('Workflow test data created successfully!');
    }

    private function createActivityAtStage($stage, $student, $adviser = null, $dean = null, $psgAdviser = null, $director = null, $vp = null)
    {
        $activityData = [
            'user_id' => $student->id,
            'title' => $this->getActivityTitle($stage),
            'type' => 'in-campus',
            'activity_date' => Carbon::now()->addDays(rand(10, 30)),
            'end_date' => Carbon::now()->addDays(rand(10, 30)),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'location' => 'SPUP Auditorium',
            'objective_1' => 'To enhance student learning and development',
            'objective_2' => 'To promote academic excellence',
            'objective_3' => 'To foster community engagement',
            'leaders' => 'Student Council Officers',
            'speakers' => 'Industry Experts',
            'budget' => 50000.00,
            'organization' => 'Computer Science Student Organization',
            'expected_participants' => 150,
            'workflow_status' => $stage,
            'status' => $this->getStatusFromWorkflow($stage),
            'meets_deadline_requirement' => true,
            'days_before_activity' => rand(10, 30),
        ];

        // Set timestamps and approvers based on stage
        $now = Carbon::now();
        
        if ($stage !== 'draft') {
            $activityData['submitted_at'] = $now->subDays(5);
        }

        if (in_array($stage, ['noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp', 'rejected']) && $adviser) {
            $activityData['adviser_noted_by'] = $adviser->id;
            $activityData['adviser_noted_at'] = $now->subDays(4);
            $activityData['adviser_notes'] = 'Activity approved by adviser. Good objectives and planning.';
        }

        if (in_array($stage, ['noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp']) && $dean) {
            $activityData['dean_noted_by'] = $dean->id;
            $activityData['dean_noted_at'] = $now->subDays(3);
            $activityData['dean_notes'] = 'Activity aligns with department objectives. Approved for next stage.';
        }

        if (in_array($stage, ['reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp']) && $psgAdviser) {
            $activityData['psg_reviewed_by'] = $psgAdviser->id;
            $activityData['psg_reviewed_at'] = $now->subDays(2);
            $activityData['psg_review_comments'] = 'Activity complies with student council guidelines. Recommended for approval.';
        }

        if (in_array($stage, ['endorsed_by_director', 'approved_by_vp']) && $director) {
            $activityData['director_endorsed_by'] = $director->id;
            $activityData['director_endorsed_at'] = $now->subDays(1);
            $activityData['director_endorsement_comments'] = 'Activity meets institutional policies. Endorsed for final approval.';
        }

        if ($stage === 'approved_by_vp' && $vp) {
            $activityData['vp_approved_by'] = $vp->id;
            $activityData['vp_approved_at'] = $now;
            $activityData['vp_approval_comments'] = 'Activity approved. Excellent planning and academic merit.';
        }

        if ($stage === 'rejected' && $adviser) {
            $activityData['rejected_by'] = $adviser->id;
            $activityData['rejected_at'] = $now->subDays(3);
            $activityData['rejection_reason'] = 'Budget needs revision. Please provide more detailed breakdown.';
        }

        $activity = Activity::create($activityData);

        // Create activity log
        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => $student->id,
            'action' => 'created',
            'previous_status' => null,
            'new_status' => $stage,
            'comments' => "Test activity created at {$stage} stage",
        ]);

        // Create sample notifications
        $this->createSampleNotifications($activity, $stage, $student, $adviser, $dean, $psgAdviser, $director, $vp);
    }

    private function getActivityTitle($stage)
    {
        $titles = [
            'draft' => 'Annual Tech Summit (Draft)',
            'noted_by_adviser' => 'Student Leadership Workshop',
            'noted_by_dean' => 'Academic Excellence Awards',
            'reviewed_by_psg' => 'Community Outreach Program',
            'endorsed_by_director' => 'Innovation and Research Expo',
            'approved_by_vp' => 'International Conference on Education',
            'rejected' => 'Budget Planning Workshop (Rejected)',
        ];

        return $titles[$stage] ?? 'Sample Activity';
    }

    private function getStatusFromWorkflow($workflowStatus)
    {
        return match($workflowStatus) {
            'approved_by_vp' => 'approved',
            'rejected' => 'rejected',
            default => 'pending'
        };
    }

    private function createSampleNotifications($activity, $stage, $student, $adviser, $dean, $psgAdviser, $director, $vp)
    {
        // Create notification for student
        Notification::create([
            'user_id' => $student->id,
            'activity_id' => $activity->id,
            'title' => $this->getNotificationTitle($stage),
            'message' => $this->getNotificationMessage($stage, $activity->title),
            'type' => $stage === 'rejected' ? 'error' : ($stage === 'approved_by_vp' ? 'success' : 'info'),
            'is_read' => rand(0, 1) == 1,
        ]);

        // Create notifications for next approver if applicable
        $nextApprover = $this->getNextApprover($stage, $adviser, $dean, $psgAdviser, $director, $vp);
        
        if ($nextApprover && !in_array($stage, ['approved_by_vp', 'rejected'])) {
            Notification::create([
                'user_id' => $nextApprover->id,
                'activity_id' => $activity->id,
                'title' => '📋 Activity Awaiting Your Review',
                'message' => "Activity '{$activity->title}' is awaiting your review and approval.",
                'type' => 'info',
                'is_read' => false,
            ]);
        }
    }

    private function getNotificationTitle($stage)
    {
        return match($stage) {
            'draft' => '📝 Activity Draft Created',
            'noted_by_adviser' => '✅ Adviser Approval',
            'noted_by_dean' => '✅ Dean Approval',
            'reviewed_by_psg' => '✅ PSG Council Review',
            'endorsed_by_director' => '✅ Director Endorsement',
            'approved_by_vp' => '🎉 Final Approval',
            'rejected' => '❌ Activity Rejected',
            default => 'Activity Update'
        };
    }

    private function getNotificationMessage($stage, $title)
    {
        return match($stage) {
            'draft' => "Your activity '{$title}' has been saved as draft.",
            'noted_by_adviser' => "Your activity '{$title}' has been approved by your adviser.",
            'noted_by_dean' => "Your activity '{$title}' has been noted by the Dean.",
            'reviewed_by_psg' => "Your activity '{$title}' has been reviewed by PSG Council.",
            'endorsed_by_director' => "Your activity '{$title}' has been endorsed by the Director.",
            'approved_by_vp' => "🎉 Your activity '{$title}' has been fully approved!",
            'rejected' => "Your activity '{$title}' has been rejected. Please review and resubmit.",
            default => "Your activity '{$title}' has been updated."
        };
    }

    private function getNextApprover($stage, $adviser, $dean, $psgAdviser, $director, $vp)
    {
        return match($stage) {
            'draft' => $adviser,
            'noted_by_adviser' => $dean,
            'noted_by_dean' => $psgAdviser,
            'reviewed_by_psg' => $director,
            'endorsed_by_director' => $vp,
            default => null
        };
    }
}
