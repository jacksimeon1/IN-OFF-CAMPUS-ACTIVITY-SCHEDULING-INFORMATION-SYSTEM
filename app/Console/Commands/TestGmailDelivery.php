<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Activity;
use App\Mail\ActivitySubmitted;

class TestGmailDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-gmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email delivery to Gmail specifically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Gmail Email Delivery...');

        // Check current email configuration
        $this->info("\n📧 Current Email Configuration:");
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Port: " . config('mail.mailers.smtp.port'));
        $this->info("Username: " . config('mail.mailers.smtp.username'));
        $this->info("From: " . config('mail.from.address'));

        // Check if using local testing
        $host = config('mail.mailers.smtp.host');
        if ($host === 'mailpit' || $host === 'localhost' || $host === '127.0.0.1') {
            $this->error("\n❌ PROBLEM FOUND!");
            $this->error("You're using LOCAL EMAIL TESTING (Mailpit)");
            $this->error("This is why you're not receiving emails in Gmail!");
            
            $this->info("\n🔧 TO FIX THIS:");
            $this->info("1. Update your .env file with these settings:");
            $this->info("   MAIL_HOST=smtp.gmail.com");
            $this->info("   MAIL_PORT=587");
            $this->info("   MAIL_USERNAME=robertsimeon12345@gmail.com");
            $this->info("   MAIL_PASSWORD=your-gmail-app-password");
            $this->info("   MAIL_ENCRYPTION=tls");
            $this->info("   MAIL_FROM_ADDRESS=robertsimeon12345@gmail.com");
            
            $this->info("\n2. Get Gmail App Password:");
            $this->info("   - Go to Google Account Settings");
            $this->info("   - Security > 2-Step Verification > App passwords");
            $this->info("   - Generate password for 'Mail'");
            $this->info("   - Use that password in MAIL_PASSWORD");
            
            $this->info("\n3. Run: php artisan config:clear");
            $this->info("4. Test again with: php artisan email:test-gmail");
            
            return;
        }

        // Test simple email
        $testEmail = 'robertsimeon12345@gmail.com';
        $this->info("\n📤 Sending test email to: {$testEmail}");

        try {
            // Send simple test email
            Mail::raw('🎉 SUCCESS! Your SPUP Activity System email is working!\n\nThis confirms that:\n✅ SMTP configuration is correct\n✅ Gmail delivery is working\n✅ Activity notifications will be delivered\n\nYou should now receive activity notifications in your Gmail inbox.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('✅ SPUP Activity System - Email Test SUCCESS!');
            });

            $this->info("✅ Simple test email sent successfully!");

        } catch (\Exception $e) {
            $this->error("❌ Failed to send simple test email:");
            $this->error($e->getMessage());
            
            $this->info("\n💡 Common solutions:");
            $this->info("1. Check Gmail App Password is correct");
            $this->info("2. Ensure 2-Step Verification is enabled");
            $this->info("3. Verify SMTP settings in .env");
            $this->info("4. Check firewall/antivirus blocking SMTP");
            
            return;
        }

        // Test activity notification email
        $this->info("\n📋 Testing activity notification email...");

        $student = User::where('email', 'robertsimeon12345@gmail.com')->first();
        $adviser = User::where('role', 'adviser')->where('is_active', true)->first();
        $activity = Activity::with('user')->first();

        if (!$student) {
            $this->warn("⚠️  Student with email robertsimeon12345@gmail.com not found");
            $this->info("Creating test scenario...");
            
            // Use any student for testing
            $student = User::where('role', 'student')->first();
            if (!$student) {
                $this->error("❌ No students found in database");
                return;
            }
        }

        if (!$adviser) {
            $this->error("❌ No advisers found in database");
            return;
        }

        if (!$activity) {
            $this->error("❌ No activities found in database");
            return;
        }

        try {
            // Send actual activity notification
            Mail::to($testEmail)->send(new ActivitySubmitted($activity, $adviser));
            $this->info("✅ Activity notification email sent successfully!");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send activity notification:");
            $this->error($e->getMessage());
        }

        // Check logs
        $this->info("\n📋 Check Laravel logs for details:");
        $this->info("tail -f storage/logs/laravel.log");

        $this->info("\n🎯 Summary:");
        $this->info("If you received the test emails in Gmail, the system is working!");
        $this->info("Activity notifications will now be delivered to Gmail when:");
        $this->info("- Students submit activities");
        $this->info("- Activities are approved/rejected");
        $this->info("- Activities need your action");

        $this->info("\n📧 Check your Gmail inbox (and spam folder) for test emails!");
    }
}
