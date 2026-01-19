<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all user passwords to simple, role-based passwords
        $passwordUpdates = [
            // Admin users
            ['email' => 'admin@spup.edu.ph', 'password' => Hash::make('admin123')],

            // Student users
            ['email' => 'student1@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student2@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student3@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student4@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student5@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student6@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student7@spup.edu.ph', 'password' => Hash::make('student123')],
            ['email' => 'student8@spup.edu.ph', 'password' => Hash::make('student123')],

            // Adviser users
            ['email' => 'adviser1@spup.edu.ph', 'password' => Hash::make('adviser123')],
            ['email' => 'adviser2@spup.edu.ph', 'password' => Hash::make('adviser123')],
            ['email' => 'adviser3@spup.edu.ph', 'password' => Hash::make('adviser123')],
            ['email' => 'adviser4@spup.edu.ph', 'password' => Hash::make('adviser123')],
            ['email' => 'adviser5@spup.edu.ph', 'password' => Hash::make('adviser123')],

            // Dean users
            ['email' => 'dean.aste@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.bahm@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.ite@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.nahs@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.medicine@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.arts@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.business@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.it@spup.edu.ph', 'password' => Hash::make('dean123')],
            ['email' => 'dean.nursing@spup.edu.ph', 'password' => Hash::make('dean123')],

            // PSG Adviser users
            ['email' => 'psg.adviser1@spup.edu.ph', 'password' => Hash::make('psg123')],
            ['email' => 'psg.adviser2@spup.edu.ph', 'password' => Hash::make('psg123')],

            // Director users
            ['email' => 'director.sa@spup.edu.ph', 'password' => Hash::make('director123')],
            ['email' => 'director.sa2@spup.edu.ph', 'password' => Hash::make('director123')],

            // VP users
            ['email' => 'vp.academics@spup.edu.ph', 'password' => Hash::make('vp123')],
            ['email' => 'vp.academics2@spup.edu.ph', 'password' => Hash::make('vp123')],
        ];

        foreach ($passwordUpdates as $update) {
            DB::table('users')
                ->where('email', $update['email'])
                ->update(['password' => $update['password']]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all passwords back to 'password'
        DB::table('users')->update(['password' => Hash::make('password')]);
    }
};
