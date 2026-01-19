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
            // Submission tracking
            $table->timestamp('submitted_at')->nullable()->after('created_at');
            $table->timestamp('osa_submission_date')->nullable()->after('submitted_at');

            // Approval workflow status
            $table->enum('workflow_status', [
                'draft', 'submitted', 'noted_by_adviser', 'noted_by_dean',
                'reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp', 'rejected'
            ])->default('draft')->after('status');

            // Noted by section
            $table->unsignedBigInteger('adviser_noted_by')->nullable()->after('adviser_id');
            $table->timestamp('adviser_noted_at')->nullable()->after('adviser_noted_by');
            $table->text('adviser_notes')->nullable()->after('adviser_noted_at');
            $table->string('adviser_signature')->nullable()->after('adviser_notes');

            $table->unsignedBigInteger('dean_noted_by')->nullable()->after('adviser_signature');
            $table->timestamp('dean_noted_at')->nullable()->after('dean_noted_by');
            $table->text('dean_notes')->nullable()->after('dean_noted_at');
            $table->string('dean_signature')->nullable()->after('dean_notes');

            // Reviewed by section
            $table->unsignedBigInteger('psg_reviewed_by')->nullable()->after('dean_signature');
            $table->timestamp('psg_reviewed_at')->nullable()->after('psg_reviewed_by');
            $table->text('psg_review_comments')->nullable()->after('psg_reviewed_at');
            $table->string('psg_signature')->nullable()->after('psg_review_comments');

            // Endorsed by section
            $table->unsignedBigInteger('director_endorsed_by')->nullable()->after('psg_signature');
            $table->timestamp('director_endorsed_at')->nullable()->after('director_endorsed_by');
            $table->text('director_endorsement_comments')->nullable()->after('director_endorsed_at');
            $table->string('director_signature')->nullable()->after('director_endorsement_comments');

            // Approved by section
            $table->unsignedBigInteger('vp_approved_by')->nullable()->after('director_signature');
            $table->timestamp('vp_approved_at')->nullable()->after('vp_approved_by');
            $table->text('vp_approval_comments')->nullable()->after('vp_approved_at');
            $table->string('vp_signature')->nullable()->after('vp_approval_comments');

            // Rejection tracking
            $table->unsignedBigInteger('rejected_by')->nullable()->after('vp_signature');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_reason')->nullable()->after('rejected_at');

            // Deadline validation
            $table->boolean('meets_deadline_requirement')->default(true)->after('rejection_reason');
            $table->integer('days_before_activity')->nullable()->after('meets_deadline_requirement');

            // Copy distribution tracking
            $table->json('copy_distribution')->nullable()->after('days_before_activity');
            $table->boolean('copies_distributed')->default(false)->after('copy_distribution');

            // Foreign key constraints
            $table->foreign('adviser_noted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('dean_noted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('psg_reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('director_endorsed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vp_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['adviser_noted_by']);
            $table->dropForeign(['dean_noted_by']);
            $table->dropForeign(['psg_reviewed_by']);
            $table->dropForeign(['director_endorsed_by']);
            $table->dropForeign(['vp_approved_by']);
            $table->dropForeign(['rejected_by']);

            // Drop columns
            $table->dropColumn([
                'submitted_at', 'osa_submission_date', 'workflow_status',
                'adviser_noted_by', 'adviser_noted_at', 'adviser_notes', 'adviser_signature',
                'dean_noted_by', 'dean_noted_at', 'dean_notes', 'dean_signature',
                'psg_reviewed_by', 'psg_reviewed_at', 'psg_review_comments', 'psg_signature',
                'director_endorsed_by', 'director_endorsed_at', 'director_endorsement_comments', 'director_signature',
                'vp_approved_by', 'vp_approved_at', 'vp_approval_comments', 'vp_signature',
                'rejected_by', 'rejected_at', 'rejection_reason',
                'meets_deadline_requirement', 'days_before_activity',
                'copy_distribution', 'copies_distributed'
            ]);
        });
    }
};
