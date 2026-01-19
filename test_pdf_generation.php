<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;

echo "=== Testing PDF Generation Process ===\n\n";

// Simulate the exact request parameters
$request = (object) [
    'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
    'format' => 'pdf'
];

echo "Simulating request with department filter: {$request->department}\n\n";

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

if ($request->department) {
    $query->where('organization', $request->department);
}

$activities = $query->orderBy('created_at', 'desc')->get();

echo "Activities found: {$activities->count()}\n\n";

// Generate report data exactly like AdminReportsController
$reportData = [
    'title' => 'Activities Report',
    'subtitle' => 'Saint Paul University Philippines - Activity Management System',
    'generated_at' => now(),
    'generated_by' => 'Test User',
    'filters' => ['department' => $request->department],
    'activities' => $activities,
    'summary' => [
        'total_activities' => $activities->count(),
        'pending' => $activities->whereIn('workflow_status', ['draft', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director'])->count(),
        'noted_by_adviser' => $activities->where('workflow_status', 'noted_by_adviser')->count(),
        'noted_by_dean' => $activities->where('workflow_status', 'noted_by_dean')->count(),
        'reviewed_by_psg' => $activities->where('workflow_status', 'reviewed_by_psg')->count(),
        'endorsed_by_director' => $activities->where('workflow_status', 'endorsed_by_director')->count(),
        'approved_by_vp' => $activities->where('workflow_status', 'approved_by_vp')->count(),
        'rejected' => $activities->where('workflow_status', 'rejected')->count(),
    ]
];

echo "Report data summary:\n";
echo "  - Title: {$reportData['title']}\n";
echo "  - Activities count: {$reportData['activities']->count()}\n";
echo "  - Total activities: {$reportData['summary']['total_activities']}\n";
echo "  - Pending: {$reportData['summary']['pending']}\n";
echo "  - Approved: {$reportData['summary']['approved_by_vp']}\n";
echo "  - Rejected: {$reportData['summary']['rejected']}\n";
echo "  - Filter department: {$reportData['filters']['department']}\n\n";

// Test the condition that shows "No activities found"
if ($activities->count() > 0) {
    echo "✅ PDF should show activities table with {$activities->count()} activities\n";
    echo "Activities to be displayed:\n";
    foreach ($activities as $activity) {
        echo "  - {$activity->title} (Status: {$activity->workflow_status})\n";
    }
} else {
    echo "❌ PDF would show 'No activities found' message\n";
}

echo "\nTest completed!\n";