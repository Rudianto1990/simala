<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Cctv extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_camera' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
            ],
            'type_camera' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'rtsp_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('cctv');
    }

    public function down()
    {
        $this->forge->dropTable('cctv');
    }
}