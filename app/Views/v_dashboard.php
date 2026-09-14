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
                    <div class="alert alert-info mb-3">
                        Klik denah untuk melihat koordinat posisi. Simpan nilai Y ke kolom <strong>latitude</strong> dan nilai X ke kolom <strong>longitude</strong>.
                    </div>
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Penyesuaian overlay denah</strong>
                            <small class="text-muted">Nilai ini sementara untuk mencari posisi dan ukuran yang tepat.</small>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="imageWidth">imageWidth</label>
                                <input type="number" id="imageWidth" class="form-control form-control-sm" value="640" min="1" step="1" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="imageHeight">imageHeight</label>
                                <input type="number" id="imageHeight" class="form-control form-control-sm" value="480" min="1" step="1" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="boundSouth">South</label>
                                <input type="number" id="boundSouth" class="form-control form-control-sm" value="-6.129" step="0.000001" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="boundWest">West</label>
                                <input type="number" id="boundWest" class="form-control form-control-sm" value="106.845" step="0.000001" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="boundNorth">North</label>
                                <input type="number" id="boundNorth" class="form-control form-control-sm" value="-6.055" step="0.000001" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="boundEast">East</label>
                                <input type="number" id="boundEast" class="form-control form-control-sm" value="106.955" step="0.000001" disabled>
                            </div>
                        </div>
                        <button type="button" id="applyDenahSettings" class="btn btn-sm btn-primary" disabled>Terapkan penyesuaian</button>
                        <span class="small text-muted ml-2">Tarik gambar untuk memindahkan. Tarik handle di sudut kanan-atas untuk mengubah ukuran.</span>
                        <div class="mt-2 small text-muted">Konfigurasi aktif:</div>
                        <code id="denahSettingsInfo" class="d-block text-dark"></code>
                        <div id="denahLiveInfo" class="small text-info mt-1"></div>
                    </div>
                    <div id="mapid" style="height: 500px;"></div>
                    <div id="map-coordinate" class="small text-muted mt-2">Koordinat denah: -</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var alatData = <?= json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var denahUrl = '<?= base_url('img/denah/denah.png'); ?>';

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

    var mymap = L.map('mapid', {
        minZoom: 10,
        maxZoom: 19
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(mymap);

    var imageWidth = 302;
    var imageHeight = 266;
    var denahBounds = [[-6.129768, 106.855984], [-6.075779, 106.925468]];
    var denahOverlay = L.imageOverlay(denahUrl, denahBounds, {
        opacity: 0.82,
        interactive: true
    }).addTo(mymap);
    mymap.fitBounds(denahBounds);

    var dragState = null;
    var resizeState = null;
    var resizeHandle = L.marker(denahBounds[1], {
        draggable: true,
        icon: L.divIcon({
            className: 'denah-resize-handle',
            html: '<div style="width:16px;height:16px;background:#fff;border:2px solid #007bff;border-radius:3px;box-shadow:0 1px 4px rgba(0,0,0,.45);cursor:nwse-resize;"></div>',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        }),
        zIndexOffset: 1000
    }).addTo(mymap);

    function pixelToLatLng(positionY, positionX) {
        var south = denahBounds[0][0];
        var west = denahBounds[0][1];
        var north = denahBounds[1][0];
        var east = denahBounds[1][1];
        return [
            north - (positionY / imageHeight) * (north - south),
            west + (positionX / imageWidth) * (east - west)
        ];
    }

    mymap.on('click', function (event) {
        var x = Math.round(((event.latlng.lng - denahBounds[0][1]) / (denahBounds[1][1] - denahBounds[0][1])) * imageWidth);
        var y = Math.round(((denahBounds[1][0] - event.latlng.lat) / (denahBounds[1][0] - denahBounds[0][0])) * imageHeight);

        document.getElementById('map-coordinate').textContent = 'Koordinat denah: Y = ' + y + ', X = ' + x;
    });

    var markerLayer = L.layerGroup().addTo(mymap);

    function renderMarkers() {
        markerLayer.clearLayers();

        alatData.forEach(function (item) {
        if (item.latitude === null || item.latitude === '' || item.longitude === null || item.longitude === '') {
            return;
        }

        var positionY = Number(item.latitude);
        var positionX = Number(item.longitude);

        if (!Number.isFinite(positionY) || !Number.isFinite(positionX)) {
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

        L.marker(pixelToLatLng(positionY, positionX), { icon: customIcon })
            .bindPopup(popup)
            .addTo(markerLayer);
        });
    }

    function updateDenahSettingsInfo() {
        var overlayWidth = denahBounds[1][1] - denahBounds[0][1];
        var overlayHeight = denahBounds[1][0] - denahBounds[0][0];
        var centerLat = (denahBounds[0][0] + denahBounds[1][0]) / 2;
        var centerLng = (denahBounds[0][1] + denahBounds[1][1]) / 2;

        document.getElementById('denahSettingsInfo').textContent =
            'var imageWidth = ' + imageWidth + '; var imageHeight = ' + imageHeight + '; ' +
            'var denahBounds = [[' + denahBounds[0][0] + ', ' + denahBounds[0][1] + '], [' +
            denahBounds[1][0] + ', ' + denahBounds[1][1] + ']];';
        document.getElementById('denahLiveInfo').textContent =
            'Titik tengah: [' + centerLat.toFixed(6) + ', ' + centerLng.toFixed(6) + '] | ' +
            'Lebar geografis: ' + overlayWidth.toFixed(6) + ' | Tinggi geografis: ' + overlayHeight.toFixed(6) +
            ' | Ukuran koordinat gambar: ' + imageWidth + ' x ' + imageHeight;
    }

    function syncDenahInputs() {
        document.getElementById('boundSouth').value = denahBounds[0][0].toFixed(6);
        document.getElementById('boundWest').value = denahBounds[0][1].toFixed(6);
        document.getElementById('boundNorth').value = denahBounds[1][0].toFixed(6);
        document.getElementById('boundEast').value = denahBounds[1][1].toFixed(6);
    }

    function startDenahDrag(event) {
        dragState = {
            start: event.latlng,
            bounds: [
                [denahBounds[0][0], denahBounds[0][1]],
                [denahBounds[1][0], denahBounds[1][1]]
            ]
        };
        mymap.dragging.disable();
        event.originalEvent.preventDefault();
    }

    function moveDenah(event) {
        if (!dragState) {
            return;
        }

        var deltaLat = event.latlng.lat - dragState.start.lat;
        var deltaLng = event.latlng.lng - dragState.start.lng;
        denahBounds = [
            [dragState.bounds[0][0] + deltaLat, dragState.bounds[0][1] + deltaLng],
            [dragState.bounds[1][0] + deltaLat, dragState.bounds[1][1] + deltaLng]
        ];
        denahOverlay.setBounds(denahBounds);
        resizeHandle.setLatLng(denahBounds[1]);
        syncDenahInputs();
        renderMarkers();
        updateDenahSettingsInfo();
    }

    function endDenahDrag() {
        if (!dragState) {
            return;
        }

        dragState = null;
        mymap.dragging.enable();
    }

    resizeHandle.on('dragstart', function () {
        resizeState = {
            bounds: [
                [denahBounds[0][0], denahBounds[0][1]],
                [denahBounds[1][0], denahBounds[1][1]]
            ],
            imageWidth: imageWidth,
            imageHeight: imageHeight
        };
        mymap.dragging.disable();
    });

    resizeHandle.on('drag', function (event) {
        if (!resizeState) {
            return;
        }

        var newNorth = Math.max(event.latlng.lat, resizeState.bounds[0][0] + 0.000001);
        var newEast = Math.max(event.latlng.lng, resizeState.bounds[0][1] + 0.000001);
        var originalWidth = resizeState.bounds[1][1] - resizeState.bounds[0][1];
        var originalHeight = resizeState.bounds[1][0] - resizeState.bounds[0][0];
        var newWidth = newEast - resizeState.bounds[0][1];
        var newHeight = newNorth - resizeState.bounds[0][0];

        denahBounds = [
            [resizeState.bounds[0][0], resizeState.bounds[0][1]],
            [newNorth, newEast]
        ];
        imageWidth = Math.max(1, Math.round(resizeState.imageWidth * newWidth / originalWidth));
        imageHeight = Math.max(1, Math.round(resizeState.imageHeight * newHeight / originalHeight));
        denahOverlay.setBounds(denahBounds);
        syncDenahInputs();
        renderMarkers();
        updateDenahSettingsInfo();
    });

    resizeHandle.on('dragend', function () {
        resizeState = null;
        mymap.dragging.enable();
    });

    denahOverlay.on('mousedown touchstart', startDenahDrag);
    mymap.on('mousemove touchmove', moveDenah);
    document.addEventListener('mouseup', endDenahDrag);
    document.addEventListener('touchend', endDenahDrag);

    document.getElementById('applyDenahSettings').addEventListener('click', function () {
        imageWidth = Number(document.getElementById('imageWidth').value);
        imageHeight = Number(document.getElementById('imageHeight').value);
        denahBounds = [
            [Number(document.getElementById('boundSouth').value), Number(document.getElementById('boundWest').value)],
            [Number(document.getElementById('boundNorth').value), Number(document.getElementById('boundEast').value)]
        ];

        denahOverlay.setBounds(denahBounds);
        resizeHandle.setLatLng(denahBounds[1]);
        mymap.fitBounds(denahBounds);
        syncDenahInputs();
        renderMarkers();
        updateDenahSettingsInfo();
    });

    renderMarkers();
    updateDenahSettingsInfo();
</script>
<?= $this->endSection(); ?>