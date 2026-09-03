<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('css/sb-admin-2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('components/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>">
    <script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Detail Alat</h6>
                    </div>
                    <div class="card-body">
                        <img src="<?= base_url('img/alat/' . $data['foto_alat']); ?>" class="img-fluid mb-3" alt="<?= $data['nama_alat']; ?>">
                        <h4><?= $data['nama_alat']; ?></h4>
                        <p><strong>Nomor Asset:</strong> <?= $data['nomor_asset']; ?></p>
                        <p><strong>Kode Alat:</strong> <?= $data['kode_alat']; ?></p>
                        <p><strong>Merk:</strong> <?= $data['merk']; ?></p>
                        <p><strong>Model:</strong> <?= $data['model']; ?></p>
                        <p><strong>Status:</strong> <?= $data['status']; ?></p>
                        <p><strong>Lokasi:</strong> <?= $data['lokasi']; ?></p>
                        <p><strong>Negara:</strong> <?= $data['negara']; ?></p>
                        <p><strong>Kap. / Ton:</strong> <?= $data['kap_swal_ton']; ?></p>
                        <p><strong>Span (m):</strong> <?= $data['span_m']; ?></p>
                        <p><strong>Outreach (m):</strong> <?= $data['outreach_m']; ?></p>
                        <p><strong>Keterangan:</strong> <?= $data['keterangan']; ?></p>

                        <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $data['latitude']; ?>,<?= $data['longitude']; ?>" target="_blank" class="btn btn-primary mt-2">
                            <i class="fas fa-map-marker-alt"></i> Rute ke Google Maps
                        </a>
                        <a href="<?= base_url('form/dataalat'); ?>" class="btn btn-secondary mt-2">Kembali</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Peta Lokasi</h6>
                    </div>
                    <div class="card-body">
                        <div id="mapid" style="height: 420px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var mymap = L.map('mapid').setView([<?= $data['latitude']; ?>, <?= $data['longitude']; ?>], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mymap);

        L.marker([<?= $data['latitude']; ?>, <?= $data['longitude']; ?>])
            .addTo(mymap)
            .bindPopup('<b><?= $data['nama_alat']; ?></b><br><?= $data['lokasi']; ?>');
    </script>
</body>
</html>
