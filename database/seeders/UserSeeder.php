<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'IT Department',
            'is_active' => true,
        ]);

        // Removed generic test student officer to enforce exactly 5 officers by department

        User::create([
            'name' => 'Test Adviser',
            'email' => 'adviser@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'adviser',
            'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            'is_active' => true,
        ]);

        // Create Adviser Users
        User::create([
            'name' => 'Dr. Ana Reyes',
            'email' => 'adviser1@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'adviser',
            'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Prof. Carlos Mendoza',
            'email' => 'adviser2@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'adviser',
            'department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Lisa Garcia',
            'email' => 'adviser3@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'adviser',
            'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Patricia Cruz',
            'email' => 'adviser4@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'adviser',
            'department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Michael Santos',
            'email' => 'adviser5@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'adviser',
            'department' => 'SCHOOL OF MEDICINE',
            'is_active' => true,
        ]);

        // Create Dean Users
        User::create([
            'name' => 'Dr. Roberto Fernandez',
            'email' => 'dean.aste@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'dean',
            'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Carmen Villanueva',
            'email' => 'dean.bahm@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'dean',
            'department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Engr. Miguel Torres',
            'email' => 'dean.ite@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'dean',
            'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Maria Santos',
            'email' => 'dean.nahs@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'dean',
            'department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Jose Rizal',
            'email' => 'dean.medicine@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'dean',
            'department' => 'SCHOOL OF MEDICINE',
            'is_active' => true,
        ]);

        // Create PSG Council Adviser Users
        User::create([
            'name' => 'Prof. Sarah Gonzales',
            'email' => 'psg.adviser1@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'psg_adviser',
            'department' => 'Student Affairs',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Mr. David Ramos',
            'email' => 'psg.adviser2@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'psg_adviser',
            'department' => 'Student Affairs',
            'is_active' => true,
        ]);

        // Create Director of Student Affairs Users
        User::create([
            'name' => 'Dr. Patricia Cruz',
            'email' => 'director.sa@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'director',
            'department' => 'Student Affairs and Academic Support Services',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Antonio Morales',
            'email' => 'director.sa2@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'director',
            'department' => 'Student Affairs and Academic Support Services',
            'is_active' => true,
        ]);

        // Create Vice President for Academics Users
        User::create([
            'name' => 'Dr. Elizabeth Santos',
            'email' => 'vp.academics@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'vp',
            'department' => 'Office of the Vice President for Academics',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Dr. Francisco Aquino',
            'email' => 'vp.academics2@spup.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'vp',
            'department' => 'Office of the Vice President for Academics',
            'is_active' => true,
        ]);

        // Create Student Officers (exactly five) each assigned to one department
        $studentOfficers = [
            [
                'name' => 'Student Officer 1',
                'email' => 'student1@spup.edu.ph',
                'student_id' => '2021-00001',
                'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
                'course' => 'Bachelor of Elementary Education',
                'year_level' => '2nd Year',
            ],
            [
                'name' => 'Student Officer 2',
                'email' => 'student2@spup.edu.ph',
                'student_id' => '2021-00002',
                'department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
                'course' => 'Bachelor of Science in Business Administration',
                'year_level' => '2nd Year',
            ],
            [
                'name' => 'Student Officer 3',
                'email' => 'student3@spup.edu.ph',
                'student_id' => '2021-00003',
                'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                'course' => 'Bachelor of Science in Computer Science',
                'year_level' => '3rd Year',
            ],
            [
                'name' => 'Student Officer 4',
                'email' => 'student4@spup.edu.ph',
                'student_id' => '2021-00004',
                'department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
                'course' => 'Bachelor of Science in Nursing',
                'year_level' => '3rd Year',
            ],
            [
                'name' => 'Student Officer 5',
                'email' => 'student5@spup.edu.ph',
                'student_id' => '2021-00005',
                'department' => 'SCHOOL OF MEDICINE',
                'course' => 'Doctor of Medicine',
                'year_level' => '1st Year',
            ],
        ];

        foreach ($studentOfficers as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'student_id' => $student['student_id'],
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'department' => $student['department'],
                    'course' => $student['course'],
                    'year_level' => $student['year_level'],
                    'is_active' => true,
                ]
            );
        }
    }
}
