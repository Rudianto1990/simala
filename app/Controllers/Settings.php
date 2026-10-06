<?php

namespace App\Controllers;

class Settings extends Dashboard
{
    public function index()
    {
        return $this->renderDashboard('v_setting', 'Pengaturan Denah');
    }

    public function save()
    {
        $rules = [
            'facility_id' => 'required|is_natural_no_zero',
            'image_width' => 'required|is_natural_no_zero|max_length[6]',
            'image_height' => 'required|is_natural_no_zero|max_length[6]',
            'south' => 'required|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]',
            'west' => 'required|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]',
            'north' => 'required|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]',
            'east' => 'required|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Nilai penyesuaian denah tidak valid.',
            ]);
        }

        $facilityId = (int) $this->request->getPost('facility_id');
        $south = (float) $this->request->getPost('south');
        $west = (float) $this->request->getPost('west');
        $north = (float) $this->request->getPost('north');
        $east = (float) $this->request->getPost('east');

        if ($north <= $south || $east <= $west) {
            return $this->response->setStatusCode(422)->setJSON([
                'message' => 'Batas utara dan timur harus lebih besar dari batas selatan dan barat.',
            ]);
        }

        if (!$this->FacilityModel->find($facilityId)) {
            return $this->response->setStatusCode(404)->setJSON([
                'message' => 'Fasilitas tidak ditemukan.',
            ]);
        }

        $settings = [
            'overlay_image_width' => (int) $this->request->getPost('image_width'),
            'overlay_image_height' => (int) $this->request->getPost('image_height'),
            'overlay_south' => $south,
            'overlay_west' => $west,
            'overlay_north' => $north,
            'overlay_east' => $east,
        ];

        try {
            if (!$this->FacilityModel->update($facilityId, $settings)) {
                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Pengaturan denah gagal disimpan ke database.',
                ]);
            }

            $savedFacility = $this->FacilityModel->find($facilityId);
        } catch (\Throwable $exception) {
            return $this->response->setStatusCode(500)->setJSON([
                'message' => 'Pengaturan denah gagal disimpan ke database.',
            ]);
        }

        foreach (array_keys($settings) as $field) {
            if (!isset($savedFacility[$field])) {
                return $this->response->setStatusCode(500)->setJSON([
                    'message' => 'Pengaturan denah tidak ditemukan setelah disimpan.',
                ]);
            }
        }

        return $this->response->setJSON([
            'message' => 'Penyesuaian denah berhasil disimpan.',
            'settings' => array_intersect_key($savedFacility, $settings),
        ]);
    }
}