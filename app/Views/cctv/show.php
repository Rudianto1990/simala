<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($heading); ?></h1>
        <div>
            <a href="<?= base_url('cctv/edit/' . $camera['id']); ?>" class="btn btn-warning"><i class="fas fa-edit mr-1"></i> Edit</a>
            <a href="<?= base_url('cctv'); ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><?= esc($camera['nama_camera']); ?></h6>
                    <span class="badge badge-success"><i class="fas fa-circle mr-1"></i> RTSP Source</span>
                </div>
                <div class="card-body">
                    <?php if ($hlsUrl || $webrtcUrl) : ?>
                        <div class="btn-group mb-3" role="group" aria-label="Mode live stream">
                            <?php if ($hlsUrl) : ?><button type="button" id="hlsModeBtn" class="btn btn-primary btn-sm">HLS Mode</button><?php endif; ?>
                            <?php if ($webrtcUrl) : ?><button type="button" id="webrtcModeBtn" class="btn <?= $hlsUrl ? 'btn-outline-primary' : 'btn-primary'; ?> btn-sm">WebRTC Mode</button><?php endif; ?>
                        </div>
                        <?php if ($hlsUrl) : ?>
                        <video id="cctvVideo" class="w-100 bg-dark rounded mb-3" style="display: block;" controls autoplay muted playsinline></video>
                        <?php endif; ?>
                        <?php if ($webrtcUrl) : ?>
                        <iframe id="webrtcFrame" class="w-100 bg-dark rounded mb-3" style="height: 420px; border: 0; display: <?= $hlsUrl ? 'none' : 'block'; ?>;" allow="autoplay; fullscreen" allowfullscreen title="WebRTC CCTV"></iframe>
                        <?php endif; ?>
                        <div id="streamMessage" class="small text-muted">Menghubungkan ke live stream...</div>
                    <?php else : ?>
                        <div class="bg-dark text-white rounded p-4 mb-3" style="min-height: 260px;">
                            <div class="d-flex align-items-center justify-content-center h-100 text-center" style="min-height: 190px;">
                                <div>
                                    <i class="fas fa-video fa-3x mb-3"></i>
                                    <h5>Gateway HLS belum dikonfigurasi</h5>
                                    <p class="mb-0 text-light">Isi <code>CCTV_HLS_BASE_URL</code> setelah MediaMTX aktif.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="small text-muted">Sumber RTSP tersimpan aman di server dan tidak ditampilkan di browser.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Kamera</h6></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Lokasi</dt><dd class="col-sm-7"><?= esc($camera['location']); ?></dd>
                        <dt class="col-sm-5">IP Address</dt><dd class="col-sm-7"><code><?= esc($camera['ip_address']); ?></code></dd>
                        <dt class="col-sm-5">Tipe</dt><dd class="col-sm-7"><?= esc($camera['type_camera']); ?></dd>
                        <dt class="col-sm-5">Latitude</dt><dd class="col-sm-7"><?= esc($camera['latitude']); ?></dd>
                        <dt class="col-sm-5">Longitude</dt><dd class="col-sm-7"><?= esc($camera['longitude']); ?></dd>
                    </dl>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Peta Lokasi</h6></div>
                <div class="card-body p-2"><div id="cctvMap" style="height: 270px;"></div></div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<?php if ($hlsUrl) : ?><script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.17/dist/hls.min.js"></script><?php endif; ?>
<script>
    var latitude = <?= json_encode((float) $camera['latitude']); ?>;
    var longitude = <?= json_encode((float) $camera['longitude']); ?>;
    var hasCoordinates = Number.isFinite(latitude) && Number.isFinite(longitude) && !(latitude === 0 && longitude === 0);
    var cctvMap = L.map('cctvMap').setView(hasCoordinates ? [latitude, longitude] : [-6.103, 106.883], hasCoordinates ? 16 : 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(cctvMap);
    if (hasCoordinates) {
        L.marker([latitude, longitude]).addTo(cctvMap).bindPopup('<?= esc($camera['nama_camera'], 'js'); ?>').openPopup();
    }

    <?php if ($hlsUrl) : ?>
    var video = document.getElementById('cctvVideo');
    var hlsUrl = <?= json_encode($hlsUrl); ?>;
    if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = hlsUrl;
    } else if (Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource(hlsUrl);
        hls.attachMedia(video);
        hls.on(Hls.Events.ERROR, function (event, data) {
            if (data.fatal) document.getElementById('streamMessage').textContent = 'Stream HLS tidak dapat diakses.';
        });
    } else {
        document.getElementById('streamMessage').textContent = 'Browser ini tidak mendukung HLS.';
    }
    <?php endif; ?>

    <?php if ($webrtcUrl) : ?>
    var webrtcFrame = document.getElementById('webrtcFrame');
    var webrtcUrl = <?= json_encode($webrtcUrl); ?>;
    document.getElementById('webrtcModeBtn').addEventListener('click', function () {
        <?php if ($hlsUrl) : ?>document.getElementById('cctvVideo').style.display = 'none';<?php endif; ?>
        webrtcFrame.style.display = 'block';
        webrtcFrame.src = webrtcUrl;
        this.classList.replace('btn-outline-primary', 'btn-primary');
        <?php if ($hlsUrl) : ?>document.getElementById('hlsModeBtn').classList.replace('btn-primary', 'btn-outline-primary');<?php endif; ?>
        document.getElementById('streamMessage').textContent = 'WebRTC mode aktif.';
    });
    <?php endif; ?>

    <?php if ($hlsUrl) : ?>
    document.getElementById('hlsModeBtn').addEventListener('click', function () {
        document.getElementById('cctvVideo').style.display = 'block';
        <?php if ($webrtcUrl) : ?>webrtcFrame.style.display = 'none';<?php endif; ?>
        this.classList.replace('btn-outline-primary', 'btn-primary');
        <?php if ($webrtcUrl) : ?>document.getElementById('webrtcModeBtn').classList.replace('btn-primary', 'btn-outline-primary');<?php endif; ?>
        document.getElementById('streamMessage').textContent = 'HLS mode aktif.';
    });
    <?php endif; ?>

</script>
<?= $this->endSection(); ?>
