<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CctvSourceFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('cctv', [
            'source_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
            'inventory_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'source_id',
            ],
            'source_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'sub_division' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'jenis_kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'merk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'serial_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'source_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'reg_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'nvr' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'nomor_urut' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'link_img' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'source_model' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'source_created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'source_updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('source_id');
        $this->forge->addKey('inventory_code');
        $this->forge->processIndexes('cctv');
    }

    public function down()
    {
        $this->forge->dropColumn('cctv', [
            'source_id', 'inventory_code', 'source_name', 'sub_division',
            'category', 'jenis_kategori', 'merk', 'serial_number', 'source_status',
            'reg_date', 'nvr', 'nomor_urut', 'link_img', 'source_model',
            'source_created_at', 'source_updated_at',
        ]);
    }
}