<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $heading; ?></h1>
    </div>

    <?= $this->include('template/statusBar'); ?>

    <div class="row align-items-start">
        <div class="col-lg-5">
            <div class="card shadow mb-4 map-panel">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Peta Lokasi</h6>
                </div>
                <div class="card-body p-2">
                    <div id="mapid" style="height: 610px; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Input Alat</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('form/simpan'); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="form-step active" data-step="1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nomor Asset</label>
                                        <input type="text" name="nomor_asset" class="form-control" value="<?= old('nomor_asset'); ?>">
                                        <?php if (isset($validation)) : ?>
                                            <small class="text-danger"><?= $validation->getError('nomor_asset'); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Alat</label>
                                        <select class="form-control" name="nama_alat">
                                            <option value="Container Crane (CC)" selected>Container Crane (CC)</option>
                                            <option value="Rubber Tyred Gantry (RTG)">Rubber Tyred Gantry (RTG)</option>
                                            <option value="Reach Stacker (RS)">Reach Stacker (RS)</option>
                                            <option value="Side Loader / Top Loader (SL/TL)">Side Loader / Top Loader (SL/TL)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kode Alat</label>
                                        <input type="text" name="kode_alat" class="form-control" value="<?= old('kode_alat'); ?>">
                                        <?php if (isset($validation)) : ?>
                                            <small class="text-danger"><?= $validation->getError('kode_alat'); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Merk</label>
                                        <input type="text" name="merk" class="form-control" value="<?= old('merk'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <input type="text" name="model" class="form-control" value="<?= old('model'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kapasitas / Ton</label>
                                        <input type="text" name="kap_swal_ton" class="form-control" value="<?= old('kap_swal_ton'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Span (m)</label>
                                        <input type="text" name="span_m" class="form-control" value="<?= old('span_m'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Outreach (m)</label>
                                        <input type="text" name="outreach_m" class="form-control" value="<?= old('outreach_m'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Milik" <?= old('status') == 'Milik' ? 'selected' : ''; ?>>Milik</option>
                                            <option value="Sewa" <?= old('status') == 'Sewa' ? 'selected' : ''; ?>>Sewa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="text" name="tahun" class="form-control" value="<?= old('tahun'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-outline-primary" id="openMapBtn">
                                    <i class="fa fa-map-marked-alt mr-2"></i> Open Map
                                </button>
                                <button type="button" class="btn btn-primary" id="nextStepBtn">Next</button>
                            </div>
                        </div>

                        <div class="form-step" data-step="2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Negara</label>
                                        <input type="text" name="negara" class="form-control" value="<?= old('negara'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <select class="form-control" name="lokasi">
                                            <option value="Dermaga 100" selected>Dermaga 100</option>
                                            <option value="Dermaga 101">Dermaga 101</option>
                                            <option value="Dermaga 102">Dermaga 102</option>
                                            <option value="Dermaga 103">Dermaga 103</option>
                                            <option value="Dermaga 300">Dermaga 300</option>
                                            <option value="Dermaga 301">Dermaga 301</option>
                                            <option value="Dermaga Jl. Tembus DKB">Dermaga Jl. Tembus DKB</option>
                                            <option value="Dermaga 114">Dermaga 114</option>
                                            <option value="Lapangan 009">Lapangan 009</option>
                                            <option value="Lapangan Inggom">Lapangan Inggom</option>
                                            <option value="Gudang Pombo">Gudang Pombo</option>  
                                            <option value="Gudang Ambon">Gudang Ambon</option>      
                                            <option value="Galangan PSM">Galangan PSM</option>                                                        
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan'); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Foto Alat</label>
                                <input type="file" name="foto_alat" class="form-control-file">
                                <?php if (isset($validation)) : ?>
                                    <small class="text-danger"><?= $validation->getError('foto_alat'); ?></small>
                                <?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" id="Latitude" name="latitude" class="form-control" value="<?= old('latitude'); ?>">
                                        <?php if (isset($validation)) : ?>
                                            <small class="text-danger"><?= $validation->getError('latitude'); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" id="Longitude" name="longitude" class="form-control" value="<?= old('longitude'); ?>">
                                        <?php if (isset($validation)) : ?>
                                            <small class="text-danger"><?= $validation->getError('longitude'); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="checkbox" value="1">
                                <label class="form-check-label">Data sudah benar</label>
                                <?php if (isset($validation)) : ?>
                                    <div><small class="text-danger"><?= $validation->getError('checkbox'); ?></small></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" id="prevStepBtn">Back</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .map-panel {
        position: sticky;
        top: 20px;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .map-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.68);
        z-index: 9999;
        padding: 20px;
    }

    .map-modal.show {
        display: block;
    }

    .map-modal-content {
        position: relative;
        width: 100%;
        height: calc(100vh - 40px);
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }

    .map-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fc;
        padding: 12px 18px;
        border-bottom: 1px solid #e3e6f0;
    }

    .map-modal-body {
        height: calc(100% - 70px);
        width: 100%;
    }

    #fullscreenMap {
        height: 100%;
        width: 100%;
    }
