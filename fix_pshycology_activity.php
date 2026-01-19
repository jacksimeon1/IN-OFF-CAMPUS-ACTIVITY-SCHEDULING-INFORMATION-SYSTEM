<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;

echo "=== Fixing Pshycology Activity Department ===\n\n";

// Find the pshycology activity
$pshycologyActivity = Activity::where('title', 'pshycology activity')->first();

if ($pshycologyActivity) {
    echo "Found 'pshycology activity' (ID: {$pshycologyActivity->id})\n";
    echo "Current owner: {$pshycologyActivity->user->name} from {$pshycologyActivity->user->department}\n";
    
    // Find Student Officer 6 from SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION
    $sasteStudent = User::where('department', 'SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION')
        ->where('role', 'student')
        ->first();
    
    if ($sasteStudent) {
        echo "Moving activity to: {$sasteStudent->name} from {$sasteStudent->department}\n";
        
        // Update the activity owner
        $pshycologyActivity->user_id = $sasteStudent->id;
        $pshycologyActivity->save();
        
        echo "✅ Successfully moved 'pshycology activity' to SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION\n";
    } else {
        echo "❌ No student found in SCHOOL OF ARTS SCIENCES AND TEACHER EDUCATION\n";
    }
} else {
    echo "❌ 'pshycology activity' not found\n";
}

// Also let's move some other activities to different departments to match your list
echo "\n=== Moving Other Activities to Match Your List ===\n";

$activityMoves = [
    'dsafas' => 'SCHOOL OF BUSINESS ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
    'dasfas' => 'SCHOOL OF MEDICINE',
    'meowww' => 'SCHOOL OF MEDICINE'
];

foreach ($activityMoves as $activityTitle => $targetDept) {
    $activity = Activity::where('title', $activityTitle)->first();
    if ($activity) {
        $targetUser = User::where('department', $targetDept)->where('role', 'student')->first();
        if ($targetUser) {
            echo "Moving '{$activityTitle}' to {$targetUser->name} from {$targetDept}\n";
            $activity->user_id = $targetUser->id;
            $activity->save();
            echo "✅ Moved successfully\n";
        } else {
            echo "❌ No student found in {$targetDept}\n";
        }
    }
}

echo "\n=== Final Activities by Department ===\n";
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