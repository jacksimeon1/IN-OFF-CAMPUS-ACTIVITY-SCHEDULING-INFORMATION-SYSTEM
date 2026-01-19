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
        // Fix inconsistent organization values in activities table
        $organizationMappings = [
            'SITE' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            'SASTE' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
            'SBAHM' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
            'SNAHS' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
            'SOM' => 'SCHOOL OF MEDICINE',
            // Add any other abbreviations that might exist
        ];

        foreach ($organizationMappings as $abbreviation => $fullName) {
            DB::table('activities')
                ->where('organization', $abbreviation)
                ->update(['organization' => $fullName]);
        }

        // Also fix any activities where organization doesn't match the standard school names
        // by using the user's department as the organization
        DB::statement("
            UPDATE activities
            SET organization = (
                SELECT users.department
                FROM users
                WHERE users.id = activities.user_id
            )
            WHERE organization NOT IN (
                'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
                'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
                'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
                'SCHOOL OF MEDICINE'
            )
            AND EXISTS (
                SELECT 1 FROM users
                WHERE users.id = activities.user_id
                AND users.department IN (
                    'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
                    'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
                    'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                    'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
                    'SCHOOL OF MEDICINE'
                )
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes if needed
        $reverseMappings = [
            'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' => 'SITE',
            'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' => 'SASTE',
            'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' => 'SBAHM',
            'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' => 'SNAHS',
            'SCHOOL OF MEDICINE' => 'SOM',
        ];

        foreach ($reverseMappings as $fullName => $abbreviation) {
            DB::table('activities')
                ->where('organization', $fullName)
                ->update(['organization' => $abbreviation]);
        }
    }
};
