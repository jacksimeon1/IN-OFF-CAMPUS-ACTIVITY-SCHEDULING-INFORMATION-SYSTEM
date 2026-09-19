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
                'objective_1' => 'To showcase latest research and developments in computer science.',
                'leaders' => 'CS Department Officers',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(5),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'location' => 'CS Building Auditorium',
                'budget' => 15000.00,
                'organization' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                'expected_participants' => 200,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
            ],
            [
                'title' => 'Business Leadership Workshop',
                'objective_1' => 'To develop leadership skills among business students.',
                'leaders' => 'Business Administration Society',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(10),
                'start_time' => '13:00',
                'end_time' => '16:00',
                'location' => 'Business Building Conference Room',
                'budget' => 8000.00,
                'organization' => 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT',
                'expected_participants' => 50,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
            ],
            [
                'title' => 'Engineering Innovation Fair',
                'objective_1' => 'To promote innovation and creativity in engineering.',
                'leaders' => 'Engineering Society Officers',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(15),
                'start_time' => '08:00',
                'end_time' => '18:00',
                'location' => 'Engineering Building Lobby',
                'budget' => 25000.00,
                'organization' => 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING',
                'expected_participants' => 300,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
            ],
            [
                'title' => 'Cultural Arts Festival',
                'objective_1' => 'To celebrate and promote cultural diversity.',
                'leaders' => 'Cultural Arts Club Officers',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(20),
                'start_time' => '18:00',
                'end_time' => '21:00',
                'location' => 'University Gymnasium',
                'budget' => 20000.00,
                'organization' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
                'expected_participants' => 500,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
            ],
            [
                'title' => 'Sports Tournament',
                'objective_1' => 'To promote physical fitness and sportsmanship.',
                'leaders' => 'Sports Club Officers',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(25),
                'start_time' => '07:00',
                'end_time' => '17:00',
                'location' => 'Sports Complex',
                'budget' => 12000.00,
                'organization' => 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES',
                'expected_participants' => 150,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
            ],
            [
                'title' => 'Community Outreach Program',
                'objective_1' => 'To serve the local community and promote social responsibility.',
                'leaders' => 'Student Government Officers',
                'type' => 'off-campus',
                'activity_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(30),
                'start_time' => '08:00',
                'end_time' => '16:00',
                'location' => 'Barangay San Jose Community Center',
                'budget' => 5000.00,
                'organization' => 'SCHOOL OF MEDICINE',
                'expected_participants' => 75,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
            ],
            [
                'title' => 'Academic Conference',
                'objective_1' => 'To promote academic excellence and research culture.',
                'leaders' => 'Academic Council Officers',
                'type' => 'in-campus',
                'activity_date' => Carbon::now()->addDays(35),
                'end_date' => Carbon::now()->addDays(35),
                'start_time' => '09:00',
                'end_time' => '15:00',
                'location' => 'Main Auditorium',
                'budget' => 18000.00,
                'organization' => 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION',
                'expected_participants' => 250,
                'status' => 'approved',
                'workflow_status' => 'approved_by_vp',
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
