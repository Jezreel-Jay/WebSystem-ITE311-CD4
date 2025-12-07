<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOtpFieldsToUsers extends Migration
{
public function up()
    {
        $fields = [
            'otp_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '6', // 6-digit OTP
                'null'       => true,
            ],
            'otp_expires_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'is_2fa_enabled' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0, // 0: disabled, 1: enabled
                'after'      => 'email', // Adjust the 'after' field as needed
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['otp_code', 'otp_expires_at', 'is_2fa_enabled']);
    }
}
