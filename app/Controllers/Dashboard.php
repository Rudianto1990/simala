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

        $cc = 0;
        $rtg = 0;
        $rs = 0;
        $sltl = 0;

        foreach ($alat as $item) {
            $nama = strtoupper((string) ($item['nama_alat'] ?? ''));

            if (stripos($nama, 'CRANE') !== false) {
                $cc++;
            } elseif (stripos($nama, 'GANTRY') !== false || stripos($nama, 'RTG') !== false) {
                $rtg++;
            } elseif (stripos($nama, 'STACKER') !== false || stripos($nama, 'REACH STACKER') !== false || stripos($nama, 'RS') !== false) {
                $rs++;
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
            'cc' => $cc,
            'rtg' => $rtg,
            'rs' => $rs,
            'sltl' => $sltl,
        ];

        return view('v_dashboard', $data);
    }
}
