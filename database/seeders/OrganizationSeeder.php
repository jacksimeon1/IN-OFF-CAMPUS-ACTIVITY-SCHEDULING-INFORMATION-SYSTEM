<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advisers = User::where('role', 'adviser')->get();

        Organization::create([
            'name' => 'Computer Science Society',
            'description' => 'Organization for Computer Science students to promote academic excellence and professional development.',
            'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING')->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Business Club',
            'description' => 'Student organization focused on business development and entrepreneurship.',
            'department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT')->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Engineering Society',
            'description' => 'Professional organization for engineering students.',
            'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING')->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Student Government',
            'description' => 'Official student government organization representing all students.',
            'department' => 'Office of Student Affairs',
            'adviser_id' => $advisers->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Cultural Arts Club',
            'description' => 'Organization promoting cultural arts and performances.',
            'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION')->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Sports Club',
            'description' => 'Athletic organization for various sports activities.',
            'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION')->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Nursing Student Association',
            'description' => 'Professional organization for nursing students.',
            'department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES')->first()?->id,
            'is_active' => true,
        ]);

        Organization::create([
            'name' => 'Medical Student Society',
            'description' => 'Organization for medical students promoting academic excellence.',
            'department' => 'SCHOOL OF MEDICINE',
            'adviser_id' => $advisers->where('department', 'SCHOOL OF MEDICINE')->first()?->id,
            'is_active' => true,
        ]);
    }
}
