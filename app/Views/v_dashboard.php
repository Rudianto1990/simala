<?= $this->extend('template/BaseView'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $heading; ?></h1>
    </div>

    <?= $this->include('template/statusBar'); ?>
<!--MENAMPILAKAN PETA MAPS COYYYYYYYYYYYYYYYYYYYYYYYYYYYYY eror -->
    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                 
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge badge-pill" style="background:#d9534f; color:#fff; padding:6px 10px;">MBC</span>
                        <span class="badge badge-pill" style="background:#5cb85c; color:#fff; padding:6px 10px;">RTG</span>
                        <span class="badge badge-pill" style="background:#f0c419; color:#fff; padding:6px 10px;">OHC</span>
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
                                <input type="number" id="imageWidth" class="form-control form-control-sm" value="304" min="1" step="1" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="imageHeight">imageHeight</label>
                                <input type="number" id="imageHeight" class="form-control form-control-sm" value="162" min="1" step="1" disabled>
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
                        <button type="button" id="toggleDenahLock" class="btn btn-sm btn-secondary"><i class="fas fa-lock mr-1"></i>Buka kunci</button>
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

<div class="modal fade" id="facilityDetailModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="facilityDetailTitle">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="facilityDetailTitle">Detail Fasilitas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <img id="facilityDetailImage" class="img-fluid rounded mb-3" alt="Gambar fasilitas">
                <dl class="row mb-0">
                    <dt class="col-sm-4">ID</dt><dd class="col-sm-8" id="facilityDetailId"></dd>
                    <dt class="col-sm-4">Nama</dt><dd class="col-sm-8" id="facilityDetailName"></dd>
                    <dt class="col-sm-4">Deskripsi</dt><dd class="col-sm-8" id="facilityDetailDescription"></dd>
                    <dt class="col-sm-4">Luas</dt><dd class="col-sm-8" id="facilityDetailArea"></dd>
                    <dt class="col-sm-4">Latitude</dt><dd class="col-sm-8" id="facilityDetailLatitude"></dd>
                    <dt class="col-sm-4">Longitude</dt><dd class="col-sm-8" id="facilityDetailLongitude"></dd>
                </dl>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="<?= base_url('js/leaflet/leaflet.css'); ?>" />
<script src="<?= base_url('js/leaflet/leaflet.js'); ?>"></script>
<style>
    #mapid:fullscreen {
        width: 100vw;
        height: 100vh;
    }

    #mapid:-webkit-full-screen {
        width: 100vw;
        height: 100vh;
    }

    .map-fullscreen-control button {
        width: 30px;
        height: 30px;
        padding: 0;
        border: 0;
        background: #fff;
        color: #333;
        cursor: pointer;
        font-size: 14px;
    }

    .map-asset-label {
        background: transparent;
        border: none;
        border-radius: 3px;
        box-shadow: none;
        color: #212529;
        font-size: 8px;
        font-weight: 600;
        padding: 2px 4px;
        white-space: nowrap;
    }

    .map-asset-label::before {
        border-top-color: transparent;
    }

    .cctv-map-popup {
        min-width: 250px;
    }

    .cctv-map-popup-view {
        position: relative;
        overflow: hidden;
        background: #111827;
        border-radius: 4px;
    }

    .cctv-map-popup-view img {
        display: block;
        width: 100%;
        height: 145px;
        object-fit: contain;
    }

    .cctv-live-status {
        color: #198754;
        font-size: 11px;
    }

    .cctv-map-popup-view:fullscreen {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100vw;
        height: 100vh;
        background: #030712;
    }

    .cctv-map-popup-view:fullscreen img {
        width: 100%;
        height: 100%;
        max-height: 100vh;
    }

    .cctv-popup-fullscreen {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 30px;
        height: 30px;
        padding: 0;
        border: 0;
        border-radius: 3px;
        background: rgba(17, 24, 39, .82);
        color: #fff;
        cursor: pointer;
    }
</style>

