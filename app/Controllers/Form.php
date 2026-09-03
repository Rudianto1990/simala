<?php

namespace App\Controllers;

use App\Models\MonitoringAlatModel;

class Form extends BaseController
{
    protected $MonitoringAlatModel;

    public function __construct()
    {
        $this->MonitoringAlatModel = new MonitoringAlatModel();
    }

    public function index()
    {
        return redirect()->to('/form/dataalat');
    }

    public function dataalat()
    {
        $data = [
            'title' => 'Data Alat',
            'appname' => 'SIMALA',
            'heading' => 'Data Alat',
            'data' => $this->MonitoringAlatModel->getAlat(),
        ];

        return view('v_dataalat', $data);
    }

    public function createalat()
    {
        session();

        $data = [
            'title' => 'Create Alat',
            'appname' => 'SIMALA',
            'heading' => 'Create Alat',
            'validation' => \Config\Services::validation(),
        ];

        return view('v_createalat', $data);
    }

    public function simpan()
    {
        if (!$this->validate([
            'nomor_asset' => [
                'rules' => 'required|is_unique[tbl_monitoring_alat.nomor_asset]',
                'errors' => [
                    'required' => 'Nomor Asset wajib diisi',
                    'is_unique' => 'Nomor Asset sudah terdaftar',
                ],
            ],
            'nama_alat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Alat wajib diisi',
                ],
            ],
            'kode_alat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode Alat wajib diisi',
                ],
            ],
            'foto_alat' => [
                'rules' => 'max_size[foto_alat,1024]|is_image[foto_alat]|mime_in[foto_alat,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Gambar maksimal 1024 KB',
                    'is_image' => 'Pastikan file yang diupload adalah gambar',
                    'mime_in' => 'Format gambar hanya JPG, JPEG, atau PNG',
                ],
            ],
            'latitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Latitude wajib diisi',
                ],
            ],
            'longitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Longitude wajib diisi',
                ],
            ],
            'checkbox' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silakan centang data sudah benar',
                ],
            ],
        ])) {
            return redirect()->to('/form/createalat')->withInput();
        }

        $fileFoto = $this->request->getFile('foto_alat');
        $namaFile = 'default.png';

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move('img/alat', $namaFile);
        }

        $slug = url_title($this->request->getVar('nama_alat'), '-', true);

        $this->MonitoringAlatModel->save([
            'nomor_asset' => $this->request->getVar('nomor_asset'),
            'nama_alat' => $this->request->getVar('nama_alat'),
            'kode_alat' => $this->request->getVar('kode_alat'),
            'slug' => $slug,
            'merk' => $this->request->getVar('merk'),
            'model' => $this->request->getVar('model'),
            'kap_swal_ton' => $this->request->getVar('kap_swal_ton'),
            'span_m' => $this->request->getVar('span_m'),
            'outreach_m' => $this->request->getVar('outreach_m'),
            'foto_alat' => $namaFile,
            'status' => $this->request->getVar('status'),
            'tahun' => $this->request->getVar('tahun'),
            'negara' => $this->request->getVar('negara'),
            'lokasi' => $this->request->getVar('lokasi'),
            'keterangan' => $this->request->getVar('keterangan'),
            'latitude' => $this->request->getVar('latitude'),
            'longitude' => $this->request->getVar('longitude'),
        ]);

        session()->setFlashdata('pesan', 'Data alat berhasil disimpan.');
        return redirect()->to('/form/dataalat');
    }

    public function hapus($id)
    {
        $alat = $this->MonitoringAlatModel->find($id);

        if ($alat && !empty($alat['foto_alat']) && $alat['foto_alat'] !== 'default.png') {
            $filePath = FCPATH . 'img/alat/' . $alat['foto_alat'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->MonitoringAlatModel->delete($id);

        session()->setFlashdata('pesan', 'Data alat berhasil dihapus.');
        return redirect()->to('/form/dataalat');
    }

    public function detail($slug)
    {
        $alat = $this->MonitoringAlatModel->getAlat($slug);

        if (!$alat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $alat['nama_alat'],
            'appname' => 'SIMALA',
            'data' => $alat,
        ];

        return view('v_detail_alat', $data);
    }

    public function update($id)
    {
        session();

        $alat = $this->MonitoringAlatModel->find($id);

        if (!$alat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Update Alat : ' . $alat['nama_alat'],
            'appname' => 'SIMALA',
            'heading' => 'Update Alat',
            'data' => $alat,
            'validation' => \Config\Services::validation(),
        ];

        return view('v_updatealat', $data);
    }

    public function prosesupdate($id)
    {
        $alatLama = $this->MonitoringAlatModel->find($id);

        if (!$alatLama) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate([
            'nomor_asset' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nomor Asset wajib diisi',
                ],
            ],
            'nama_alat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Alat wajib diisi',
                ],
            ],
            'kode_alat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kode Alat wajib diisi',
                ],
            ],
            'foto_alat' => [
                'rules' => 'max_size[foto_alat,1024]|is_image[foto_alat]|mime_in[foto_alat,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Gambar maksimal 1024 KB',
                    'is_image' => 'Pastikan file yang diupload adalah gambar',
                    'mime_in' => 'Format gambar hanya JPG, JPEG, atau PNG',
                ],
            ],
            'latitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Latitude wajib diisi',
                ],
            ],
            'longitude' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Longitude wajib diisi',
                ],
            ],
            'checkbox' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silakan centang data sudah benar',
                ],
            ],
        ])) {
            return redirect()->to('/form/update/' . $id)->withInput();
        }

        $namaFile = $alatLama['foto_alat'];
        $fileFoto = $this->request->getFile('foto_alat');

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if (!empty($alatLama['foto_alat']) && $alatLama['foto_alat'] !== 'default.png') {
                $oldFilePath = FCPATH . 'img/alat/' . $alatLama['foto_alat'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move('img/alat', $namaFile);
        }

        $this->MonitoringAlatModel->save([
            'id' => $alatLama['id'],
            'nomor_asset' => $this->request->getPost('nomor_asset'),
            'nama_alat' => $this->request->getPost('nama_alat'),
            'kode_alat' => $this->request->getPost('kode_alat'),
            'slug' => url_title($this->request->getPost('nama_alat'), '-', true),
            'merk' => $this->request->getPost('merk'),
            'model' => $this->request->getPost('model'),
            'kap_swal_ton' => $this->request->getPost('kap_swal_ton'),
            'span_m' => $this->request->getPost('span_m'),
            'outreach_m' => $this->request->getPost('outreach_m'),
            'foto_alat' => $namaFile,
            'status' => $this->request->getPost('status'),
            'tahun' => $this->request->getPost('tahun'),
            'negara' => $this->request->getPost('negara'),
            'lokasi' => $this->request->getPost('lokasi'),
            'keterangan' => $this->request->getPost('keterangan'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ]);

        session()->setFlashdata('pesan', 'Data alat berhasil diupdate.');
        return redirect()->to(base_url('form/dataalat'));
    }
}
