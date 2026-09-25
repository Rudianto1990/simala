<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Fasilitas extends Migration
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
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
            ],
            'luas' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'link_gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('fasilitas');

        $this->db->table('fasilitas')->insertBatch([
            [
                'nama'        => 'BASO',
                'deskripsi'   => 'Layout fasilitas area BASO.',
                'luas'        => '-',
                'link_gambar' => 'img/denah/denah.png',
            ],
            [
                'nama'        => 'Dermaga Kalijapat',
                'deskripsi'   => 'Layout fasilitas area Dermaga Kalijapat.',
                'luas'        => '-',
                'link_gambar' => 'img/denah/kalijapat.png',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('fasilitas');
    }
}
