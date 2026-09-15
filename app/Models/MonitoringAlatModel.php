<?php

namespace App\Models;

use CodeIgniter\Model;

class MonitoringAlatModel extends Model
{
    protected $table            = 'tbl_monitoring_alat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_asset',
        'nama_alat',
        'kode_alat',
        'slug',
        'merk',
        'model',
        'kap_swal_ton',
        'span_m',
        'outreach_m',
        'foto_alat',
        'status',
        'tahun',
        'negara',
        'lokasi',
        'keterangan',
        'latitude',
        'longitude',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAlat($slug = false)
    {
        if ($slug == false) {
            return $this->orderBy('id', 'DESC')->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }

    public function getFilteredAlat(array $filters = [])
    {
        $builder = $this->builder();

        foreach (['status', 'tahun', 'negara'] as $field) {
            if (!empty($filters[$field])) {
                $builder->where($field, $filters[$field]);
            }
        }

        if (!empty($filters['keterangan'])) {
            if ($filters['keterangan'] === 'Elektrifikasi') {
                $builder->where('keterangan', 'Elektrifikasi');
            } else {
                $builder->groupStart()
                    ->where('keterangan', '')
                    ->orWhere('keterangan IS NULL', null, false)
                    ->orWhere('keterangan !=', 'Elektrifikasi')
                    ->groupEnd();
            }
        }

        return $builder->orderBy('id', 'DESC')->get()->getResultArray();
    }

    public function countByStatus($status = '')
    {
        if ($status == '') {
            return $this->countAllResults();
        }

        return $this->where(['status' => $status])->countAllResults();
    }

    public function searchAlat($keyword)
    {
        return $this->groupStart()
            ->like('nama_alat', $keyword)
            ->orLike('nomor_asset', $keyword)
            ->orLike('kode_alat', $keyword)
            ->orLike('lokasi', $keyword)
            ->groupEnd();
    }
}
