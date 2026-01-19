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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['in-campus', 'off-campus']);
            $table->date('activity_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->text('objectives');
            $table->decimal('budget', 10, 2)->nullable();
            $table->string('organization')->nullable();
            $table->integer('expected_participants')->nullable();
            $table->string('budget_file')->nullable();
            $table->string('permit_file')->nullable();
            $table->string('supporting_documents')->nullable();
            $table->enum('status', ['pending', 'recommended', 'approved', 'rejected'])->default('pending');
            $table->text('adviser_comments')->nullable();
            $table->text('osa_comments')->nullable();
            $table->timestamp('adviser_reviewed_at')->nullable();
            $table->timestamp('osa_reviewed_at')->nullable();
            $table->foreignId('adviser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('osa_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
