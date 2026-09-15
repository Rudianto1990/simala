<?php

namespace App\Controllers;

use App\Models\CctvModel;
use App\Models\MonitoringAlatModel;

class Dashboard extends BaseController
{
    protected $MonitoringAlatModel;
    protected $CctvModel;

    public function __construct()
    {
        $this->MonitoringAlatModel = new MonitoringAlatModel();
        $this->CctvModel = new CctvModel();
    }

    public function index()
    {
        $alat = $this->MonitoringAlatModel->getAlat();
        $cctv = $this->CctvModel->orderBy('id', 'DESC')->findAll();

        $mbc = 0;
        $rtg = 0;
        $ohc = 0;
        $sltl = 0;

        foreach ($alat as $item) {
            $nama = strtoupper((string) ($item['nama_alat'] ?? ''));

            if (
                stripos($nama, 'MBC') !== false ||
                stripos($nama, 'MOBILE CRANE') !== false ||
                stripos($nama, 'CONTAINER CRANE') !== false
            ) {
                $mbc++;
            } elseif (stripos($nama, 'GANTRY') !== false || stripos($nama, 'RTG') !== false) {
                $rtg++;
            } elseif (
                stripos($nama, 'OHC') !== false ||
                stripos($nama, 'OVERHEAD CRANE') !== false ||
                stripos($nama, 'REACH STACKER') !== false
            ) {
                $ohc++;
            } elseif (
                stripos($nama, 'LOADER') !== false ||
                stripos($nama, 'SIDE LOADER') !== false ||
                stripos($nama, 'TOP LOADER') !== false ||
                stripos($nama, 'SL') !== false ||
                stripos($nama, 'TL') !== false
            ) {
                $sltl++;
            }
        }

        $data = [
            'title' => 'Dashboard Monitoring Alat',
            'appname' => 'SIMALA',
            'heading' => 'Dashboard',
            'data' => $alat,
            'mbc' => $mbc,
            'rtg' => $rtg,
            'ohc' => $ohc,
            'sltl' => $sltl,
            'cctv' => $cctv,
        ];

        return view('v_dashboard', $data);
    }
}
