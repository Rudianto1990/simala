<?php

namespace App\Controllers;

use App\Models\MonitoringAlatModel;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $filters = $this->reportFilters();
        $alat = $this->MonitoringAlatModel->getFilteredAlat($filters);
        $allAlat = $this->MonitoringAlatModel->getAlat();
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
            'title' => 'Data Alat',
            'appname' => 'SIMALA',
            'heading' => 'Data Alat',
            'data' => $alat,
            'mbc' => $mbc,
            'rtg' => $rtg,
            'ohc' => $ohc,
            'sltl' => $sltl,
            'filters' => $filters,
            'tahunOptions' => $this->uniqueValues($allAlat, 'tahun'),
            'negaraOptions' => $this->uniqueValues($allAlat, 'negara'),
        ];

        return view('v_dataalat', $data);
    }

    public function exportExcel()
    {
        $alat = $this->MonitoringAlatModel->getFilteredAlat($this->reportFilters());
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = ['Nomor Asset', 'Nama Alat', 'Kode Alat', 'Merk', 'Model', 'Kap Swal Ton','Span M','Outreach M', 'Status', 'Tahun', 'Negara', 'Keterangan', 'Lokasi'];
        $sheet->fromArray($headers, null, 'A1');

        $rows = array_map(static function (array $item) {
            return [
                $item['nomor_asset'], 
                $item['nama_alat'], 
                $item['kode_alat'], 
                $item['merk'],
                $item['model'],
                $item['kap_swal_ton'],
                $item['span_m'],
                $item['outreach_m'],
                $item['status'], 
                $item['tahun'], 
                $item['negara'],
                $item['keterangan'] ?: 'Non Elektrifikasi', 
                $item['lokasi'],
            ];
        }, $alat);
        if ($rows !== []) {
            $sheet->fromArray($rows, null, 'A2');
        }
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        foreach (range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'laporan-data-alat-' . date('Ymd-His') . '.xlsx';
        $response = service('response');
        ob_start();
        (new Xlsx($spreadsheet))->save('php://output');
        return $response->download($filename, ob_get_clean());
    }

    public function exportPdf()
    {
        $alat = $this->MonitoringAlatModel->getFilteredAlat($this->reportFilters());
        $html = '<h2>Laporan Data Alat</h2><table border="1" cellpadding="5" cellspacing="0" width="100%">';
        $html .= '<thead><tr><th>Nomor Asset</th><th>Nama Alat</th><th>Status</th><th>Tahun</th><th>Negara</th><th>Keterangan</th><th>Lokasi</th></tr></thead><tbody>';
        foreach ($alat as $item) {
            $html .= '<tr><td>' . esc($item['nomor_asset']) . '</td><td>' . esc($item['nama_alat']) . '</td><td>' . esc($item['status']) . '</td><td>' . esc($item['tahun']) . '</td><td>' . esc($item['negara']) . '</td><td>' . esc($item['keterangan'] ?: 'Non Elektrifikasi') . '</td><td>' . esc($item['lokasi']) . '</td></tr>';
        }
        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return $this->response->download('laporan-data-alat-' . date('Ymd-His') . '.pdf', $dompdf->output());
    }

    private function reportFilters(): array
    {
        return [
            'status' => trim((string) $this->request->getGet('status')),
            'keterangan' => trim((string) $this->request->getGet('keterangan')),
            'tahun' => trim((string) $this->request->getGet('tahun')),
            'negara' => trim((string) $this->request->getGet('negara')),
        ];
    }

    private function uniqueValues(array $alat, string $field): array
    {
        $values = array_filter(array_unique(array_column($alat, $field)));
        sort($values);
        return $values;
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

    public function detail($nomor_asset)
    {
        $alat = $this->MonitoringAlatModel->where('nomor_asset', $nomor_asset)->first();

        if (!$alat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $alat['nomor_asset'] . ' - ' . $alat['nama_alat'],
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