</style>

<div id="mapModal" class="map-modal" aria-hidden="true">
    <div class="map-modal-content">
        <div class="map-modal-header">
            <h5 class="m-0 font-weight-bold text-primary">Peta Lokasi</h5>
            <button type="button" class="btn btn-light btn-sm" id="closeMapModalBtn" aria-label="Close">&times;</button>
        </div>
        <div class="map-modal-body">
            <div id="fullscreenMap"></div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<script>
    var curLocation = [0, 0];
    if (curLocation[0] == 0 && curLocation[1] == 0) {
        curLocation = [-6.2088, 106.8456];
    }

    var mapBaseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    var mymap = L.map('mapid').setView(curLocation, 12);
    mapBaseLayer.addTo(mymap);

    var marker = new L.marker(curLocation, { draggable: true });
    marker.on('dragend', function (event) {
        var position = marker.getLatLng();
        marker.setLatLng(position, { draggable: 'true' }).bindPopup(position).update();
        document.getElementById('Latitude').value = position.lat;
        document.getElementById('Longitude').value = position.lng;
    });
    mymap.addLayer(marker);

    mymap.on('click', function (e) {
        var position = e.latlng;
        marker.setLatLng(position);
        document.getElementById('Latitude').value = position.lat;
        document.getElementById('Longitude').value = position.lng;
    });

    var fullscreenMap = null;
    var fullscreenMarker = null;

    function initFullscreenMap() {
        if (fullscreenMap) {
            return fullscreenMap;
        }

        fullscreenMap = L.map('fullscreenMap').setView(curLocation, 12);
        mapBaseLayer.addTo(fullscreenMap);

        fullscreenMarker = new L.marker(curLocation, { draggable: true });
        fullscreenMarker.on('dragend', function (event) {
            var position = fullscreenMarker.getLatLng();
            fullscreenMarker.setLatLng(position, { draggable: 'true' }).bindPopup(position).update();
            document.getElementById('Latitude').value = position.lat;
            document.getElementById('Longitude').value = position.lng;
            marker.setLatLng(position);
        });
        fullscreenMap.addLayer(fullscreenMarker);

        fullscreenMap.on('click', function (e) {
            var position = e.latlng;
            fullscreenMarker.setLatLng(position);
            document.getElementById('Latitude').value = position.lat;
            document.getElementById('Longitude').value = position.lng;
            marker.setLatLng(position);
        });

        return fullscreenMap;
    }

    var steps = document.querySelectorAll('.form-step');
    var nextBtn = document.getElementById('nextStepBtn');
    var prevBtn = document.getElementById('prevStepBtn');
    var openMapBtn = document.getElementById('openMapBtn');
    var mapModal = document.getElementById('mapModal');
    var closeMapModalBtn = document.getElementById('closeMapModalBtn');

    function showStep(index) {
        steps.forEach(function (step, i) {
            step.classList.toggle('active', i === index);
        });
    }

    function openMapModal() {
        mapModal.classList.add('show');
        setTimeout(function () {
            if (!fullscreenMap) {
                initFullscreenMap();
            }
            fullscreenMap.invalidateSize();
            fullscreenMap.setView(marker.getLatLng(), fullscreenMap.getZoom());
        }, 150);
    }

    function closeMapModal() {
        mapModal.classList.remove('show');
        setTimeout(function () {
            mymap.invalidateSize();
        }, 150);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            showStep(1);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            showStep(0);
        });
    }

    if (openMapBtn) {
        openMapBtn.addEventListener('click', openMapModal);
    }

    if (closeMapModalBtn) {
        closeMapModalBtn.addEventListener('click', closeMapModal);
    }

    mapModal.addEventListener('click', function (e) {
        if (e.target === mapModal) {
            closeMapModal();
        }
    });
</script>
<?= $this->endSection(); ?>
