<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $advisers = User::where('role', 'adviser')->get();
        $osaUsers = User::where('role', 'osa')->get();

        if ($students->isEmpty() || $advisers->isEmpty() || $osaUsers->isEmpty()) {
            $this->command->warn('Please run UserSeeder first to create users.');
            return;
        }

        // Create approved activities for the calendar
        $activities = [
            [
                'title' => 'Computer Science Symposium',
                'description' => 'Annual symposium featuring latest trends in computer science and technology.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(5),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'location' => 'CS Building Auditorium',
                'objectives' => 'To showcase latest research and developments in computer science.',
                'budget' => 15000.00,
                'organization' => 'Computer Science Society',
                'expected_participants' => 200,
                'status' => 'approved',
            ],
            [
                'title' => 'Business Leadership Workshop',
                'description' => 'Workshop on leadership skills for business students.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(10),
                'start_time' => '13:00',
                'end_time' => '16:00',
                'location' => 'Business Building Conference Room',
                'objectives' => 'To develop leadership skills among business students.',
                'budget' => 8000.00,
                'organization' => 'Business Administration Society',
                'expected_participants' => 50,
                'status' => 'approved',
            ],
            [
                'title' => 'Engineering Innovation Fair',
                'description' => 'Showcase of innovative engineering projects and solutions.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(15),
                'start_time' => '08:00',
                'end_time' => '18:00',
                'location' => 'Engineering Building Lobby',
                'objectives' => 'To promote innovation and creativity in engineering.',
                'budget' => 25000.00,
                'organization' => 'Engineering Society',
                'expected_participants' => 300,
                'status' => 'approved',
            ],
            [
                'title' => 'Cultural Arts Festival',
                'description' => 'Annual celebration of cultural arts and performances.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(20),
                'start_time' => '18:00',
                'end_time' => '21:00',
                'location' => 'University Gymnasium',
                'objectives' => 'To celebrate and promote cultural diversity.',
                'budget' => 20000.00,
                'organization' => 'Cultural Arts Club',
                'expected_participants' => 500,
                'status' => 'approved',
            ],
            [
                'title' => 'Sports Tournament',
                'description' => 'Inter-department sports competition.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(25),
                'start_time' => '07:00',
                'end_time' => '17:00',
                'location' => 'Sports Complex',
                'objectives' => 'To promote physical fitness and sportsmanship.',
                'budget' => 12000.00,
                'organization' => 'Sports Club',
                'expected_participants' => 150,
                'status' => 'approved',
            ],
            [
                'title' => 'Community Outreach Program',
                'description' => 'Volunteer work in local community.',
                'type' => 'off-campus',
                'activity_date' => Carbon::now()->addDays(30),
                'start_time' => '08:00',
                'end_time' => '16:00',
                'location' => 'Barangay San Jose Community Center',
                'objectives' => 'To serve the local community and promote social responsibility.',
                'budget' => 5000.00,
                'organization' => 'Student Government',
                'expected_participants' => 75,
                'status' => 'approved',
            ],
            [
                'title' => 'Academic Conference',
                'description' => 'Conference on academic excellence and research.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(35),
                'start_time' => '09:00',
                'end_time' => '15:00',
                'location' => 'Main Auditorium',
                'objectives' => 'To promote academic excellence and research culture.',
                'budget' => 18000.00,
                'organization' => 'Academic Council',
                'expected_participants' => 250,
                'status' => 'approved',
            ],
        ];

        foreach ($activities as $activityData) {
            $student = $students->random();
            $adviser = $advisers->random();
            $osa = $osaUsers->random();

            Activity::create(array_merge($activityData, [
                'user_id' => $student->id,
                'adviser_id' => $adviser->id,
                'osa_id' => $osa->id,
                'adviser_reviewed_at' => Carbon::now()->subDays(2),
                'osa_reviewed_at' => Carbon::now()->subDays(1),
                'adviser_comments' => 'Approved by adviser. Good planning and objectives.',
                'osa_comments' => 'Final approval granted. All requirements met.',
            ]));
        }

        // Create some pending activities
        $pendingActivities = [
            [
                'title' => 'Student Orientation Program',
                'description' => 'Orientation for new students.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(40),
                'start_time' => '08:00',
                'end_time' => '12:00',
                'location' => 'Main Auditorium',
                'objectives' => 'To orient new students about university policies and procedures.',
                'budget' => 10000.00,
                'organization' => 'Student Affairs Office',
                'expected_participants' => 400,
                'status' => 'pending',
            ],
            [
                'title' => 'Research Symposium',
                'description' => 'Presentation of student research projects.',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(45),
                'start_time' => '13:00',
                'end_time' => '17:00',
                'location' => 'Research Center',
                'objectives' => 'To showcase student research achievements.',
                'budget' => 15000.00,
                'organization' => 'Research Council',
                'expected_participants' => 100,
                'status' => 'pending',
            ],
        ];

        foreach ($pendingActivities as $activityData) {
            $student = $students->random();

            Activity::create(array_merge($activityData, [
                'user_id' => $student->id,
            ]));
        }

        $this->command->info('Created ' . (count($activities) + count($pendingActivities)) . ' sample activities.');
    }
}
