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
        Schema::table('activities', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('activity_date');
        });

        // Set end_date to activity_date for existing records
        DB::table('activities')->whereNull('end_date')->update([
            'end_date' => DB::raw('activity_date')
        ]);

        // Make end_date required after setting default values
        Schema::table('activities', function (Blueprint $table) {
            $table->date('end_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });
    }
};
