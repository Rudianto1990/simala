<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $heading; ?></h1>
    </div>
//Proses Update
    <?= $this->include('template/statusBar'); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Data Alat</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('form/prosesupdate/' . $data['id']); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Asset</label>
                                    <input type="text" name="nomor_asset" class="form-control" value="<?= old('nomor_asset', $data['nomor_asset']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Alat</label>
                                    <input type="text" name="nama_alat" class="form-control" value="<?= old('nama_alat', $data['nama_alat']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Alat</label>
                                    <input type="text" name="kode_alat" class="form-control" value="<?= old('kode_alat', $data['kode_alat']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Merk</label>
                                    <input type="text" name="merk" class="form-control" value="<?= old('merk', $data['merk']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Model</label>
                                    <input type="text" name="model" class="form-control" value="<?= old('model', $data['model']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kapasitas / Ton</label>
                                    <input type="text" name="kap_swal_ton" class="form-control" value="<?= old('kap_swal_ton', $data['kap_swal_ton']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Span (m)</label>
                                    <input type="text" name="span_m" class="form-control" value="<?= old('span_m', $data['span_m']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Outreach (m)</label>
                                    <input type="text" name="outreach_m" class="form-control" value="<?= old('outreach_m', $data['outreach_m']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Milik" <?= old('status', $data['status']) == 'Milik' ? 'selected' : ''; ?>>Milik</option>
                                        <option value="Sewa" <?= old('status', $data['status']) == 'Sewa' ? 'selected' : ''; ?>>Sewa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <input type="text" name="tahun" class="form-control" value="<?= old('tahun', $data['tahun']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Negara</label>
                                    <input type="text" name="negara" class="form-control" value="<?= old('negara', $data['negara']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <select name="lokasi" class="form-control">
                                        <option value="Dermaga 100" <?= old('lokasi', $data['lokasi']) == 'Dermaga 100' ? 'selected' : ''; ?>>Dermaga 100</option>
                                        <option value="Dermaga 101" <?= old('lokasi', $data['lokasi']) == 'Dermaga 101' ? 'selected' : ''; ?>>Dermaga 101</option>
                                        <option value="Dermaga 102" <?= old('lokasi', $data['lokasi']) == 'Dermaga 102' ? 'selected' : ''; ?>>Dermaga 102</option>
                                        <option value="Dermaga 103" <?= old('lokasi', $data['lokasi']) == 'Dermaga 103' ? 'selected' : ''; ?>>Dermaga 103</option>
                                        <option value="Dermaga 300" <?= old('lokasi', $data['lokasi']) == 'Dermaga 300' ? 'selected' : ''; ?>>Dermaga 300</option>
                                        <option value="Dermaga 301" <?= old('lokasi', $data['lokasi']) == 'Dermaga 301' ? 'selected' : ''; ?>>Dermaga 301</option>
                                         <option value="Dermaga Jl. Tembus DKB" <?= old('lokasi', $data['lokasi']) == 'Dermaga Jl. Tembus DKB' ? 'selected' : ''; ?>>Dermaga Jl. Tembus DKB</option>
                                        <option value="Dermaga 114" <?= old('lokasi', $data['lokasi']) == 'Dermaga 114' ? 'selected' : ''; ?>>Dermaga 114</option>
                                        <option value="Lapangan 009" <?= old('lokasi', $data['lokasi']) == 'Lapangan 009' ? 'selected' : ''; ?>>Lapangan 009</option>
                                         <option value="Lapangan Inggom" <?= old('lokasi', $data['lokasi']) == 'Lapangan Inggom' ? 'selected' : ''; ?>>Lapangan Inggom</option>
                                        <option value="Gudang Pombo" <?= old('lokasi', $data['lokasi']) == 'Gudang Pombo' ? 'selected' : ''; ?>>Gudang Pombo</option>
                                        <option value="Gudang Ambon" <?= old('lokasi', $data['lokasi']) == 'Gudang Ambon' ? 'selected' : ''; ?>>Gudang Ambon</option>
                                        <option value="Galangan PSM" <?= old('lokasi', $data['lokasi']) == 'Galangan PSM' ? 'selected' : ''; ?>>Galangan PSM</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan', $data['keterangan']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Foto Alat</label>
                            <input type="file" name="foto_alat" class="form-control-file">
                            <br>
                            <?php if (!empty($data['foto_alat'])) : ?>
                                <img src="<?= base_url('img/alat/' . $data['foto_alat']); ?>" width="120px" height="120px" class="img-thumbnail">
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" id="Latitude" name="latitude" class="form-control" value="<?= old('latitude', $data['latitude']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" id="Longitude" name="longitude" class="form-control" value="<?= old('longitude', $data['longitude']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Peta Lokasi</h6>
                            </div>
                            <div class="card-body">
                                <div id="mapid" style="height: 400px;"></div>
                            </div>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="checkbox" value="1" checked>
                            <label class="form-check-label">Data sudah benar</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<script>
    var curLocation = [
        <?= json_encode(old('latitude', $data['latitude'] ?? null)); ?>,
        <?= json_encode(old('longitude', $data['longitude'] ?? null)); ?>
    ].map(Number);

    if (!Number.isFinite(curLocation[0]) || !Number.isFinite(curLocation[1]) ||
        (curLocation[0] === 0 && curLocation[1] === 0)) {
        curLocation = [-6.2088, 106.8456];
        document.getElementById('Latitude').value = curLocation[0];
        document.getElementById('Longitude').value = curLocation[1];
    }

    var mymap = L.map('mapid').setView(curLocation, 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);

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

    setTimeout(function () {
        mymap.invalidateSize();
    }, 150);
</script>
<?= $this->endSection(); ?>
