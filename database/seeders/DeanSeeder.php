<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DeanSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $deans = [
            [
                'name' => 'Dr. Maria Santos',
                'email' => 'dean.arts@spup.edu.ph',
                'department' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
                'password' => Hash::make('dean123'),
            ],
            [
                'name' => 'Dr. John Rodriguez',
                'email' => 'dean.business@spup.edu.ph',
                'department' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
                'password' => Hash::make('dean123'),
            ],
            [
                'name' => 'Dr. Sarah Chen',
                'email' => 'dean.it@spup.edu.ph',
                'department' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                'password' => Hash::make('dean123'),
            ],
            [
                'name' => 'Dr. Michael Torres',
                'email' => 'dean.nursing@spup.edu.ph',
                'department' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
                'password' => Hash::make('dean123'),
            ],
            [
                'name' => 'Dr. Patricia Lim',
                'email' => 'dean.medicine@spup.edu.ph',
                'department' => 'SCHOOL OF MEDICINE',
                'password' => Hash::make('dean123'),
            ],
        ];

        foreach ($deans as $deanData) {
            User::updateOrCreate(
                ['email' => $deanData['email']],
                [
                    'name' => $deanData['name'],
                    'email' => $deanData['email'],
                    'department' => $deanData['department'],
                    'role' => 'dean',
                    'is_active' => true,
                    'password' => $deanData['password'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('5 Deans created successfully with their assigned schools!');
        $this->command->info('Login credentials: dean123 for all deans');
    }
}
