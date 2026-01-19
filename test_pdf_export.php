<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;

echo "=== Testing PDF Export Data ===\n\n";

// Test the same query that the AdminReportsController uses
$filters = [
    'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'
];

echo "Testing filter: Department = '{$filters['department']}'\n\n";

// Build query exactly like AdminReportsController does
$query = Activity::with([
    'user',
    'adviser',
    'deanNoted',
    'psgReviewed',
    'directorEndorsed',
    'vpApproved',
    'rejectedBy'
]);

if (isset($filters['department']) && $filters['department']) {
    $query->where('organization', $filters['department']);
}

$activities = $query->orderBy('created_at', 'desc')->get();

echo "Query result: Found {$activities->count()} activities\n\n";

if ($activities->count() > 0) {
    echo "Activities found:\n";
    foreach ($activities as $activity) {
        echo "  - ID: {$activity->id}\n";
        echo "    Title: {$activity->title}\n";
        echo "    Organization: {$activity->organization}\n";
        echo "    User: {$activity->user->name}\n";
        echo "    User Department: {$activity->user->department}\n";
        echo "    Status: {$activity->workflow_status}\n";
        echo "    Type: {$activity->type}\n\n";
    }
} else {
    echo "No activities found - this would trigger the 'No activities found' message\n";
}

// Test summary data
$summary = [
    'total_activities' => $activities->count(),
    'pending' => $activities->whereIn('workflow_status', ['draft', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director'])->count(),
    'approved_by_vp' => $activities->where('workflow_status', 'approved_by_vp')->count(),
    'rejected' => $activities->where('workflow_status', 'rejected')->count(),
];

echo "Summary data:\n";
echo "  - Total: {$summary['total_activities']}\n";
echo "  - Pending: {$summary['pending']}\n";
echo "  - Approved: {$summary['approved_by_vp']}\n";
echo "  - Rejected: {$summary['rejected']}\n\n";

// Test with no filters
echo "=== Testing with no department filter ===\n";
$allActivities = Activity::with(['user'])->orderBy('created_at', 'desc')->get();
echo "All activities count: {$allActivities->count()}\n";

foreach ($allActivities as $activity) {
    echo "  - {$activity->title} (Org: {$activity->organization}, User Dept: {$activity->user->department})\n";
}

echo "\nTest completed!\n";