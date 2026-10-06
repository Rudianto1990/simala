<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityModel extends Model
{
    protected $table            = 'fasilitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama',
        'deskripsi',
        'luas',
        'link_gambar',
        'latitude',
        'longitude',
        'overlay_image_width',
        'overlay_image_height',
        'overlay_south',
        'overlay_west',
        'overlay_north',
        'overlay_east',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
