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
                    <div class="d-flex flex-wrap align-items-center mb-2 mb-md-0">
                        <h6 class="m-0 font-weight-bold text-primary mr-3 mb-2 mb-sm-0">Peta Monitoring Alat</h6>
                        <div class="btn-group shadow-sm" role="group" aria-label="Toggle Layout Peta">
                            <button type="button" class="btn btn-sm <?= ($activeLayout ?? 'baso') === 'baso' ? 'btn-primary active' : 'btn-outline-primary'; ?>" id="btnLayoutBaso" onclick="switchLayout('baso')">
                                <i class="fas fa-layer-group mr-1"></i> BASO
                            </button>
                            <button type="button" class="btn btn-sm <?= ($activeLayout ?? 'baso') === 'kalijapat' ? 'btn-primary active' : 'btn-outline-primary'; ?>" id="btnLayoutKalijapat" onclick="switchLayout('kalijapat')">
                                <i class="fas fa-anchor mr-1"></i> Dermaga Kalijapat
                            </button>
                            <button type="button" class="btn btn-sm <?= ($activeLayout ?? 'baso') === 'dermaga_a' ? 'btn-primary active' : 'btn-outline-primary'; ?>" id="btnLayoutDermagaA" onclick="switchLayout('dermaga_a')">
                                <i class="fas fa-ship mr-1"></i> Dermaga A
                            </button>
                            <button type="button" class="btn btn-sm <?= ($activeLayout ?? 'baso') === 'dermaga_b' ? 'btn-primary active' : 'btn-outline-primary'; ?>" id="btnLayoutDermagaB" onclick="switchLayout('dermaga_b')">
                                <i class="fas fa-ship mr-1"></i> Dermaga B
                            </button>
                            <button type="button" class="btn btn-sm <?= ($activeLayout ?? 'baso') === 'dermaga_c' ? 'btn-primary active' : 'btn-outline-primary'; ?>" id="btnLayoutDermagaC" onclick="switchLayout('dermaga_c')">
                                <i class="fas fa-ship mr-1"></i> Dermaga C
                            </button>
                        </div>
                    </div>
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
    var denahUrl = '<?= base_url(($activeLayout ?? "baso") === "kalijapat" ? "img/denah/kalijapat.png" : "img/denah/denah.png"); ?>';

    function getCategoryKey(item) {
        var nama = (item.nama_alat || '').toUpperCase();

        if (nama.indexOf('MBC') !== -1 || nama.indexOf('MOBILE CRANE') !== -1 || nama.indexOf('CONTAINER CRANE') !== -1) {
            return 'mbc';
        }

        if (nama.indexOf('GANTRY') !== -1 || nama.indexOf('RTG') !== -1) {
            return 'rtg';
        }

        if (nama.indexOf('OHC') !== -1 || nama.indexOf('OVERHEAD CRANE') !== -1 || nama.indexOf('REACH STACKER') !== -1) {
            return 'ohc';
        }

        if (nama.indexOf('LOADER') !== -1 || nama.indexOf('SIDE LOADER') !== -1 || nama.indexOf('TOP LOADER') !== -1 || nama.indexOf('SL') !== -1 || nama.indexOf('TL') !== -1) {
            return 'sltl';
        }

        return 'mbc';
    }

    function getMarkerColor(categoryKey) {
        var colors = {
            mbc: '#d9534f',
            rtg: 'rgb(14, 247, 45)',
            ohc: '#f0c419',
            sltl: '#f0ad4e'
        };

        return colors[categoryKey] || '#d9534f';
    }

    var mymap = L.map('mapid', {
        zoomControl: false
    }).setView([-6.103, 106.883], 14);

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
            container.innerHTML = '<div class="btn-group btn-group-toggle" role="group">' +
                '<button type="button" class="btn btn-xs btn-primary font-weight-bold" id="mapBtnBaso" onclick="switchLayout(\'baso\')" style="font-size:11px; padding:3px 8px;">BASO</button>' +
                '<button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="mapBtnKalijapat" onclick="switchLayout(\'kalijapat\')" style="font-size:11px; padding:3px 8px;">Dermaga Kalijapat</button>' +
                '<button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="mapBtnDermagaA" onclick="switchLayout(\'dermaga_a\')" style="font-size:11px; padding:3px 8px;">Dermaga A</button>' +
                '<button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="mapBtnDermagaB" onclick="switchLayout(\'dermaga_b\')" style="font-size:11px; padding:3px 8px;">Dermaga B</button>' +
                '<button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" id="mapBtnDermagaC" onclick="switchLayout(\'dermaga_c\')" style="font-size:11px; padding:3px 8px;">Dermaga C</button>' +
                '</div>';
            L.DomEvent.disableClickPropagation(container);
            return container;
        }
    });
    new LayoutToggleControl().addTo(mymap);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(mymap);

    var imageWidth = 302;
    var imageHeight = 266;
    var denahBounds = [[-6.129426629182171, 106.85606981646343], [-6.07543762918217, 106.92555381646342]];
    var denahOverlay = L.imageOverlay(denahUrl, denahBounds, {
        opacity: 0.82,
        interactive: true
    }).addTo(mymap);
    mymap.setView([-6.102432, 106.890812], 14);


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

    function getStoredCoordinate(positionY, positionX) {
        var isGeographic = positionY < 0 && positionX > 90;

        return {
            mapPosition: isGeographic ? [positionY, positionX] : pixelToLatLng(positionY, positionX),
            latitude: isGeographic ? positionY : pixelToLatLng(positionY, positionX)[0],
            longitude: isGeographic ? positionX : pixelToLatLng(positionY, positionX)[1]
        };
    }

    mymap.on('click', function (event) {
        var x = Math.round(((event.latlng.lng - denahBounds[0][1]) / (denahBounds[1][1] - denahBounds[0][1])) * imageWidth);
        var y = Math.round(((denahBounds[1][0] - event.latlng.lat) / (denahBounds[1][0] - denahBounds[0][0])) * imageHeight);

        document.getElementById('map-coordinate').textContent = 'Koordinat denah: Y = ' + y + ', X = ' + x;
    });

    var markerLayer = L.layerGroup().addTo(mymap);
    var cctvMarkerLayer = L.layerGroup().addTo(mymap);
    var cctvSnapshotUrl = '<?= base_url('cctv/snapshot'); ?>';
    var cctvLiveTimers = {};

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

        var categoryKey = getCategoryKey(item);
        var markerColor = getMarkerColor(categoryKey);
        var foto = item.foto_alat ? '<?= base_url('img/alat'); ?>/' + item.foto_alat : '<?= base_url('img/alat/default.png'); ?>';
        var latitude = Number(item.latitude);
        var longitude = Number(item.longitude);
        var coordinate = getStoredCoordinate(positionY, positionX);
        var googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + coordinate.latitude + ',' + coordinate.longitude;

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
        var markerIcon = categoryKey === 'mbc' ? 'fa-truck-moving' :
            (categoryKey === 'rtg' ? 'fa-warehouse' :
            (categoryKey === 'ohc' ? 'fa-ship' : 'fa-boxes'));
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
    renderCctvMarkers();
    updateDenahSettingsInfo();

    var currentLayout = '<?= $activeLayout ?? "baso"; ?>';

    var layoutConfigs = {
        baso: {
            name: 'Layout BASO',
            center: [-6.102432, 106.890812],
            zoom: 14,
            bounds: [[-6.129426629182171, 106.85606981646343], [-6.07543762918217, 106.92555381646342]],
            imageWidth: 302,
            imageHeight: 266,
            imageUrl: '<?= base_url('img/denah/denah.png'); ?>'
        },
        kalijapat: {
            name: 'Layout Dermaga Kalijapat',
            center: [-6.1148, 106.8632],
            zoom: 16,
            bounds: [[-6.1265, 106.8540], [-6.1065, 106.8730]],
            imageWidth: 302,
            imageHeight: 266,
            imageUrl: '<?= base_url('img/denah/kalijapat.png'); ?>'
        },
        dermaga_a: {
            name: 'Layout Dermaga A',
            center: [-6.1085, 106.8785],
            zoom: 16,
            bounds: [[-6.1200, 106.8690], [-6.1000, 106.8880]],
            imageWidth: 302,
            imageHeight: 266,
            imageUrl: '<?= base_url('img/denah/kalijapat.png'); ?>'
        },
        dermaga_b: {
            name: 'Layout Dermaga B',
            center: [-6.1040, 106.8845],
            zoom: 16,
            bounds: [[-6.1155, 106.8750], [-6.0955, 106.8940]],
            imageWidth: 302,
            imageHeight: 266,
            imageUrl: '<?= base_url('img/denah/kalijapat.png'); ?>'
        },
        dermaga_c: {
            name: 'Layout Dermaga C',
            center: [-6.0995, 106.8910],
            zoom: 16,
            bounds: [[-6.1110, 106.8815], [-6.0910, 106.9005]],
            imageWidth: 302,
            imageHeight: 266,
            imageUrl: '<?= base_url('img/denah/kalijapat.png'); ?>'
        }
    };

    function switchLayout(layoutName) {
        if (!layoutConfigs[layoutName]) return;
        currentLayout = layoutName;
        var config = layoutConfigs[layoutName];

        mymap.flyTo(config.center, config.zoom, { duration: 1.2 });

        denahBounds = config.bounds;
        imageWidth = config.imageWidth;
        imageHeight = config.imageHeight;

        if (config.imageUrl && typeof denahOverlay.setUrl === 'function') {
            denahOverlay.setUrl(config.imageUrl);
        }
        denahOverlay.setBounds(denahBounds);
        resizeHandle.setLatLng(denahBounds[1]);

        syncDenahInputs();
        renderMarkers();
        updateDenahSettingsInfo();

        var layoutKeys = ['baso', 'kalijapat', 'dermaga_a', 'dermaga_b', 'dermaga_c'];
        var idSuffixes = {
            baso: 'Baso',
            kalijapat: 'Kalijapat',
            dermaga_a: 'DermagaA',
            dermaga_b: 'DermagaB',
            dermaga_c: 'DermagaC'
        };

        layoutKeys.forEach(function(k) {
            var s = idSuffixes[k];
            var btn = document.getElementById('btnLayout' + s);
            var mapBtn = document.getElementById('mapBtn' + s);
            if (btn) {
                btn.className = (k === layoutName) ? 'btn btn-sm btn-primary active' : 'btn btn-sm btn-outline-primary';
            }
            if (mapBtn) {
                mapBtn.className = (k === layoutName) ? 'btn btn-xs btn-primary font-weight-bold' : 'btn btn-xs btn-outline-primary font-weight-bold';
            }
        });
    }

    if (layoutConfigs[currentLayout]) {
        switchLayout(currentLayout);
    }
</script>
<?= $this->endSection(); ?>