<?php

namespace App\Models;

use CodeIgniter\Model;

class CctvModel extends Model
{
    protected $table         = 'cctv';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'source_id',
        'inventory_code',
        'nama_camera',
        'location',
        'ip_address',
        'type_camera',
        'rtsp_url',
        'latitude',
        'longitude',
        'source_name',
        'sub_division',
        'category',
        'jenis_kategori',
        'merk',
        'serial_number',
        'source_status',
        'reg_date',
        'nvr',
        'nomor_urut',
        'link_img',
        'source_model',
        'source_created_at',
        'source_updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}