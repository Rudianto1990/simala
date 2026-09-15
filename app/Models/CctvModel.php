<?php

namespace App\Models;

use CodeIgniter\Model;

class CctvModel extends Model
{
    protected $table         = 'cctv';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'nama_camera',
        'location',
        'ip_address',
        'type_camera',
        'rtsp_url',
        'latitude',
        'longitude',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}