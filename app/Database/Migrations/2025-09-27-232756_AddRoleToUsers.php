<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyRoleColumnInUsers extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'role' => [
                'name'       => 'role',
                'type'       => 'ENUM',
                'constraint' => ['admin', 'teacher', 'student'],
                'null'       => false,
                'after'      => 'password', // place role after email
            ],
        ]);
    }

    public function down()
    {
        // Rollback: remove ENUM constraint, make it a simple VARCHAR again
        $this->forge->modifyColumn('users', [
            'role' => [
                'name'       => 'role',
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);
    }
}

