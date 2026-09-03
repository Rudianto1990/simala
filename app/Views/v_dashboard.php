<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $heading; ?></h1>
    </div>

    <?= $this->include('template/statusBar'); ?>
<!--MENAMPILAKAN PETA MAPS -->
    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Peta Monitoring Alat</h6>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge badge-pill" style="background:#d9534f; color:#fff; padding:6px 10px;">CC</span>
                        <span class="badge badge-pill" style="background:#5cb85c; color:#fff; padding:6px 10px;">RTG</span>
                        <span class="badge badge-pill" style="background:#5bc0de; color:#fff; padding:6px 10px;">RS</span>
                        <span class="badge badge-pill" style="background:#f0ad4e; color:#fff; padding:6px 10px;">SL/TL</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="mapid" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>" />
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<script>
    var L = window.L;
    var centerLat = -6.2088;
    var centerLng = 106.8456;

    var alatData = <?= json_encode($data); ?>;
    var hasValidPoint = false;
    var bounds = L.latLngBounds([]);

    function getCategoryKey(item) {
        var nama = (item.nama_alat || '').toUpperCase();

        if (nama.indexOf('CRANE') !== -1) {
            return 'cc';
        }

        if (nama.indexOf('GANTRY') !== -1 || nama.indexOf('RTG') !== -1) {
            return 'rtg';
        }

        if (nama.indexOf('STACKER') !== -1 || nama.indexOf('REACH STACKER') !== -1 || nama.indexOf('RS') !== -1) {
            return 'rs';
        }

        if (nama.indexOf('LOADER') !== -1 || nama.indexOf('SIDE LOADER') !== -1 || nama.indexOf('TOP LOADER') !== -1 || nama.indexOf('SL') !== -1 || nama.indexOf('TL') !== -1) {
            return 'sltl';
        }

        return 'cc';
    }

    function getMarkerColor(categoryKey) {
        var colors = {
            cc: '#d9534f',
            rtg: '#5cb85c',
            rs: '#5bc0de',
            sltl: '#f0ad4e'
        };

        return colors[categoryKey] || '#d9534f';
    }

    alatData.forEach(function (item) {
        if (item.latitude && item.longitude) {
            hasValidPoint = true;
            centerLat = Number(item.latitude);
            centerLng = Number(item.longitude);
            bounds.extend([Number(item.latitude), Number(item.longitude)]);
        }
    });

    var mymap = L.map('mapid').setView([centerLat, centerLng], hasValidPoint ? 10 : 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mymap);

    if (hasValidPoint && bounds.isValid()) {
        mymap.fitBounds(bounds, { padding: [30, 30] });
    }

    alatData.forEach(function (item) {
        if (!item.latitude || !item.longitude) {
            return;
        }

        var categoryKey = getCategoryKey(item);
        var markerColor = getMarkerColor(categoryKey);
        var foto = item.foto_alat ? '<?= base_url('img/alat'); ?>/' + item.foto_alat : '<?= base_url('img/alat/default.png'); ?>';

        var popup = '<div style="min-width:220px;">' +
            '<img src="' + foto + '" class="img-fluid mb-2" style="max-height:120px; width:100%; object-fit:cover;">' +
            '<b>' + (item.nama_alat || '-') + '</b><br>' +
            'Kode: ' + (item.kode_alat || '-') + '<br>' +
            'Status: ' + (item.status || '-') + '<br>' +
            'Lokasi: ' + (item.lokasi || '-') +
            '</div>';

        var nama = (item.nama_alat || '').toUpperCase();
        var markerIcon = nama.indexOf('CRANE') !== -1 ? 'fa-truck-moving' :
            (nama.indexOf('GANTRY') !== -1 || nama.indexOf('RTG') !== -1 ? 'fa-warehouse' :
            (nama.indexOf('STACKER') !== -1 || nama.indexOf('REACH STACKER') !== -1 || nama.indexOf('RS') !== -1 ? 'fa-ship' : 'fa-boxes'));

        var iconHtml = '<div style="position:relative;width:32px;height:44px;display:flex;align-items:center;justify-content:center;">' +
            '<svg viewBox="0 0 32 44" width="32" height="44" style="position:absolute;top:0;left:0;z-index:1;">' +
            '<path d="M16,2 C8,2 2,8 2,16 C2,26 16,42 16,42 C16,42 30,26 30,16 C30,8 24,2 16,2 Z" fill="' + markerColor + '" stroke="white" stroke-width="1.5" style="filter:drop-shadow(0 2px 4px rgba(0,0,0,0.25));"/>' +
            '</svg>' +
            '<i class="fas ' + markerIcon + '" style="position:absolute;top:50%;left:50%;transform:translate(-50%, -55%);z-index:2;font-size:16px;color:white;text-shadow:0 0 2px rgba(0,0,0,0.3);line-height:1;"></i>' +
            '</div>';

        var customIcon = L.divIcon({
            className: 'custom-pin-marker',
            html: iconHtml,
            iconSize: [32, 44],
            iconAnchor: [16, 44],
            popupAnchor: [0, -34]
        });

        L.marker([Number(item.latitude), Number(item.longitude)], { icon: customIcon })
            .bindPopup(popup)
            .addTo(mymap);
    });
</script>
<?= $this->endSection(); ?>