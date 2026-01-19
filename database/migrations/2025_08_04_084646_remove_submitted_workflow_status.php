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
        // Update any existing 'submitted' status to 'draft'
        DB::table('activities')
            ->where('workflow_status', 'submitted')
            ->update(['workflow_status' => 'draft']);

        Schema::table('activities', function (Blueprint $table) {
            // Update workflow_status enum to remove 'submitted'
            $table->enum('workflow_status', [
                'draft', 'noted_by_adviser', 'noted_by_dean',
                'reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp', 'rejected'
            ])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Restore workflow_status enum with 'submitted'
            $table->enum('workflow_status', [
                'draft', 'submitted', 'noted_by_adviser', 'noted_by_dean',
                'reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp', 'rejected'
            ])->default('draft')->change();
        });
    }
};
