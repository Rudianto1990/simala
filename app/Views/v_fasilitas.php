<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($heading); ?></h1>
        <?php if (!$facility) : ?>
            <a href="<?= base_url('fasilitas/create'); ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Fasilitas</a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('pesan')); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <?php if ($facility) : ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary"><?= $facility['id'] ? 'Edit Fasilitas' : 'Tambah Fasilitas'; ?></h6></div>
            <div class="card-body">
                <form action="<?= base_url($facility['id'] ? 'fasilitas/update/' . $facility['id'] : 'fasilitas/store'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama">Nama</label>
                            <input type="text" id="nama" name="nama" class="form-control" value="<?= esc(old('nama', $facility['nama'])); ?>" required>
                            <?php if ($validation->hasError('nama')) : ?><small class="text-danger"><?= esc($validation->getError('nama')); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="luas">Luas</label>
                            <input type="text" id="luas" name="luas" class="form-control" value="<?= esc(old('luas', $facility['luas'])); ?>" placeholder="Contoh: 12.500 m2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= esc(old('deskripsi', $facility['deskripsi'])); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="link_gambar">Link Gambar (PNG/JPG)</label>
                        <input type="text" id="link_gambar" name="link_gambar" class="form-control" value="<?= esc(old('link_gambar', $facility['link_gambar'])); ?>" placeholder="img/denah/contoh.png atau https://.../gambar.jpg" required>
                        <?php if ($validation->hasError('link_gambar')) : ?><small class="text-danger"><?= esc($validation->getError('link_gambar')); ?></small><?php endif; ?>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="latitude">Latitude</label>
                            <input type="number" id="latitude" name="latitude" class="form-control" value="<?= esc(old('latitude', $facility['latitude'])); ?>" step="any" min="-90" max="90">
                            <?php if ($validation->hasError('latitude')) : ?><small class="text-danger"><?= esc($validation->getError('latitude')); ?></small><?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="longitude">Longitude</label>
                            <input type="number" id="longitude" name="longitude" class="form-control" value="<?= esc(old('longitude', $facility['longitude'])); ?>" step="any" min="-180" max="180">
                            <?php if ($validation->hasError('longitude')) : ?><small class="text-danger"><?= esc($validation->getError('longitude')); ?></small><?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                    <a href="<?= base_url('fasilitas'); ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Peta Fasilitas</h6></div>
        <div class="card-body p-2"><div id="facilityMap" style="height: 420px;"></div></div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Fasilitas</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Luas</th><th>Latitude</th><th>Longitude</th><th>Gambar</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($facilities as $item) : ?>
                            <?php $imageUrl = preg_match('/^https?:\/\//i', $item['link_gambar']) ? $item['link_gambar'] : base_url(ltrim($item['link_gambar'], '/')); ?>
                            <tr>
                                <td><?= (int) $item['id']; ?></td>
                                <td><?= esc($item['nama']); ?></td>
                                <td><?= esc($item['deskripsi']); ?></td>
                                <td><?= esc($item['luas']); ?></td>
                                <td><?= $item['latitude'] !== null ? esc($item['latitude']) : '-'; ?></td>
                                <td><?= $item['longitude'] !== null ? esc($item['longitude']) : '-'; ?></td>
                                <td><a href="<?= esc($imageUrl, 'attr'); ?>" target="_blank" rel="noopener noreferrer"><img src="<?= esc($imageUrl, 'attr'); ?>" alt="<?= esc($item['nama'], 'attr'); ?>" style="max-width:120px; max-height:70px;"></a></td>
                                <td class="text-nowrap">
                                    <a href="<?= base_url('fasilitas/edit/' . $item['id']); ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="<?= base_url('fasilitas/delete/' . $item['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus fasilitas ini?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (!$facilities) : ?><div class="text-muted text-center py-3">Belum ada data fasilitas.</div><?php endif; ?>
        </div>
    </div>
</div>
<script>
    var facilityMapData = <?= json_encode($facilities, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var facilityMap = L.map('facilityMap').setView([-6.1049, 106.8863], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(facilityMap);

    var facilityMarkers = [];
    facilityMapData.forEach(function (item) {
        var latitude = Number(item.latitude);
        var longitude = Number(item.longitude);
        if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) return;

        var marker = L.marker([latitude, longitude]).addTo(facilityMap)
            .bindPopup('<strong>' + escapeFacilityHtml(item.nama) + '</strong><br>' + escapeFacilityHtml(item.deskripsi || '-'));
        facilityMarkers.push(marker);
    });

    var latitudeInput = document.getElementById('latitude');
    var longitudeInput = document.getElementById('longitude');
    var editableMarker;
    if (latitudeInput && longitudeInput) {
        var initialLatitude = Number(latitudeInput.value) || -6.1049;
        var initialLongitude = Number(longitudeInput.value) || 106.8863;
        editableMarker = L.marker([initialLatitude, initialLongitude], {draggable: true}).addTo(facilityMap);

        function updateFacilityCoordinates(latitude, longitude) {
            latitudeInput.value = latitude.toFixed(8);
            longitudeInput.value = longitude.toFixed(8);
            editableMarker.setLatLng([latitude, longitude]);
        }

        facilityMap.on('click', function (event) {
            updateFacilityCoordinates(event.latlng.lat, event.latlng.lng);
        });
        editableMarker.on('dragend', function (event) {
            var position = event.target.getLatLng();
            updateFacilityCoordinates(position.lat, position.lng);
        });
    }

    function escapeFacilityHtml(value) {
        return String(value).replace(/[&<>'"]/g, function (character) {
            return {'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'}[character];
        });
    }
</script>
<?= $this->endSection(); ?>
