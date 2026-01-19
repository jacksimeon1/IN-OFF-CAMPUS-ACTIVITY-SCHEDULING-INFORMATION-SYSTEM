<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Checking Department Name Variations ===\n\n";

// Check all department variations
$allDepartments = User::whereNotNull('department')->distinct()->pluck('department')->sort();

echo "All departments in database:\n";
foreach ($allDepartments as $dept) {
    echo "- '{$dept}'\n";
}

echo "\nLooking for ARTS/TEACHER EDUCATION variations:\n";
foreach ($allDepartments as $dept) {
    if (stripos($dept, 'ARTS') !== false || stripos($dept, 'TEACHER') !== false) {
        echo "- '{$dept}'\n";
        
        $users = User::where('department', $dept)->get();
        foreach ($users as $user) {
            echo "  → {$user->name} ({$user->role})\n";
        }
    }
}

echo "\nLooking for BUSINESS variations:\n";
foreach ($allDepartments as $dept) {
    if (stripos($dept, 'BUSINESS') !== false) {
        echo "- '{$dept}'\n";
        
        $users = User::where('department', $dept)->get();
        foreach ($users as $user) {
            echo "  → {$user->name} ({$user->role})\n";
        }
    }
}

echo "\nCheck completed!\n";