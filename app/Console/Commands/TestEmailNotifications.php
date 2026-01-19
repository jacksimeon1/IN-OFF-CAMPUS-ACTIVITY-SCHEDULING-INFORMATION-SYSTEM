<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Activity;
use App\Models\User;
use App\Services\NotificationService;

class TestEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email notifications for activity workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Email Notifications...');

        // Get a sample activity and users
        $activity = Activity::with('user')->first();
        $adviser = User::where('role', 'adviser')->where('is_active', true)->first();
        $student = User::where('role', 'student')->where('is_active', true)->first();

        if (!$activity) {
            $this->error('No activities found. Please create an activity first.');
            return;
        }

        if (!$adviser) {
            $this->error('No adviser found. Please create an adviser user first.');
            return;
        }

        if (!$student) {
            $this->error('No student found. Please create a student user first.');
            return;
        }

        $notificationService = new NotificationService();

        $this->info("Testing with Activity: {$activity->title}");
        $this->info("Adviser: {$adviser->name} ({$adviser->email})");
        $this->info("Student: {$student->name} ({$student->email})");

        // Test 1: Activity Submitted
        $this->info("\n1. Testing Activity Submitted notification...");
        try {
            $notificationService->notifyActivitySubmitted($activity);
            $this->info("✅ Activity submitted notification sent successfully!");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send activity submitted notification: " . $e->getMessage());
        }

        // Test 2: Activity Approved
        $this->info("\n2. Testing Activity Approved notification...");
        try {
            $notificationService->notifyActivityApproved($activity, $adviser, "This looks good! Approved.");
            $this->info("✅ Activity approved notification sent successfully!");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send activity approved notification: " . $e->getMessage());
        }

        // Test 3: Activity Rejected
        $this->info("\n3. Testing Activity Rejected notification...");
        try {
            $notificationService->notifyActivityRejected($activity, $adviser, "Please revise the budget section.");
            $this->info("✅ Activity rejected notification sent successfully!");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send activity rejected notification: " . $e->getMessage());
        }

        // Test 4: Next Approver
        $this->info("\n4. Testing Next Approver notification...");
        try {
            $notificationService->notifyNextApprover($activity);
            $this->info("✅ Next approver notification sent successfully!");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send next approver notification: " . $e->getMessage());
        }

        $this->info("\n🎉 Email notification testing completed!");
        $this->info("Check your email inbox and spam folder for the test emails.");
        $this->info("Also check the application logs for any email sending errors.");
    }
}
