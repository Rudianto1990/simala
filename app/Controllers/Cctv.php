<?php

namespace App\Controllers;

use App\Models\CctvModel;

class Cctv extends BaseController
{
    protected $cctvModel;

    public function __construct()
    {
        $this->cctvModel = new CctvModel();
    }

    public function index()
    {
        return view('cctv/index', [
            'title' => 'CCTV Monitoring',
            'appname' => 'SIMALA',
            'heading' => 'CCTV Monitoring',
            'cameras' => $this->cctvModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('cctv/form', $this->formData('Tambah CCTV'));
    }

    public function store()
    {
        if (!$this->validate($this->rules())) {
            return redirect()->to('/cctv/create')->withInput();
        }

        $this->cctvModel->insert($this->cameraInput());
        $this->syncMediaMtxConfig();
        return redirect()->to('/cctv')->with('pesan', 'Data CCTV berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $camera = $this->cctvModel->find($id);
        if (!$camera) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('cctv/form', $this->formData('Edit CCTV', $camera));
    }

    public function update($id)
    {
        if (!$this->cctvModel->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate($this->rules())) {
            return redirect()->to('/cctv/edit/' . $id)->withInput();
        }

        $this->cctvModel->update($id, $this->cameraInput());
        $this->syncMediaMtxConfig();
        return redirect()->to('/cctv')->with('pesan', 'Data CCTV berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->cctvModel->delete($id);
        $this->syncMediaMtxConfig();
        return redirect()->to('/cctv')->with('pesan', 'Data CCTV berhasil dihapus.');
    }

    public function show($id)
    {
        $camera = $this->cctvModel->find($id);
        if (!$camera) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('cctv/show', [
            'title' => $camera['nama_camera'],
            'appname' => 'SIMALA',
            'heading' => 'CCTV Live View',
            'camera' => $camera,
            'hlsUrl' => $this->hlsUrl($camera['id']),
            'webrtcUrl' => $this->webrtcUrl($camera['id']),
        ]);
    }

    public function snapshot($id)
    {
        $camera = $this->cctvModel->find($id);
        if (!$camera) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $parts = parse_url($camera['rtsp_url']);
        $username = isset($parts['user']) ? urldecode($parts['user']) : '';
        $password = isset($parts['pass']) ? urldecode($parts['pass']) : '';
        $url = isset($parts['scheme']) && in_array(strtolower($parts['scheme']), ['http', 'https'], true)
            ? $camera['rtsp_url']
            : 'http://' . $camera['ip_address'] . '/ISAPI/streaming/channels/101/picture';
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_USERPWD => $username . ':' . $password,
            CURLOPT_HTTPHEADER => ['Accept: image/jpeg,image/*;q=0.9'],
        ]);
        $image = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE) ?: 'image/jpeg';
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($image === false || $status < 200 || $status >= 300) {
            log_message('error', 'CCTV snapshot failed: camera={id}, status={status}, error={error}', [
                'id' => $id,
                'status' => $status,
                'error' => $curlError,
            ]);
            return $this->response->setStatusCode(502)->setBody('Snapshot kamera tidak dapat diakses.');
        }

        return $this->response->setHeader('Content-Type', $contentType)->setBody($image);
    }

    private function hlsUrl(int $id): string
    {
        $baseUrl = trim((string) env('CCTV_HLS_BASE_URL', ''), '/');

        return $baseUrl === '' ? '' : $baseUrl . '/camera-' . $id . '/index.m3u8';
    }

    private function webrtcUrl(int $id): string
    {
        $baseUrl = trim((string) env('CCTV_WEBRTC_BASE_URL', ''), '/');
        $pathTemplate = trim((string) env('CCTV_WEBRTC_PATH_TEMPLATE', 'camera-{id}'), '/');
        $path = str_replace('{id}', (string) $id, $pathTemplate);

        return $baseUrl === '' ? '' : $baseUrl . '/' . $path . '/?autoplay=true';
    }

    private function syncMediaMtxConfig(): void
    {
        $configPath = ROOTPATH . 'streaming/mediamtx.yml';
        $config = "logLevel: info\n\nhls: yes\nhlsAddress: :8888\n\npaths:\n";

        foreach ($this->cctvModel->orderBy('id', 'ASC')->findAll() as $camera) {
            if (stripos((string) $camera['rtsp_url'], 'rtsp://') !== 0) {
                continue;
            }

            $source = str_replace("'", "''", $camera['rtsp_url']);
            $config .= "  camera-{$camera['id']}:\n";
            $config .= "    source: '{$source}'\n";
            $config .= "    rtspTransport: tcp\n\n";
        }

        if (!is_dir(dirname($configPath))) {
            mkdir(dirname($configPath), 0755, true);
        }

        file_put_contents($configPath, $config, LOCK_EX);
    }

    private function formData(string $heading, array $camera = []): array
    {
        return [
            'title' => $heading,
            'appname' => 'SIMALA',
            'heading' => $heading,
            'camera' => $camera,
            'validation' => \Config\Services::validation(),
        ];
    }

    private function cameraInput(): array
    {
        return [
            'nama_camera' => trim((string) $this->request->getPost('nama_camera')),
            'location' => trim((string) $this->request->getPost('location')),
            'ip_address' => trim((string) $this->request->getPost('ip_address')),
            'type_camera' => trim((string) $this->request->getPost('type_camera')),
            'rtsp_url' => trim((string) $this->request->getPost('rtsp_url')),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
    }

    private function rules(): array
    {
        return [
            'nama_camera' => 'required|max_length[100]',
            'location' => 'required|max_length[100]',
            'ip_address' => 'required|max_length[45]',
            'type_camera' => 'required|max_length[50]',
            'rtsp_url' => 'required|max_length[255]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
        ];
    }
}