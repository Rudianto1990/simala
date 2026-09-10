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
                        <span class="badge badge-pill" style="background:#d9534f; color:#fff; padding:6px 10px;">MBC</span>
                        <span class="badge badge-pill" style="background:#5cb85c; color:#fff; padding:6px 10px;">RTG</span>
                        <span class="badge badge-pill" style="background:#f0c419; color:#fff; padding:6px 10px;">OHC</span>
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
</style>
<script>
    var L = window.L;
    var centerLat = -6.2088;
    var centerLng = 106.8456;

    var alatData = <?= json_encode($data); ?>;
    var hasValidPoint = false;
    var bounds = L.latLngBounds([]);

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

    alatData.forEach(function (item) {
        if (item.latitude && item.longitude) {
            hasValidPoint = true;
            centerLat = Number(item.latitude);
            centerLng = Number(item.longitude);
            bounds.extend([Number(item.latitude), Number(item.longitude)]);
        }
    });

    var mymap = L.map('mapid', {
        zoomControl: false
    }).setView([centerLat, centerLng], hasValidPoint ? 10 : 5);

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
        var latitude = Number(item.latitude);
        var longitude = Number(item.longitude);
        var googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=' + latitude + ',' + longitude;

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

        L.marker([latitude, longitude], { icon: customIcon })
            .bindTooltip(assetLabel, {
                permanent: true,
                direction: 'top',
                offset: [0, -42],
                className: 'map-asset-label'
            })
            .bindPopup(popup)
            .addTo(mymap);
    });
</script>
<?= $this->endSection(); ?>