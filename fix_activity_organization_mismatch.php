<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;

echo "=== Fixing Activity Organization Mismatch ===\n\n";

// Find all activities where the organization doesn't match the user's department
$activities = Activity::with('user')->get();

echo "Total activities found: " . $activities->count() . "\n\n";

$mismatches = [];
$fixes = [];

foreach ($activities as $activity) {
    if ($activity->user && $activity->organization !== $activity->user->department) {
        $mismatches[] = [
            'id' => $activity->id,
            'title' => $activity->title,
            'current_organization' => $activity->organization,
            'user_department' => $activity->user->department,
            'user_name' => $activity->user->name
        ];
    }
}

echo "Found " . count($mismatches) . " activities with organization/department mismatches:\n\n";

foreach ($mismatches as $mismatch) {
    echo "Activity ID {$mismatch['id']}: '{$mismatch['title']}'\n";
    echo "  Current organization: {$mismatch['current_organization']}\n";
    echo "  User department: {$mismatch['user_department']}\n";
    echo "  User: {$mismatch['user_name']}\n";
    echo "  → Fixing...\n";
    
    // Fix the activity
    $activity = Activity::find($mismatch['id']);
    $activity->organization = $mismatch['user_department'];
    $activity->save();
    
    $fixes[] = $mismatch['id'];
    echo "  ✅ Fixed!\n\n";
}

echo "=== Summary ===\n";
echo "Fixed " . count($fixes) . " activities\n";

if (count($fixes) > 0) {
    echo "Fixed activity IDs: " . implode(', ', $fixes) . "\n";
}

// Show current activities by department
echo "\n=== Current Activities by Department ===\n";
$activitiesByDept = Activity::with('user')
    ->get()
    ->groupBy('organization')
    ->map(function($activities) {
        return $activities->count();
    })
    ->sortDesc();

foreach ($activitiesByDept as $dept => $count) {
    if ($dept && strpos($dept, 'SCHOOL OF') === 0) {
        echo "{$dept}: {$count} activities\n";
    }
}

echo "\nFix completed!\n";