<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')
    ->handle(Illuminate\Http\Request::capture());

use App\Models\Activity;
use App\Models\User;

echo "=== FIXING TEACHING ACTIVITY DEPARTMENT ===\n\n";

// Find the teaching activity
$teachingActivity = Activity::where('title', 'teaching')->first();

if (!$teachingActivity) {
    echo "Teaching activity not found!\n";
    exit;
}

echo "Found teaching activity (ID: {$teachingActivity->id})\n";
echo "Current user ID: {$teachingActivity->user_id}\n";

if ($teachingActivity->user) {
    echo "Current user department: {$teachingActivity->user->department}\n";
} else {
    echo "No user associated with this activity!\n";
}

// Find a user from SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION
$artsUser = User::where('department', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION')->first();

if (!$artsUser) {
    echo "No user found in ARTS department!\n";
    echo "Available departments:\n";
    $departments = User::whereNotNull('department')->distinct()->pluck('department');
    foreach($departments as $dept) {
        echo "- $dept\n";
    }
    exit;
}

echo "Found ARTS user: {$artsUser->name} (ID: {$artsUser->id})\n";

// Update the activity to be associated with the ARTS user
$teachingActivity->user_id = $artsUser->id;
$teachingActivity->save();

echo "✅ Updated teaching activity to be associated with ARTS department user\n";
echo "=== FIX COMPLETE ===\n";
