<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($heading); ?></h1>
        <a href="<?= base_url('cctv/create'); ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah CCTV</a>
    </div>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('pesan')); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Peta Lokasi CCTV</h6></div>
                <div class="card-body p-2"><div id="cctvMap" style="height: 560px;"></div></div>
            </div>
        </div>
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Kamera</h6></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="cctvTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Kamera</th>
                                    <th>Lokasi</th>
                                    <th>IP Address</th>
                                    <th>Tipe</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cameras as $camera) : ?>
                                    <tr>
                                        <td><?= esc($camera['nama_camera']); ?></td>
                                        <td><?= esc($camera['location']); ?></td>
                                        <td><code><?= esc($camera['ip_address']); ?></code></td>
                                        <td><span class="badge badge-info"><?= esc($camera['type_camera']); ?></span></td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-success btn-sm preview-camera" title="Preview CCTV"
                                                data-camera-id="<?= (int) $camera['id']; ?>"
                                                data-camera-name="<?= esc($camera['nama_camera'], 'attr'); ?>"
                                                data-camera-ip="<?= esc($camera['ip_address'], 'attr'); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="<?= base_url('cctv/show/' . $camera['id']); ?>" class="btn btn-info btn-sm" title="Live View"><i class="fas fa-video"></i></a>
                                            <a href="<?= base_url('cctv/edit/' . $camera['id']); ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('cctv/delete/' . $camera['id']); ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Hapus kamera ini?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (!$cameras) : ?><div class="text-muted text-center py-3">Belum ada data CCTV.</div><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="previewModalTitle">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalTitle">Preview CCTV</h5>
                <div class="ml-auto d-flex align-items-center">
                    <button type="button" id="fullscreenBtn" class="btn btn-sm btn-light mr-2" title="Fullscreen"><i class="fas fa-expand"></i></button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="modal-body text-center">
                <div id="countdownText" class="font-weight-bold text-primary mb-2" style="display:none;"></div>
                <div id="loadingSpinner" class="my-3" style="display:none;"><div class="spinner-border text-primary"></div></div>
                <iframe id="modalFrame" allow="autoplay" style="width:100%;height:500px;border:0;display:none;" title="Preview CCTV"></iframe>
                <img id="snapshotImage" alt="Snapshot CCTV" style="width:100%;height:500px;object-fit:contain;display:none;">
                <div id="previewMessage" class="small text-muted mt-2"></div>
                <div class="mt-3">
                    <button id="snapshotBtn" type="button" class="btn btn-warning btn-sm">Snapshot Mode</button>
                    <button id="webrtcBtn" type="button" class="btn btn-primary btn-sm">WebRTC Mode</button>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<script>
    var cctvData = <?= json_encode($cameras, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var cctvMap = L.map('cctvMap').setView([-6.103, 106.883], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(cctvMap);

    cctvData.forEach(function (camera) {
        var latitude = Number(camera.latitude);
        var longitude = Number(camera.longitude);
        if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) return;
        L.marker([latitude, longitude]).addTo(cctvMap).bindPopup(
            '<strong>' + escapeHtml(camera.nama_camera) + '</strong><br>' +
            escapeHtml(camera.location) + '<br><a href="<?= base_url('cctv/show'); ?>/' + camera.id + '">Lihat CCTV</a>'
        );
    });

    var previewModal = $('#previewModal');
    var modalFrame = document.getElementById('modalFrame');
    var snapshotImage = document.getElementById('snapshotImage');
    var previewMessage = document.getElementById('previewMessage');
    var countdownText = document.getElementById('countdownText');
    var activeCamera = null;
    var countdownTimer = null;
    var snapshotRefreshTimer = null;
    var webRtcBaseUrl = <?= json_encode(trim((string) env('CCTV_WEBRTC_BASE_URL', ''), '/')); ?>;
    var webRtcPathTemplate = <?= json_encode(trim((string) env('CCTV_WEBRTC_PATH_TEMPLATE', 'camera-{id}'), '/')); ?>;

    $('.preview-camera').on('click', function () {
        activeCamera = this.dataset;
        document.getElementById('previewModalTitle').textContent = 'Preview CCTV - ' + activeCamera.cameraName;
        previewModal.modal('show');
        showSnapshot();
    });

    function showSnapshot() {
        clearInterval(countdownTimer);
        clearInterval(snapshotRefreshTimer);
        modalFrame.style.display = 'none';
        snapshotImage.style.display = 'block';
        document.getElementById('snapshotBtn').classList.add('active');
        document.getElementById('webrtcBtn').classList.remove('active');
        countdownText.style.display = 'block';
        var seconds = 3;
        countdownText.textContent = 'Menghubungkan snapshot... ' + seconds;
        countdownTimer = setInterval(function () {
            seconds -= 1;
            countdownText.textContent = seconds > 0 ? 'Menghubungkan snapshot... ' + seconds : '';
            if (seconds <= 0) {
                clearInterval(countdownTimer);
                countdownText.style.display = 'none';
            }
        }, 1000);
        var refreshSnapshot = function () {
            if (!activeCamera) {
                return;
            }

            snapshotImage.src = '<?= base_url('cctv/snapshot'); ?>/' + activeCamera.cameraId + '?t=' + Date.now();
        };

        refreshSnapshot();
        snapshotRefreshTimer = setInterval(refreshSnapshot, 2000);
        snapshotImage.onerror = function () {
            previewMessage.textContent = 'Snapshot gagal dimuat. Periksa sesi login dan izin akses kamera.';
        };
        snapshotImage.onload = function () { previewMessage.textContent = 'Live snapshot aktif.'; };
    }

    document.getElementById('snapshotBtn').addEventListener('click', showSnapshot);
    document.getElementById('webrtcBtn').addEventListener('click', function () {
        if (!webRtcBaseUrl || !activeCamera) {
            previewMessage.textContent = 'Gateway WebRTC belum dikonfigurasi.';
            return;
        }
        clearInterval(snapshotRefreshTimer);
        var path = webRtcPathTemplate.replace('{id}', activeCamera.cameraId);
        modalFrame.src = webRtcBaseUrl + '/' + path + '/?autoplay=true';
        modalFrame.style.display = 'block';
        snapshotImage.style.display = 'none';
        countdownText.style.display = 'none';
        document.getElementById('snapshotBtn').classList.remove('active');
        this.classList.add('active');
        previewMessage.textContent = 'WebRTC mode aktif.';
    });

    document.getElementById('fullscreenBtn').addEventListener('click', function () {
        var content = document.querySelector('#previewModal .modal-content');
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else if (content.requestFullscreen) {
            content.requestFullscreen();
        }
    });

    previewModal.on('hidden.bs.modal', function () {
        clearInterval(countdownTimer);
        clearInterval(snapshotRefreshTimer);
        modalFrame.src = '';
        snapshotImage.src = '';
        activeCamera = null;
    });

    function escapeHtml(value) {
        return String(value).replace(/[&<>'"]/g, function (character) {
            return {'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'}[character];
        });
    }
</script>
<?= $this->endSection(); ?>
