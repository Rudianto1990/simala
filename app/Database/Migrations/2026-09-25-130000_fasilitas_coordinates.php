<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FasilitasCoordinates extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fasilitas', [
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
                'after'      => 'link_gambar',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
                'after'      => 'latitude',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fasilitas', ['latitude', 'longitude']);
    }
}
