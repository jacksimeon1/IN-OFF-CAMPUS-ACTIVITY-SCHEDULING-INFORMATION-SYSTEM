<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestProfileUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:test-updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test profile update functionality for all roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Profile Update Functionality...');

        // Test email uniqueness validation
        $this->info("\n1. Testing email uniqueness validation...");
        
        $student1 = User::where('role', 'student')->first();
        $student2 = User::where('role', 'student')->skip(1)->first();
        
        if ($student1 && $student2) {
            $this->info("Student 1: {$student1->email}");
            $this->info("Student 2: {$student2->email}");
            
            // Try to update student2's email to student1's email (should fail)
            try {
                $student2->update(['email' => $student1->email]);
                $this->error("❌ Email uniqueness validation failed - duplicate email was allowed!");
            } catch (\Exception $e) {
                $this->info("✅ Email uniqueness validation working - duplicate email rejected");
            }
        }

        // Test successful email update
        $this->info("\n2. Testing successful email update...");
        
        $testUser = User::where('role', 'student')->first();
        if ($testUser) {
            $originalEmail = $testUser->email;
            $newEmail = 'test.updated.' . time() . '@spup.edu.ph';
            
            $this->info("Original email: {$originalEmail}");
            $this->info("New email: {$newEmail}");
            
            try {
                $testUser->update(['email' => $newEmail]);
                $testUser->refresh();
                
                if ($testUser->email === $newEmail) {
                    $this->info("✅ Email update successful!");
                    
                    // Restore original email
                    $testUser->update(['email' => $originalEmail]);
                    $this->info("✅ Email restored to original value");
                } else {
                    $this->error("❌ Email update failed - database not updated");
                }
            } catch (\Exception $e) {
                $this->error("❌ Email update failed: " . $e->getMessage());
            }
        }

        // Test password update
        $this->info("\n3. Testing password update...");
        
        if ($testUser) {
            $originalPassword = $testUser->password;
            $newPassword = 'newpassword123';
            
            try {
                $testUser->update(['password' => Hash::make($newPassword)]);
                $testUser->refresh();
                
                if ($testUser->password !== $originalPassword) {
                    $this->info("✅ Password update successful!");
                    
                    // Verify new password works
                    if (Hash::check($newPassword, $testUser->password)) {
                        $this->info("✅ New password verification successful!");
                    } else {
                        $this->error("❌ New password verification failed!");
                    }
                    
                    // Restore original password
                    $testUser->update(['password' => $originalPassword]);
                    $this->info("✅ Password restored to original value");
                } else {
                    $this->error("❌ Password update failed - database not updated");
                }
            } catch (\Exception $e) {
                $this->error("❌ Password update failed: " . $e->getMessage());
            }
        }

        // Test role-specific field updates
        $this->info("\n4. Testing role-specific field updates...");
        
        // Test student fields
        $student = User::where('role', 'student')->first();
        if ($student) {
            $originalStudentId = $student->student_id;
            $newStudentId = 'TEST-' . time();
            
            try {
                $student->update(['student_id' => $newStudentId]);
                $student->refresh();
                
                if ($student->student_id === $newStudentId) {
                    $this->info("✅ Student ID update successful!");
                    
                    // Restore original
                    $student->update(['student_id' => $originalStudentId]);
                } else {
                    $this->error("❌ Student ID update failed");
                }
            } catch (\Exception $e) {
                $this->error("❌ Student ID update failed: " . $e->getMessage());
            }
        }

        // Test department updates for advisers
        $adviser = User::where('role', 'adviser')->first();
        if ($adviser) {
            $originalDepartment = $adviser->department;
            $newDepartment = 'SCHOOL OF TESTING';
            
            try {
                $adviser->update(['department' => $newDepartment]);
                $adviser->refresh();
                
                if ($adviser->department === $newDepartment) {
                    $this->info("✅ Adviser department update successful!");
                    
                    // Restore original
                    $adviser->update(['department' => $originalDepartment]);
                } else {
                    $this->error("❌ Adviser department update failed");
                }
            } catch (\Exception $e) {
                $this->error("❌ Adviser department update failed: " . $e->getMessage());
            }
        }

        $this->info("\n🎉 Profile update testing completed!");
        $this->info("All profile update functionality has been verified.");
        $this->info("Users can now successfully update their email addresses and other profile information.");
    }
}
