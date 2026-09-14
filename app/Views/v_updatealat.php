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
                                    <input type="text" name="lokasi" class="form-control" value="<?= old('lokasi', $data['lokasi']); ?>">
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
                                    <label>Koordinat Y (latitude)</label>
                                    <input type="text" id="Latitude" name="latitude" class="form-control" value="<?= old('latitude', $data['latitude']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Koordinat X (longitude)</label>
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
    var denahUrl = '<?= base_url('img/denah/denah.png'); ?>';
    var mapBounds = [[0, 0], [1000, 1600]];
    var mymap = L.map('mapid', { crs: L.CRS.Simple, minZoom: -5, maxZoom: 4 });
    var denahOverlay = L.imageOverlay(denahUrl, mapBounds).addTo(mymap);
    mymap.fitBounds(mapBounds);

    denahOverlay.once('load', function () {
        var image = denahOverlay.getElement();
        if (image && image.naturalWidth && image.naturalHeight) {
            mapBounds = [[0, 0], [image.naturalHeight, image.naturalWidth]];
            denahOverlay.setBounds(mapBounds);
            mymap.fitBounds(mapBounds);
        }
    });

    var marker = new L.marker([
        Number(<?= json_encode(old('latitude', $data['latitude'] ?? 0)); ?>) || 0,
        Number(<?= json_encode(old('longitude', $data['longitude'] ?? 0)); ?>) || 0
    ], { draggable: true });
    marker.on('dragend', function (event) {
        var position = marker.getLatLng();
        document.getElementById('Latitude').value = Math.round(position.lat);
        document.getElementById('Longitude').value = Math.round(position.lng);
    });

    mymap.addLayer(marker);

    mymap.on('click', function (e) {
        var position = e.latlng;
        marker.setLatLng(position);
        document.getElementById('Latitude').value = Math.round(position.lat);
        document.getElementById('Longitude').value = Math.round(position.lng);
    });
</script>
<?= $this->endSection(); ?>
