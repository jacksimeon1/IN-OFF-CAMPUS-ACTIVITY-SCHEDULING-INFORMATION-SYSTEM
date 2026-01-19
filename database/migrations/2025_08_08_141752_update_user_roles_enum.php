<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include all roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'adviser', 'dean', 'psg_adviser', 'director', 'vp', 'osa') DEFAULT 'student'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'adviser', 'dean', 'psg_adviser', 'director', 'vp', 'osa') DEFAULT 'student'");
    }
};
