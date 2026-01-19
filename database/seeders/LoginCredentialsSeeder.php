<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrator
        User::updateOrCreate(
            ['email' => 'admin@spup.edu.ph'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@spup.edu.ph',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Student Officers (exactly 5) mapped to specific departments
        $studentOfficers = [
            1 => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
            2 => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
            3 => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
            4 => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
            5 => 'SCHOOL OF MEDICINE',
        ];

        foreach ($studentOfficers as $i => $department) {
            User::updateOrCreate(
                ['email' => "student{$i}@spup.edu.ph"],
                [
                    'name' => "Student Officer {$i}",
                    'email' => "student{$i}@spup.edu.ph",
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'department' => $department,
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
        }

        // Advisers (adviser1 to adviser5) - Department-Specific
        $advisers = [
            [
                'email' => 'adviser1@spup.edu.ph',
                'name' => 'Dr. Ana Reyes - IT & Engineering Adviser',
                'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'
            ],
            [
                'email' => 'adviser2@spup.edu.ph',
                'name' => 'Prof. Carlos Mendoza - Business Adviser',
                'department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT'
            ],
            [
                'email' => 'adviser3@spup.edu.ph',
                'name' => 'Dr. Lisa Garcia - Arts & Sciences Adviser',
                'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION'
            ],
            [
                'email' => 'adviser4@spup.edu.ph',
                'name' => 'Dr. Patricia Cruz - Nursing Adviser',
                'department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'
            ],
            [
                'email' => 'adviser5@spup.edu.ph',
                'name' => 'Dr. Michael Santos - Medicine Adviser',
                'department' => 'SCHOOL OF MEDICINE'
            ],
        ];

        foreach ($advisers as $adviser) {
            User::updateOrCreate(
                ['email' => $adviser['email']],
                [
                    'name' => $adviser['name'],
                    'email' => $adviser['email'],
                    'password' => Hash::make('adviser123'),
                    'role' => 'adviser',
                    'department' => $adviser['department'],
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
        }

        // Deans (School-Specific)
        $deans = [
            [
                'email' => 'dean.aste@spup.edu.ph',
                'name' => 'Dean - Arts, Sciences and Teacher Education',
                'school' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION'
            ],
            [
                'email' => 'dean.bahm@spup.edu.ph',
                'name' => 'Dean - Business, Accountancy and Hospitality Management',
                'school' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT'
            ],
            [
                'email' => 'dean.ite@spup.edu.ph',
                'name' => 'Dean - Information Technology and Engineering',
                'school' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING'
            ],
            [
                'email' => 'dean.nahs@spup.edu.ph',
                'name' => 'Dean - Nursing and Allied Health Sciences',
                'school' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES'
            ],
            [
                'email' => 'dean.medicine@spup.edu.ph',
                'name' => 'Dean - Medicine',
                'school' => 'SCHOOL OF MEDICINE'
            ],
        ];

        foreach ($deans as $dean) {
            User::updateOrCreate(
                ['email' => $dean['email']],
                [
                    'name' => $dean['name'],
                    'email' => $dean['email'],
                    'password' => Hash::make('dean123'),
                    'role' => 'dean',
                    'school' => $dean['school'],
                    'email_verified_at' => now(),
                ]
            );
        }

        // PSG Council Advisers
        for ($i = 1; $i <= 2; $i++) {
            User::updateOrCreate(
                ['email' => "psg.adviser{$i}@spup.edu.ph"],
                [
                    'name' => "PSG Council Adviser {$i}",
                    'email' => "psg.adviser{$i}@spup.edu.ph",
                    'password' => Hash::make('psg123'),
                    'role' => 'psg_adviser',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Director of Student Officer Affairs
        for ($i = 1; $i <= 2; $i++) {
            $email = $i === 1 ? 'director.sa@spup.edu.ph' : 'director.sa2@spup.edu.ph';
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Director of Student Affairs {$i}",
                    'email' => $email,
                    'password' => Hash::make('director123'),
                    'role' => 'director',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Vice President for Academics
        for ($i = 1; $i <= 2; $i++) {
            $email = $i === 1 ? 'vp.academics@spup.edu.ph' : 'vp.academics2@spup.edu.ph';
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Vice President for Academics {$i}",
                    'email' => $email,
                    'password' => Hash::make('vp123'),
                    'role' => 'vp',
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Login credentials seeded successfully!');
        $this->command->info('Total users created/updated: ' . User::count());
    }
}
