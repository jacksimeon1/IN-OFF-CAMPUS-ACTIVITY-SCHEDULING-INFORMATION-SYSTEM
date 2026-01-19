<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;

echo "=== Verification: Department Filter Accuracy ===\n\n";

// Get all activities with their users
$activities = Activity::with('user')->get();

echo "=== Activities by Department (After Fix) ===\n";
$activitiesByDept = $activities->groupBy('organization');

foreach ($activitiesByDept as $dept => $deptActivities) {
    if ($dept && strpos($dept, 'SCHOOL OF') === 0) {
        echo "\n{$dept}: {$deptActivities->count()} activities\n";
        foreach ($deptActivities as $activity) {
            $status = $activity->workflow_status ?? 'draft';
            $type = $activity->type ?? 'in-campus';
            echo "  - {$activity->title} (User: {$activity->user->name}, Status: {$status}, Type: {$type})\n";
        }
    }
}

echo "\n=== Specific Activity Check ===\n";
$dsafasActivity = Activity::where('title', 'dsafas')->with('user')->first();
if ($dsafasActivity) {
    echo "Activity 'dsafas':\n";
    echo "  - Organization: {$dsafasActivity->organization}\n";
    echo "  - User: {$dsafasActivity->user->name}\n";
    echo "  - User Department: {$dsafasActivity->user->department}\n";
    echo "  - Status: {$dsafasActivity->workflow_status}\n";
    echo "  - Type: {$dsafasActivity->type}\n";
    echo "  - Match: " . ($dsafasActivity->organization === $dsafasActivity->user->department ? "✅ YES" : "❌ NO") . "\n";
}

echo "\n=== Filter Test ===\n";
echo "When filtering by 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT':\n";
$businessActivities = Activity::where('organization', 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT')->with('user')->get();
echo "Found {$businessActivities->count()} activities\n";
foreach ($businessActivities as $activity) {
    echo "  - {$activity->title} (User: {$activity->user->name})\n";
}

echo "\nWhen filtering by 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING':\n";
$itActivities = Activity::where('organization', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING')->with('user')->get();
echo "Found {$itActivities->count()} activities\n";
foreach ($itActivities as $activity) {
    echo "  - {$activity->title} (User: {$activity->user->name})\n";
}

echo "\nVerification completed!\n";