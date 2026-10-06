<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FasilitasOverlaySettings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('fasilitas', [
            'overlay_image_width' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'overlay_image_height' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
            'overlay_south' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'overlay_west' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'overlay_north' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'overlay_east' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('fasilitas', [
            'overlay_image_width',
            'overlay_image_height',
            'overlay_south',
            'overlay_west',
            'overlay_north',
            'overlay_east',
        ]);
    }
}