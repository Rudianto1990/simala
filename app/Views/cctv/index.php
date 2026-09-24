<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= esc($heading); ?></h1>
        <div>
            <form action="<?= base_url('cctv/sync'); ?>" method="post" class="d-inline">
                <?= csrf_field(); ?>
                <button type="submit" class="btn btn-outline-info" onclick="return confirm('Tarik data CCTV terbaru dari server sumber?');">
                    <i class="fas fa-sync-alt mr-1"></i> Sinkronkan
                </button>
            </form>
            <a href="<?= base_url('cctv/create'); ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah CCTV</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('pesan')); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Peta Lokasi CCTV</h6></div>
                <div class="card-body p-2"><div id="cctvmap" style="height: 420px;"></div></div>
            </div>
        </div>
        <div id="cctvtable" class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Kamera</h6>
                    <form action="<?= base_url('cctv'); ?>" method="get" class="form-inline mt-2 mt-md-0">
                        <label for="cctvSearch" class="sr-only">Cari kamera</label>
                        <input type="search" id="cctvSearch" name="search" value="<?= esc($search ?? ''); ?>" class="form-control form-control-sm mr-2" placeholder="Nama, IP, atau inventory code">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search mr-1"></i> Cari</button>
                        <?php if (($search ?? '') !== '') : ?>
                            <a href="<?= base_url('cctv'); ?>" class="btn btn-outline-secondary btn-sm ml-2">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>nomor</th>
                                    <th>Nama Kamera</th>
                                    <th>Lokasi</th>
                                    <th>IP Address</th>
                                    <th>Inventory Code</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cameras as $camera) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= esc($camera['nama_camera']); ?></td>
                                        <td><?= esc($camera['location']); ?></td>
                                        <td><code><?= esc($camera['ip_address']); ?></code></td>
                                        <td><span class="badge badge-info"><?= esc($camera['inventory_code'] ?? '-'); ?></span></td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-success btn-sm preview-camera" title="Preview CCTV"
                                                data-camera-id="<?= (int) $camera['id']; ?>"
                                                data-camera-name="<?= esc($camera['nama_camera'], 'attr'); ?>"
                                                data-camera-ip="<?= esc($camera['ip_address'], 'attr'); ?>"
                                                data-camera-inventory="<?= esc($camera['inventory_code'] ?? '-', 'attr'); ?>"
                                                data-camera-location="<?= esc($camera['location'], 'attr'); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="<?= base_url('cctv/show/' . $camera['id']); ?>" class="btn btn-info btn-sm" title="Live View"><i class="fas fa-video"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3 overflow-auto">
                        <?= $pager->links('default', 'cctv'); ?>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
            <div class="modal-body text-center">
                <dl id="previewDetails" class="row text-left small mb-3">
                </dl>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var cctvData = <?= json_encode($cameras, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var cctvMap = L.map('cctvmap').setView([-6.103, 106.883], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(cctvMap);

    cctvData.forEach(function (camera) {
        var latitude = Number(camera.latitude);
        var longitude = Number(camera.longitude);
        if (!Number.isFinite(latitude) || !Number.isFinite(longitude) || (latitude === 0 && longitude === 0)) return;
        L.marker([latitude, longitude]).addTo(cctvMap).bindPopup(
            '<strong>' + escapeHtml(camera.nama_camera) + '</strong><br>' +
            escapeHtml(camera.location) + '<br><a href="<?= base_url('cctv/show'); ?>/' + camera.id + '">Lihat CCTV</a>'
        );
    });

    var previewModal = $('#previewModal');
    var previewDetails = document.getElementById('previewDetails');
    var fieldLabels = {
        id: 'ID',
        source_id: 'Source ID',
        inventory_code: 'Inventory Code',
        nama_camera: 'Nama Kamera',
        location: 'Lokasi',
        ip_address: 'IP Address',
        type_camera: 'Tipe Kamera',
        rtsp_url: 'RTSP URL',
        latitude: 'Latitude',
        longitude: 'Longitude',
        source_name: 'Source Name',
        sub_division: 'Sub Division',
        category: 'Category',
        jenis_kategori: 'Jenis Kategori',
        merk: 'Merk',
        serial_number: 'Serial Number',
        source_status: 'Source Status',
        reg_date: 'Registration Date',
        nvr: 'NVR',
        nomor_urut: 'Nomor Urut',
        link_img: 'Image Link',
        source_model: 'Source Model',
        source_created_at: 'Source Created At',
        source_updated_at: 'Source Updated At',
        created_at: 'Created At',
        updated_at: 'Updated At'
    };

    $('.preview-camera').on('click', function () {
        var cameraId = Number(this.dataset.cameraId);
        var camera = cctvData.find(function (item) { return Number(item.id) === cameraId; });
        if (!camera) return;

        document.getElementById('previewModalTitle').textContent = 'Preview CCTV - ' + (camera.nama_camera || '-');
        previewDetails.innerHTML = '';
        Object.keys(camera).forEach(function (key) {
            var label = document.createElement('dt');
            label.className = 'col-sm-4';
            label.textContent = fieldLabels[key] || key;
            var value = document.createElement('dd');
            value.className = 'col-sm-8 mb-1 text-break';
            value.textContent = camera[key] === null || camera[key] === '' ? '-' : camera[key];
            previewDetails.appendChild(label);
            previewDetails.appendChild(value);
        });
        previewModal.modal('show');
    });

    function escapeHtml(value) {
        return String(value).replace(/[&<>'"]/g, function (character) {
            return {'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'}[character];
        });
    }
});
</script>
<?= $this->endSection(); ?>
