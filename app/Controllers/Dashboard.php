<?php

namespace App\Controllers;

use App\Models\MonitoringAlatModel;

class Dashboard extends BaseController
{
    protected $MonitoringAlatModel;

    public function __construct()
    {
        $this->MonitoringAlatModel = new MonitoringAlatModel();
    }

    public function index()
    {
        $alat = $this->MonitoringAlatModel->getAlat();

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
        ];

        return view('v_dashboard', $data);
    }
}
