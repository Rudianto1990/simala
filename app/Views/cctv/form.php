<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($heading); ?></h1>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Informasi Kamera</h6></div>
                <div class="card-body">
                    <form action="<?= base_url(isset($camera['id']) ? 'cctv/update/' . $camera['id'] : 'cctv/store'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama_camera">Nama Kamera</label>
                                <input id="nama_camera" type="text" name="nama_camera" class="form-control" required maxlength="100" value="<?= old('nama_camera', $camera['nama_camera'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="location">Lokasi</label>
                                <input id="location" type="text" name="location" class="form-control" required maxlength="100" value="<?= old('location', $camera['location'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ip_address">IP Address</label>
                                <input id="ip_address" type="text" name="ip_address" class="form-control" required maxlength="45" value="<?= old('ip_address', $camera['ip_address'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="type_camera">Tipe Kamera</label>
                                <select id="type_camera" name="type_camera" class="form-control" required>
                                    <?php $type = old('type_camera', $camera['type_camera'] ?? ''); ?>
                                    <option value="">Pilih tipe</option>
                                    <?php foreach (['Hikvision', 'Dahua', 'PTZ', 'Dome', 'Lainnya'] as $option) : ?>
                                        <option value="<?= $option; ?>" <?= $type === $option ? 'selected' : ''; ?>><?= $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="rtsp_url">RTSP / Snapshot URL</label>
                            <input id="rtsp_url" type="text" name="rtsp_url" class="form-control" required maxlength="255" placeholder="rtsp://user:password@192.168.1.100:554/stream1 atau http://IP/ISAPI/streaming/channels/101/picture" value="<?= old('rtsp_url', $camera['rtsp_url'] ?? ''); ?>">
                            <small class="form-text text-muted">Gunakan RTSP untuk HLS/WebRTC MediaMTX, atau URL snapshot HTTP untuk Snapshot Mode.</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="latitude">Latitude</label>
                                <input id="latitude" type="number" name="latitude" class="form-control" required step="0.00000001" min="-90" max="90" value="<?= old('latitude', $camera['latitude'] ?? '-6.10300000'); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="longitude">Longitude</label>
                                <input id="longitude" type="number" name="longitude" class="form-control" required step="0.00000001" min="-180" max="180" value="<?= old('longitude', $camera['longitude'] ?? '106.88300000'); ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        <a href="<?= base_url('cctv'); ?>" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Titik Lokasi</h6></div>
                <div class="card-body p-2"><div id="cctvMap" style="height: 480px;"></div></div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<script>
    var latitudeInput = document.getElementById('latitude');
    var longitudeInput = document.getElementById('longitude');
    var cctvMap = L.map('cctvMap').setView([Number(latitudeInput.value), Number(longitudeInput.value)], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(cctvMap);

    var marker = L.marker([Number(latitudeInput.value), Number(longitudeInput.value)], {draggable: true}).addTo(cctvMap);
    function setCoordinates(point) {
        latitudeInput.value = point.lat.toFixed(8);
        longitudeInput.value = point.lng.toFixed(8);
        marker.setLatLng(point);
    }
    marker.on('dragend', function () { setCoordinates(marker.getLatLng()); });
    cctvMap.on('click', function (event) { setCoordinates(event.latlng); });
</script>
<?= $this->endSection(); ?>
