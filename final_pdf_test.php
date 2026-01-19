<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

echo "=== Final PDF Export Test ===\n\n";

// Create a mock request object similar to what Laravel would create
$requestData = [
    'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
    'format' => 'pdf'
];

echo "Testing with request data:\n";
foreach ($requestData as $key => $value) {
    echo "  - {$key}: {$value}\n";
}
echo "\n";

// Test the filled() method behavior
$hasDepartment = isset($requestData['department']) && !empty($requestData['department']);
echo "Request has department filter: " . ($hasDepartment ? 'YES' : 'NO') . "\n";

// Build the exact query from AdminReportsController
$query = Activity::with([
    'user',
    'adviser',
    'deanNoted',
    'psgReviewed',
    'directorEndorsed',
    'vpApproved',
    'rejectedBy'
]);

if ($hasDepartment) {
    echo "Applying department filter: organization = '{$requestData['department']}'\n";
    $query->where('organization', $requestData['department']);
}

$activities = $query->orderBy('created_at', 'desc')->get();

echo "Query executed successfully\n";
echo "Activities found: {$activities->count()}\n\n";

// Test the template condition
$showTable = $activities->count() > 0;
echo "Template condition (\$activities->count() > 0): " . ($showTable ? 'TRUE' : 'FALSE') . "\n";

if ($showTable) {
    echo "✅ PDF will show the activities table\n";
    echo "Activities that will be displayed:\n";
    foreach ($activities as $activity) {
        echo "  - ID: {$activity->id} | Title: {$activity->title} | Status: {$activity->workflow_status} | User: {$activity->user->name}\n";
    }
} else {
    echo "❌ PDF will show 'No activities found' message\n";
}

// Test summary calculations
$summary = [
    'total_activities' => $activities->count(),
    'pending' => $activities->whereIn('workflow_status', ['draft', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director'])->count(),
    'approved_by_vp' => $activities->where('workflow_status', 'approved_by_vp')->count(),
    'rejected' => $activities->where('workflow_status', 'rejected')->count(),
];

echo "\nSummary statistics:\n";
foreach ($summary as $key => $value) {
    echo "  - {$key}: {$value}\n";
}

echo "\n=== Test Result ===\n";
if ($activities->count() > 0) {
    echo "✅ SUCCESS: PDF export should now display {$activities->count()} activities correctly\n";
    echo "The 'No activities found' message should NOT appear\n";
} else {
    echo "❌ ISSUE: No activities found - PDF would still show 'No activities found' message\n";
}

echo "\nTest completed!\n";