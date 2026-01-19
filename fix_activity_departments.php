<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;

echo "=== Fixing Activity Departments ===\n\n";

// Find the pshycology activity
$pshycologyActivity = Activity::where('title', 'pshycology activity')->with('user')->first();

if ($pshycologyActivity) {
    echo "Found 'pshycology activity':\n";
    echo "- Current User: {$pshycologyActivity->user->name}\n";
    echo "- Current Department: {$pshycologyActivity->user->department}\n";
    echo "- Status: {$pshycologyActivity->workflow_status}\n";
    
    // Find a user from SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION
    $sasteUser = User::where('department', 'SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION')
        ->where('role', 'student')
        ->first();
    
    if (!$sasteUser) {
        echo "No student found from SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION, checking all users:\n";
        $sasteUsers = User::where('department', 'SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION')->get();
        foreach ($sasteUsers as $user) {
            echo "- {$user->name} ({$user->role})\n";
        }
        $sasteUser = $sasteUsers->first();
    }
    
    if ($sasteUser) {
        echo "\nUpdating activity to be owned by: {$sasteUser->name} from {$sasteUser->department}\n";
        
        // Update the activity's user
        $pshycologyActivity->user_id = $sasteUser->id;
        $pshycologyActivity->save();
        
        echo "✅ Updated successfully!\n";
    } else {
        echo "❌ No user found from SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION\n";
    }
} else {
    echo "❌ 'pshycology activity' not found\n";
}

echo "\n=== Current Activities by Department ===\n";
$activities = Activity::with('user')->get();
$departmentGroups = $activities->groupBy('user.department');

foreach ($departmentGroups as $dept => $acts) {
    if (strpos($dept, 'SCHOOL OF') === 0) {
        echo "\n{$dept}: {$acts->count()} activities\n";
        foreach ($acts as $activity) {
            echo "  - {$activity->title} (Status: {$activity->workflow_status})\n";
        }
    }
}

echo "\nFix completed!\n";