<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin2300@example.com',
            'password' => password_hash('pas0123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'is_deleted' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}