<script>
    var alatData = <?= json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var cctvData = <?= json_encode($cctv ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var facilityData = <?= json_encode($facilities ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var selectedFacilityId = <?= (int) ($selectedFacilityId ?? 0); ?>;

    var selectedFacility = <?= json_encode($selectedFacility ?? null, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var denahUrl = selectedFacility ? selectedFacility.image_url : '';

    var mymap = L.map('mapid', {
        zoomControl: false
    }).setView([-6.1049, 106.8863], 15);

    L.control.zoom({
        position: 'topright',
        zoomInTitle: 'Perbesar peta',
        zoomOutTitle: 'Perkecil peta'
    }).addTo(mymap);

    var FullscreenControl = L.Control.extend({
        options: {
            position: 'topright'
        },

        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control map-fullscreen-control');
            var button = L.DomUtil.create('button', '', container);

            button.type = 'button';
            button.title = 'Buka peta layar penuh';
            button.setAttribute('aria-label', 'Buka peta layar penuh');
            button.innerHTML = '<i class="fas fa-expand"></i>';

            function updateButton() {
                var isFullscreen = document.fullscreenElement === map.getContainer();
                button.innerHTML = isFullscreen ? '<i class="fas fa-compress"></i>' : '<i class="fas fa-expand"></i>';
                button.title = isFullscreen ? 'Keluar dari layar penuh' : 'Buka peta layar penuh';
                button.setAttribute('aria-label', button.title);
                map.invalidateSize();
            }

            L.DomEvent.disableClickPropagation(container);
            L.DomEvent.on(button, 'click', function (event) {
                L.DomEvent.stop(event);

                if (document.fullscreenElement) {
                    document.exitFullscreen();
                    return;
                }

                if (map.getContainer().requestFullscreen) {
                    map.getContainer().requestFullscreen();
                }
            });

            document.addEventListener('fullscreenchange', updateButton);

            return container;
        }
    });

    new FullscreenControl().addTo(mymap);

    var LayoutToggleControl = L.Control.extend({
        options: {
            position: 'topleft'
        },
        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            container.style.backgroundColor = '#fff';
            container.style.padding = '4px';
            container.style.borderRadius = '4px';
            container.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            var options = facilityData.map(function (facility) {
                var selected = Number(facility.id) === selectedFacilityId ? ' selected' : '';
                return '<option value="' + Number(facility.id) + '"' + selected + '>' + escapeMapHtml(facility.nama) + '</option>';
            }).join('');
            container.innerHTML = '<label for="facilitySelector" class="sr-only">Pilih fasilitas</label>' +
                '<select id="facilitySelector" class="form-control form-control-sm" style="min-width:170px;">' + options + '</select>';
            container.querySelector('select').addEventListener('change', function () {
                switchLayout(Number(this.value));
            });
            L.DomEvent.disableClickPropagation(container);
            return container;
        }
    });
    new LayoutToggleControl().addTo(mymap);

    var MarkerTypeControl = L.Control.extend({
        options: {
            position: 'topleft'
        },
        onAdd: function (map) {
            var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            container.style.backgroundColor = '#fff';
            container.style.padding = '4px';
            container.style.borderRadius = '4px';
            container.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            container.innerHTML = '<label for="markerTypeSelector" class="sr-only">Pilih marker peta</label>' +
                '<select id="markerTypeSelector" class="form-control form-control-sm" style="min-width:170px;">' +
                '<option value="alat">Marker Alat Berat</option>' +
                '<option value="cctv">Marker CCTV</option>' +
                '<option value="fasilitas">Marker Fasilitas</option>' +
                '</select>';
            container.querySelector('select').addEventListener('change', function () {
                setMarkerMode(this.value);
            });
            L.DomEvent.disableClickPropagation(container);
            return container;
        }
    });
    new MarkerTypeControl().addTo(mymap);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(mymap);

    var imageWidth = 300;
    var imageHeight = 161;
    var denahBounds = [[-6.122599236486045, 106.85564050487093], [-6.089520085244972, 106.92418102745674]];
    var denahOverlay = L.imageOverlay(denahUrl, denahBounds, {
        opacity: 0.82,
        interactive: true
    }).addTo(mymap);
    var dragState = null;
    var resizeState = null;
    var denahLocked = true;
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

    mymap.on('click', function (event) {
        var x = Math.round(((event.latlng.lng - denahBounds[0][1]) / (denahBounds[1][1] - denahBounds[0][1])) * imageWidth);
        var y = Math.round(((denahBounds[1][0] - event.latlng.lat) / (denahBounds[1][0] - denahBounds[0][0])) * imageHeight);

        document.getElementById('map-coordinate').textContent = 'Koordinat denah: Y = ' + y + ', X = ' + x;
    });

    var markerLayer = L.layerGroup().addTo(mymap);
    var cctvMarkerLayer = L.layerGroup().addTo(mymap);
    var facilityMarkerLayer = L.layerGroup().addTo(mymap);
    var cctvSnapshotUrl = '<?= base_url('cctv/snapshot'); ?>';
    var cctvLiveTimers = {};
    var markerMode = 'alat';

    function setMarkerMode(mode) {
        markerMode = ['alat', 'cctv', 'fasilitas'].indexOf(mode) !== -1 ? mode : 'alat';

        if (markerMode === 'cctv') {
            cctvMarkerLayer.addTo(mymap);
            markerLayer.removeFrom(mymap);
            facilityMarkerLayer.removeFrom(mymap);
            return;
        }

        if (markerMode === 'fasilitas') {
            facilityMarkerLayer.addTo(mymap);
            markerLayer.removeFrom(mymap);
            cctvMarkerLayer.removeFrom(mymap);
            return;
        }

        markerLayer.addTo(mymap);
        cctvMarkerLayer.removeFrom(mymap);
        facilityMarkerLayer.removeFrom(mymap);
    }

    function showFacilityDetail(facility) {
        document.getElementById('facilityDetailTitle').textContent = facility.nama || 'Detail Fasilitas';
        document.getElementById('facilityDetailId').textContent = facility.id || '-';
        document.getElementById('facilityDetailName').textContent = facility.nama || '-';
        document.getElementById('facilityDetailDescription').textContent = facility.deskripsi || '-';
        document.getElementById('facilityDetailArea').textContent = facility.luas || '-';
        document.getElementById('facilityDetailLatitude').textContent = facility.latitude || '-';
        document.getElementById('facilityDetailLongitude').textContent = facility.longitude || '-';

        var image = document.getElementById('facilityDetailImage');
        image.src = facility.image_url || '';
        image.alt = facility.nama || 'Gambar fasilitas';
        image.style.display = facility.image_url ? 'block' : 'none';
        $('#facilityDetailModal').modal('show');
    }

    function renderFacilityMarkers() {
        facilityMarkerLayer.clearLayers();

        facilityData.forEach(function (facility) {
            var latitude = Number(facility.latitude);
            var longitude = Number(facility.longitude);
            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) return;

            var facilityIcon = L.divIcon({
                className: 'facility-map-marker',
                html: '<i class="fas fa-building" style="color:#0d6efd;font-size:28px;text-shadow:0 1px 2px #fff;"></i>',
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
            var marker = L.marker([latitude, longitude], {icon: facilityIcon})
                .bindTooltip(facility.nama || 'Fasilitas')
                .addTo(facilityMarkerLayer);
            marker.on('click', function () {
                showFacilityDetail(facility);
            });
        });
    }

    function renderCctvMarkers() {
        cctvMarkerLayer.clearLayers();

        cctvData.forEach(function (camera) {
            var latitude = Number(camera.latitude);
            var longitude = Number(camera.longitude);

            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                return;
            }

            var cameraIcon = L.divIcon({
                className: 'cctv-map-marker',
                html: '<svg width="32" height="32" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-label="CCTV">' +
                    '<path d="M17 16l4-5 21 12-4 5z" fill="#171717"/>' +
                    '<path d="M14 18l25 14-7 5-17-9c-3.5-2-4.4-5.9-1-10z" fill="#171717"/>' +
                    '<path d="M36 30l6 3-5 5-6-3z" fill="#171717"/>' +
                    '<circle cx="20" cy="28" r="3.2" fill="#ffffff" stroke="#171717" stroke-width="2"/>' +
                    '<path d="M20 31l-4 8-7 1v2h9l5-10z" fill="#171717"/>' +
                    '<path d="M8 37h4v10H8zM8 39h8v2H8z" fill="#171717"/>' +
                    '</svg>',
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -20]
            });
            var detailUrl = '<?= base_url('cctv/show'); ?>/' + encodeURIComponent(camera.id);
            var snapshotUrl = cctvSnapshotUrl + '/' + encodeURIComponent(camera.id) + '?t=' + Date.now();
            var popup = '<div class="cctv-map-popup">' +
                '<div class="font-weight-bold mb-1">' + escapeMapHtml(camera.nama_camera || 'CCTV') + '</div>' +
                '<div class="small text-muted mb-2"><i class="fas fa-map-marker-alt mr-1"></i>' + escapeMapHtml(camera.location || '-') + '</div>' +
                '<div class="small mb-2"><i class="fas fa-network-wired mr-1"></i><code>' + escapeMapHtml(camera.ip_address || '-') + '</code></div>' +
                '<div class="cctv-map-popup-view" id="cctv-view-' + escapeMapHtml(camera.id) + '">' +
                '<img class="cctv-live-image" src="' + snapshotUrl + '" alt="View CCTV ' + escapeMapHtml(camera.nama_camera || '') + '" onerror="this.alt=\'Snapshot CCTV tidak tersedia\';" />' +
                '<button type="button" class="cctv-popup-fullscreen" title="Maximize view CCTV" aria-label="Maximize view CCTV" onclick="toggleCctvFullscreen(this)"><i class="fas fa-expand"></i></button>' +
                '</div>' +
                '<div class="mt-2 d-flex justify-content-between align-items-center">' +
                '<small class="text-muted">Lat: ' + latitude.toFixed(6) + ', Lng: ' + longitude.toFixed(6) + '</small>' +
                '<small class="cctv-live-status"><i class="fas fa-circle mr-1"></i>Live snapshot</small>' +
                '<a class="btn btn-primary btn-sm ml-2" href="' + detailUrl + '"><i class="fas fa-video mr-1"></i>Detail</a>' +
                '</div></div>';

            var marker = L.marker([latitude, longitude], { icon: cameraIcon })
                .bindPopup(popup)
                .bindTooltip(camera.nama_camera || 'CCTV')
                .addTo(cctvMarkerLayer);

            marker.on('popupopen', function (event) {
                startCctvLiveSnapshot(event.popup.getElement(), camera.id);
            });
            marker.on('popupclose', function () {
                stopCctvLiveSnapshot(camera.id);
            });
        });
    }

    function startCctvLiveSnapshot(popupElement, cameraId) {
        stopCctvLiveSnapshot(cameraId);

        var image = popupElement.querySelector('.cctv-live-image');
        if (!image) {
            return;
        }

        var refresh = function () {
            image.src = cctvSnapshotUrl + '/' + encodeURIComponent(cameraId) + '?t=' + Date.now();
        };

        cctvLiveTimers[cameraId] = window.setInterval(refresh, 2000);
    }

    function stopCctvLiveSnapshot(cameraId) {
        if (cctvLiveTimers[cameraId]) {
            window.clearInterval(cctvLiveTimers[cameraId]);
            delete cctvLiveTimers[cameraId];
        }
    }

    function escapeMapHtml(value) {
        return String(value).replace(/[&<>'"]/g, function (character) {
            return {'&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'}[character];
        });
    }

    function toggleCctvFullscreen(button) {
        var view = button.parentElement;

        if (document.fullscreenElement === view) {
            document.exitFullscreen();
            return;
        }

        if (view.requestFullscreen) {
            view.requestFullscreen();
        }
    }

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

        var categoryKey = item.category;
        var markerColor = item.marker_color;
        var foto = item.foto_url;
        var coordinate = {
            mapPosition: [Number(item.map_latitude), Number(item.map_longitude)],
            latitude: Number(item.map_latitude),
            longitude: Number(item.map_longitude)
        };
        var googleMapsUrl = item.google_maps_url;

        var popup = '<div style="min-width:220px;">' +
            '<img src="' + foto + '" class="img-fluid mb-2" style="max-height:120px; width:100%; object-fit:cover;">' +
            '<b>' + (item.nama_alat || '-') + '</b><br>' +
            'Kode: ' + (item.kode_alat || '-') + '<br>' +
            'Status: ' + (item.status || '-') + '<br>' +
            'Lokasi: ' + (item.lokasi || '-') + '<br>' +
            '<a href="' + googleMapsUrl + '" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm mt-2">' +
            '<i class="fas fa-map-marker-alt"></i> Buka Google Maps</a>' +
            '</div>';

        var isRtg = categoryKey === 'rtg';
        var isMbc = categoryKey === 'mbc';
        var isOhc = categoryKey === 'ohc';
        var markerIcon = item.marker_icon;
        //var assetLabel = (item.kode_alat || '-') + ' - ' + (item.nama_alat || '-');
        //var assetLabel = (item.kode_alat || '-');
        var assetLabel = '';

        var iconHtml = isRtg ?
            '<div style="position:relative;width:30px;height:42px;display:flex;align-items:center;justify-content:center;">' +
            '<svg viewBox="0 0 64 88" width="30" height="42" style="position:absolute;top:0;left:0;z-index:1;filter:drop-shadow(0 2px 3px rgba(0,0,0,0.25));">' +
            '<path d="M32 2C16 2 4 14 4 29c0 20 28 55 28 55s28-35 28-55C60 14 48 2 32 2Z" fill="#08e6d4"/>' +
            '<path d="M15 30h34v4H15zM19 30v25M45 30v25M21 35l9-7 9 7" fill="none" stroke="#b52d25" stroke-width="2.2"/>' +
            '<path d="M12 27h40v4H12z" fill="#e85b24"/>' +
            '<path d="M22 27l10-8 10 8M32 19v12" fill="none" stroke="#b52d25" stroke-width="1.8"/>' +
            '<circle cx="32" cy="19" r="2.2" fill="#80c83d"/>' +
            '<path d="M32 31v10M29 41h6v4h-6zM26 45h12v10H26z" fill="#e85b24"/>' +
            '<path d="M27 47h10v8H27z" fill="#10ad1d" stroke="#06eb38" stroke-width="1.2"/>' +
            '<path d="M18 55h28v5H18z" fill="#365433"/><path d="M14 60h36v4H14z" fill="#334b54"/>' +
            '<path d="M19 61h6M31 61h6M43 61h5" stroke="#fff" stroke-width="1.4"/>' +
            '</svg>' +
            '</div>' :
            isMbc ?
            '<div style="position:relative;width:32px;height:44px;display:flex;align-items:center;justify-content:center;">' +
            '<svg viewBox="0 0 64 88" width="32" height="44" style="position:absolute;top:0;left:0;z-index:1;filter:drop-shadow(0 2px 3px rgba(0,0,0,0.25));">' +
            '<path d="M32 2C16 2 4 14 4 29c0 20 28 55 28 55s28-35 28-55C60 14 48 2 32 2Z" fill="#d9534f"/>' +
            '<path d="M14 49h36v7H14z" fill="#273b42" stroke="#17272c" stroke-width="1"/>' +
            '<circle cx="23" cy="56" r="5" fill="#17272c" stroke="#e9b83f" stroke-width="2"/>' +
            '<circle cx="44" cy="56" r="5" fill="#17272c" stroke="#e9b83f" stroke-width="2"/>' +
            '<path d="M35 48V36h10l5 5v7z" fill="#0ce228" stroke="#17272c" stroke-width="1.8"/>' +
            '<path d="M37 38h7l3 3h-10z" fill="#f6d15b"/>' +
            '<path d="M31 45L22 25l3-2 12 22z" fill="#f0c14b" stroke="#17272c" stroke-width="1.8"/>' +
            '<path d="M24 24L46 13l2 4-22 11z" fill="#334f5b" stroke="#17272c" stroke-width="1.8"/>' +
            '<path d="M45 13l7-4 3 5-7 4z" fill="#f0c14b" stroke="#17272c" stroke-width="1.8"/>' +
            '<path d="M53 14v12" stroke="#17272c" stroke-width="1.6"/><path d="M50 26h6v4h-6z" fill="#334f5b" stroke="#17272c" stroke-width="1.2"/>' +
            '<path d="M52 30v5" stroke="#17272c" stroke-width="1.4"/>' +
            '</svg>' +
            '</div>' :
            isOhc ?
            '<div style="position:relative;width:32px;height:44px;display:flex;align-items:center;justify-content:center;">' +
            '<svg viewBox="0 0 64 88" width="32" height="44" style="position:absolute;top:0;left:0;z-index:1;filter:drop-shadow(0 2px 3px rgba(0,0,0,0.25));">' +
            '<path d="M32 2C16 2 4 14 4 29c0 20 28 55 28 55s28-35 28-55C60 14 48 2 32 2Z" fill="#f0c419"/>' +
            '<path d="M15 20h34v5H15z" fill="#f5a623" stroke="#334b54" stroke-width="1.2"/>' +
            '<path d="M20 25v18M44 25v18" stroke="#334b54" stroke-width="2.2"/>' +
            '<path d="M25 25v10M31 25v10M37 25v10" stroke="#334b54" stroke-width="1.8" stroke-linecap="round"/>' +
            '<path d="M25 35c0 5 6 5 6 0M31 35c0 5 6 5 6 0M37 35c0 5 6 5 6 0" fill="none" stroke="#334b54" stroke-width="1.8"/>' +
            '<path d="M31 35v9" stroke="#334b54" stroke-width="1.8"/><path d="M28 44h6v4h-6z" fill="#8c9aa0" stroke="#334b54" stroke-width="1.1"/>' +
            '<path d="M31 48v4c0 3 4 3 4 0" fill="none" stroke="#334b54" stroke-width="1.8"/>' +
            '<path d="M27 52h10v9H27z" fill="#e85b24" stroke="#a53c24" stroke-width="1.1"/>' +
            '<path d="M29 54v5M32 54v5M35 54v5" stroke="#c74c2c" stroke-width="1"/>' +
            '</svg>' +
            '</div>' :
            '<div style="position:relative;width:32px;height:44px;display:flex;align-items:center;justify-content:center;">' +
            '<svg viewBox="0 0 32 44" width="32" height="44" style="position:absolute;top:0;left:0;z-index:1;">' +
            '<path d="M16,2 C8,2 2,8 2,16 C2,26 16,42 16,42 C16,42 30,26 30,16 C30,8 24,2 16,2 Z" fill="' + markerColor + '" stroke="white" stroke-width="1.5" style="filter:drop-shadow(0 2px 4px rgba(0,0,0,0.25));"/>' +
            '</svg>' +
            '<i class="fas ' + markerIcon + '" style="position:absolute;top:50%;left:50%;transform:translate(-50%, -67%);z-index:2;font-size:14px;color:white;text-shadow:0 0 2px rgba(0,0,0,0.3);line-height:1;"></i>' +
            '</div>';

        var customIcon = L.divIcon({
            className: 'custom-pin-marker',
            html: iconHtml,
            iconSize: isRtg ? [30, 42] : [32, 44],
            iconAnchor: isRtg ? [15, 42] : [16, 44],
            popupAnchor: [0, -34]
        });

        L.marker(coordinate.mapPosition, { icon: customIcon })
            .bindTooltip(assetLabel, {
                permanent: true,
                direction: 'top',
                offset: [0, -42],
                className: 'map-asset-label'
            })
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
        document.getElementById('imageWidth').value = imageWidth;
        document.getElementById('imageHeight').value = imageHeight;
        document.getElementById('boundSouth').value = denahBounds[0][0].toFixed(6);
        document.getElementById('boundWest').value = denahBounds[0][1].toFixed(6);
        document.getElementById('boundNorth').value = denahBounds[1][0].toFixed(6);
        document.getElementById('boundEast').value = denahBounds[1][1].toFixed(6);
    }

    function startDenahDrag(event) {
        if (denahLocked) {
            return;
        }

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
        if (denahLocked) {
            resizeHandle.setLatLng(denahBounds[1]);
            return;
        }

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

    function setDenahLockState(locked) {
        denahLocked = locked;

        ['imageWidth', 'imageHeight', 'boundSouth', 'boundWest', 'boundNorth', 'boundEast'].forEach(function (id) {
            document.getElementById(id).disabled = locked;
        });
        document.getElementById('applyDenahSettings').disabled = locked;

        if (locked) {
            dragState = null;
            resizeState = null;
            mymap.dragging.enable();
            resizeHandle.setLatLng(denahBounds[1]);
            resizeHandle.setOpacity(0.45);
        } else {
            resizeHandle.setOpacity(1);
        }

        var lockButton = document.getElementById('toggleDenahLock');
        lockButton.innerHTML = locked
            ? '<i class="fas fa-lock mr-1"></i>Buka kunci'
            : '<i class="fas fa-unlock mr-1"></i>Kunci overlay';
        lockButton.className = locked ? 'btn btn-sm btn-secondary' : 'btn btn-sm btn-warning';
    }

    denahOverlay.on('mousedown touchstart', startDenahDrag);
    mymap.on('mousemove touchmove', moveDenah);
    document.addEventListener('mouseup', endDenahDrag);
    document.addEventListener('touchend', endDenahDrag);

    document.getElementById('toggleDenahLock').addEventListener('click', function () {
        setDenahLockState(!denahLocked);
    });

    document.getElementById('applyDenahSettings').addEventListener('click', function () {
        if (denahLocked) {
            return;
        }

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
    renderCctvMarkers();
    renderFacilityMarkers();
    setMarkerMode('alat');
    updateDenahSettingsInfo();
    setDenahLockState(true);

    function switchLayout(facilityId) {
        var facility = facilityData.find(function (item) {
            return Number(item.id) === Number(facilityId);
        });

        if (!facility) return;

        selectedFacilityId = Number(facility.id);
        denahOverlay.setUrl(facility.image_url);
        updateDenahSettingsInfo();
    }

    if (selectedFacility) {
        switchLayout(selectedFacility.id);
    }
</script>
<?= $this->endSection(); ?>