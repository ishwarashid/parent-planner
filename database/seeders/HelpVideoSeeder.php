<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HelpVideo;

class HelpVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            ['title' => 'Getting Started with Parent Planner', 'description' => 'Learn how to set up your account and add your first child.', 'duration' => '5:23', 'date' => '2023-05-15'],
            ['title' => 'Adding and Managing Children', 'description' => 'Step-by-step guide on adding children and managing their profiles.', 'duration' => '7:42', 'date' => '2023-06-10'],
            ['title' => 'Setting Up Visitation Schedules', 'description' => 'How to create and manage visitation schedules for your children.', 'duration' => '8:15', 'date' => '2023-07-22'],
            ['title' => 'Expense Tracking Tutorial', 'description' => 'Track and manage shared expenses between co-parents.', 'duration' => '6:30', 'date' => '2023-08-05'],
            ['title' => 'Using the Calendar Feature', 'description' => 'Navigate and use the calendar to view schedules and events.', 'duration' => '4:50', 'date' => '2023-09-12'],
            ['title' => 'Document Storage Guide', 'description' => 'Upload and securely store important documents for your children.', 'duration' => '5:45', 'date' => '2023-10-18'],
            ['title' => 'Generating Reports', 'description' => 'Create and download various reports for your records.', 'duration' => '6:20', 'date' => '2023-11-03'],
            ['title' => 'Sending Messages to Co-Parents', 'description' => 'How to use the messaging feature for effective communication.', 'duration' => '4:35', 'date' => '2023-12-01'],
            ['title' => 'Setting Up Billing and Subscriptions', 'description' => 'Manage your subscription and billing information.', 'duration' => '7:10', 'date' => '2024-01-15'],
            ['title' => 'Privacy Settings', 'description' => 'Configure privacy settings for your account and children profiles.', 'duration' => '3:55', 'date' => '2024-02-20'],
            ['title' => 'Mobile App Setup', 'description' => 'Download and set up the Parent Planner mobile app.', 'duration' => '6:40', 'date' => '2024-03-10'],
            ['title' => 'Professional Services Integration', 'description' => 'Connect with professionals through our platform.', 'duration' => '5:25', 'date' => '2024-04-05'],
            ['title' => 'Inviting Co-Parents', 'description' => 'How to invite and connect with your co-parent.', 'duration' => '4:15', 'date' => '2024-05-12'],
            ['title' => 'Managing Notifications', 'description' => 'Configure notifications to stay informed about updates.', 'duration' => '3:40', 'date' => '2024-06-22'],
            ['title' => 'Troubleshooting Common Issues', 'description' => 'Solutions to common problems users may encounter.', 'duration' => '8:05', 'date' => '2024-07-18'],
        ];

        foreach ($videos as $index => $video) {
            HelpVideo::create([
                'title' => $video['title'],
                'description' => $video['description'],
                'duration' => $video['duration'],
                'date' => $video['date'],
                'is_active' => true,
                'order' => $index + 1
            ]);
        }
    }
}
