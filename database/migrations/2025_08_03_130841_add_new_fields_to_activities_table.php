<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->text('objective_1')->nullable()->after('objectives');
            $table->text('objective_2')->nullable()->after('objective_1');
            $table->text('objective_3')->nullable()->after('objective_2');
            $table->text('leaders')->nullable()->after('objective_3');
            $table->text('speakers')->nullable()->after('leaders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['objective_1', 'objective_2', 'objective_3', 'leaders', 'speakers']);
        });
    }
};
