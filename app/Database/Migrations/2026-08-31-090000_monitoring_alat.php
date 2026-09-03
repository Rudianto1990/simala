<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MonitoringAlat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 15,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_asset' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'nama_alat' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'kode_alat' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'merk' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'model' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'kap_swal_ton' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'span_m' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'outreach_m' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'foto_alat' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'ENUM("Milik","Sewa")',
            ],
            'tahun' => [
                'type'       => 'VARCHAR',
                'constraint' => 4,
            ],
            'negara' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 70,
            ],
            'latitude' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'longitude' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tbl_monitoring_alat');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_monitoring_alat');
    }
}
