<?php

namespace App\Controllers;

use App\Models\CctvModel;
use App\Models\FacilityModel;
use App\Models\MonitoringAlatModel;

class Dashboard extends BaseController
{
    protected $MonitoringAlatModel;
    protected $CctvModel;
    protected $FacilityModel;

    public function __construct()
    {
        $this->MonitoringAlatModel = new MonitoringAlatModel();
        $this->CctvModel = new CctvModel();
        $this->FacilityModel = new FacilityModel();
    }

    public function index()
    {
        return $this->renderDashboard('baso');
    }

    public function kalijapat()
    {
        return $this->renderDashboard();
    }

    public function dermaga_a()
    {
        return $this->renderDashboard();
    }

    public function dermaga_b()
    {
        return $this->renderDashboard();
    }

    public function dermaga_c()
    {
        return $this->renderDashboard();
    }

    private function renderDashboard()
    {
        $alat = array_map([$this, 'prepareAlat'], $this->MonitoringAlatModel->getAlat());
        $cctv = $this->CctvModel->orderBy('id', 'DESC')->findAll();
        $facilities = $this->FacilityModel->orderBy('id', 'ASC')->findAll();
        $selectedFacilityId = (int) $this->request->getGet('facility');

        if (!$selectedFacilityId && $facilities) {
            $selectedFacilityId = (int) $facilities[0]['id'];
        }

        $facilities = array_map([$this, 'prepareFacility'], $facilities);
        $selectedFacility = null;
        foreach ($facilities as $facility) {
            if ((int) $facility['id'] === $selectedFacilityId) {
                $selectedFacility = $facility;
                break;
            }
        }

        if (!$selectedFacility && $facilities) {
            $selectedFacility = $facilities[0];
            $selectedFacilityId = (int) $selectedFacility['id'];
        }

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
            'heading' => 'Dashboard Monitoring Alat',
            'data' => $alat,
            'mbc' => $mbc,
            'rtg' => $rtg,
            'ohc' => $ohc,
            'sltl' => $sltl,
            'cctv' => $cctv,
            'facilities' => $facilities,
            'selectedFacilityId' => $selectedFacilityId,
            'selectedFacility' => $selectedFacility,
        ];

        return view('v_dashboard', $data);
    }

    private function prepareFacility(array $facility): array
    {
        $link = trim((string) $facility['link_gambar']);
        $facility['image_url'] = preg_match('/^https?:\/\//i', $link)
            ? $link
            : base_url(ltrim($link, '/'));

        return $facility;
    }

    private function prepareAlat(array $alat): array
    {
        $nama = strtoupper((string) ($alat['nama_alat'] ?? ''));
        $category = 'mbc';

        if (stripos($nama, 'MBC') !== false || stripos($nama, 'MOBILE CRANE') !== false || stripos($nama, 'CONTAINER CRANE') !== false) {
            $category = 'mbc';
        } elseif (stripos($nama, 'GANTRY') !== false || stripos($nama, 'RTG') !== false) {
            $category = 'rtg';
        } elseif (stripos($nama, 'OHC') !== false || stripos($nama, 'OVERHEAD CRANE') !== false || stripos($nama, 'REACH STACKER') !== false) {
            $category = 'ohc';
        } elseif (stripos($nama, 'LOADER') !== false || stripos($nama, 'SIDE LOADER') !== false || stripos($nama, 'TOP LOADER') !== false || stripos($nama, 'SL') !== false || stripos($nama, 'TL') !== false) {
            $category = 'sltl';
        }

        $markerData = [
            'mbc' => ['color' => '#d9534f', 'icon' => 'fa-truck-moving'],
            'rtg' => ['color' => 'rgb(14, 247, 45)', 'icon' => 'fa-warehouse'],
            'ohc' => ['color' => '#f0c419', 'icon' => 'fa-ship'],
            'sltl' => ['color' => '#f0ad4e', 'icon' => 'fa-boxes'],
        ][$category];

        $alat['category'] = $category;
        $alat['marker_color'] = $markerData['color'];
        $alat['marker_icon'] = $markerData['icon'];
        $alat['foto_url'] = base_url('img/alat/' . ($alat['foto_alat'] ?: 'default.png'));

        $positionY = (float) ($alat['latitude'] ?? 0);
        $positionX = (float) ($alat['longitude'] ?? 0);
        if ($positionY < 0 && $positionX > 90) {
            $alat['map_latitude'] = $positionY;
            $alat['map_longitude'] = $positionX;
        } else {
            $south = -6.122599236486045;
            $west = 106.85564050487093;
            $north = -6.089520085244972;
            $east = 106.92418102745674;
            $alat['map_latitude'] = $north - ($positionY / 161) * ($north - $south);
            $alat['map_longitude'] = $west + ($positionX / 300) * ($east - $west);
        }
        $alat['google_maps_url'] = 'https://www.google.com/maps/search/?api=1&query=' . $alat['map_latitude'] . ',' . $alat['map_longitude'];

        return $alat;
    }
}
