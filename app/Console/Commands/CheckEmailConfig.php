<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivitySubmitted;
use App\Models\Activity;
use App\Models\User;

class CheckEmailConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:check-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check email configuration and send a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking Email Configuration...');

        // Check mail configuration
        $this->info("\n📧 Mail Configuration:");
        $this->info("Driver: " . config('mail.default'));
        $this->info("Host: " . config('mail.mailers.smtp.host', 'Not set'));
        $this->info("Port: " . config('mail.mailers.smtp.port', 'Not set'));
        $this->info("Username: " . config('mail.mailers.smtp.username', 'Not set'));
        $this->info("Encryption: " . config('mail.mailers.smtp.encryption', 'Not set'));
        $this->info("From Address: " . config('mail.from.address', 'Not set'));
        $this->info("From Name: " . config('mail.from.name', 'Not set'));

        // Check if using local testing
        $host = config('mail.mailers.smtp.host');
        if ($host === 'mailpit' || $host === 'localhost' || $host === '127.0.0.1') {
            $this->warn("\n⚠️  WARNING: You're using local email testing!");
            $this->warn("Emails will NOT be delivered to real email addresses.");
            $this->warn("To send real emails, configure SMTP settings in .env file.");
            $this->info("\nFor Gmail, use these settings:");
            $this->info("MAIL_HOST=smtp.gmail.com");
            $this->info("MAIL_PORT=587");
            $this->info("MAIL_USERNAME=your-gmail@gmail.com");
            $this->info("MAIL_PASSWORD=your-app-password");
            $this->info("MAIL_ENCRYPTION=tls");
        }

        // Check if we can send a simple test email
        $testEmail = $this->ask('Enter an email address to send a test email to (or press Enter to skip)');

        if ($testEmail) {
            $this->info("\n📤 Sending test email to: {$testEmail}");

            try {
                // Get a sample activity for testing
                $activity = Activity::with('user')->first();
                $user = User::where('is_active', true)->first();

                if (!$activity || !$user) {
                    $this->error('No activity or user found for testing. Creating a mock test...');
                    
                    // Send a simple test email instead
                    Mail::raw('This is a test email from SPUP Activity Management System. Email configuration is working!', function ($message) use ($testEmail) {
                        $message->to($testEmail)
                                ->subject('SPUP Activity System - Email Test');
                    });
                } else {
                    // Send actual activity notification
                    Mail::to($testEmail)->send(new ActivitySubmitted($activity, $user));
                }

                $this->info("✅ Test email sent successfully!");
                $this->info("Please check your inbox (and spam folder) for the test email.");

            } catch (\Exception $e) {
                $this->error("❌ Failed to send test email: " . $e->getMessage());
                $this->info("\n💡 Common solutions:");
                $this->info("1. Check your .env file for correct MAIL_* settings");
                $this->info("2. Make sure MAIL_MAILER is set (smtp, mailgun, etc.)");
                $this->info("3. Verify SMTP credentials if using SMTP");
                $this->info("4. Check if your email provider allows app passwords");
                $this->info("5. Ensure firewall/network allows outbound email connections");
            }
        }

        $this->info("\n🔧 Email Configuration Tips:");
        $this->info("For Gmail: Use app passwords, set MAIL_HOST=smtp.gmail.com, MAIL_PORT=587");
        $this->info("For development: Consider using Mailtrap or log driver");
        $this->info("For production: Use a reliable email service like SendGrid, Mailgun, or SES");

        $this->info("\n✅ Email configuration check completed!");
    }
}
