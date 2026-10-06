<?php

namespace App\Controllers;

use App\Models\FacilityModel;

class Facility extends BaseController
{
    protected $facilityModel;

    public function __construct()
    {
        $this->facilityModel = new FacilityModel();
    }

    public function index()
    {
        return view('v_fasilitas', $this->viewData());
    }

    public function create()
    {
        return view('v_fasilitas', $this->viewData([
            'facility' => [
                'id' => '',
                'nama' => '',
                'deskripsi' => '',
                'luas' => '',
                'link_gambar' => '',
                'latitude' => '',
                'longitude' => '',
            ],
        ]));
    }

    public function store()
    {
        if (!$this->validate($this->rules())) {
            return redirect()->back()->withInput();
        }

        $this->facilityModel->insert($this->facilityInput());
        return redirect()->to('/fasilitas')->with('pesan', 'Data fasilitas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $facility = $this->facilityModel->find($id);
        if (!$facility) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('v_fasilitas', $this->viewData(['facility' => $facility]));
    }

    public function update($id)
    {
        if (!$this->facilityModel->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate($this->rules())) {
            return redirect()->back()->withInput();
        }

        $this->facilityModel->update($id, $this->facilityInput());
        return redirect()->to('/fasilitas')->with('pesan', 'Data fasilitas berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->facilityModel->delete($id);
        return redirect()->to('/fasilitas')->with('pesan', 'Data fasilitas berhasil dihapus.');
    }

    private function viewData(array $data = []): array
    {
        return array_merge([
            'title' => 'Data Fasilitas',
            'appname' => 'SIMALA',
            'heading' => 'Data Fasilitas',
            'facilities' => $this->facilityModel->orderBy('id', 'DESC')->findAll(),
            'facility' => null,
            'validation' => \Config\Services::validation(),
        ], $data);
    }

    private function facilityInput(): array
    {
        return [
            'nama' => trim((string) $this->request->getPost('nama')),
            'deskripsi' => trim((string) $this->request->getPost('deskripsi')),
            'luas' => trim((string) $this->request->getPost('luas')),
            'link_gambar' => trim((string) $this->request->getPost('link_gambar')),
            'latitude' => $this->request->getPost('latitude') === '' ? null : (float) $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude') === '' ? null : (float) $this->request->getPost('longitude'),
        ];
    }

    private function rules(): array
    {
        return [
            'nama' => [
                'rules' => 'required|max_length[100]',
                'errors' => ['required' => 'Nama fasilitas wajib diisi.'],
            ],
            'deskripsi' => 'permit_empty|max_length[65535]',
            'luas' => 'permit_empty|max_length[100]',
            'link_gambar' => [
                'rules' => 'required|regex_match[/\.(png|jpe?g)(\?.*)?$/i]|max_length[255]',
                'errors' => [
                    'required' => 'Link gambar wajib diisi.',
                    'regex_match' => 'Link gambar harus berformat PNG atau JPG/JPEG.',
                ],
            ],
            'latitude' => 'permit_empty|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]',
            'longitude' => 'permit_empty|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]',
        ];
    }
}
