<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the Online Student Portal',
                'content' => 'We are excited to launch our new student portal for better communication and learning.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Midterm Exam Schedule Released',
                'content' => 'The midterm exam schedule is now available. Please check your dashboard for details.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
        ];

        // Insert both rows
        $this->db->table('announcements')->insertBatch($data);
    }
}
