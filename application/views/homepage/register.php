<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #map {
            height: 400px;
            border-radius: 0.5rem; 
        }

        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .autocomplete-dropdown {
            position: absolute;
            z-index: 1000;
            width: 100%;
            background: white;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .autocomplete-item {
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }

        .shipping-option label {
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .shipping-option input:checked+label {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .shipping-option input {
            display: none;
        }

        .checkout-card {
            background-color: #fff;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .sticky-top-custom {
            position: sticky;
            top: 1rem;
            z-index: 1000;
        }

        .product-image img,
        .product-image .placeholder {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .location-info {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .location-info p {
            margin-bottom: 0.5rem;
        }

        .location-info i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
            color: #6c757d;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 10px;
        }

        .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Custom Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: white;
            margin: 2rem auto;
            width: 90%;
            max-width: 800px;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .custom-modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .custom-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6c757d;
        }

        .custom-modal-body {
            padding: 1.5rem;
        }

        .custom-modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .custom-modal-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: 500;
        }

        .custom-modal-btn-primary {
            background-color: #0d6efd;
            color: white;
            border: 1px solid #0d6efd;
        }

        .custom-modal-btn-secondary {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
        }

        .custom-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="mb-4">
            <h2 class="fw-bold">Checkout</h2>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form id="checkout-form" action="<?= site_url('checkout/proses') ?>" method="post">
            <div class="row g-4">
                <!-- Shipping Address -->
                <div class="col-lg-7">
                    <div class="checkout-card">
        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="nama_penerima" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="nama_penerima" name="nama_penerima"
                                    value="<?= set_value('nama_penerima', $pembeli->nama_pembeli ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label">No. HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    value="<?= set_value('no_hp', $pembeli->no_hp ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="alamat" name="alamat"
                                    value="<?= set_value('alamat', $pembeli->alamat ?? '') ?>"
                                    placeholder="Ketik alamat atau cari di peta..." required>
                                <button type="button" class="btn btn-outline-primary" id="open-map-modal">
                                    <i class="fas fa-map-marker-alt"></i> Pilih di Peta
                                </button>
                            </div>
                            <div id="autocomplete-results" class="autocomplete-dropdown d-none"></div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <select class="form-select select2-provinsi" id="provinsi" name="provinsi" required>
                                    <option value="">Pilih Provinsi</option>
                                    <?php foreach ($provinsi_list as $prov): ?>
                                        <option value="<?= $prov['province_id'] ?>"
                                            data-province-name="<?= $prov['province'] ?>"><?= $prov['province'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                            </div>
                            <div class="col-md-6">
                                <label for="kota" class="form-label">Kota/Kabupaten</label>
                                <select class="form-select select2-kota" id="kota" name="kota" required disabled>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                <input type="hidden" name="kota_nama" id="kota_nama">
                            </div>
                        </div>
                        <div>
                            <label for="kode_pos" class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos"
                                value="<?= set_value('kode_pos') ?>" required>
                        </div>
                    </div>

                </div>


    <!-- Custom Map Modal -->
    <div id="custom-map-modal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Pilih Lokasi di Peta</h5>
                <button class="custom-modal-close">×</button>
            </div>
            <div class="custom-modal-body">
                <div id="map"></div>
                <div class="mt-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="search-address" placeholder="Cari alamat...">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div id="search-results" class="autocomplete-dropdown d-none mt-2"></div>
                    <div id="search-loading" class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="location-details" class="location-info d-none">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle"></i>Detail Lokasi</h6>
                    <p id="selected-address"><i class="fas fa-map-marker-alt"></i><span>Alamat belum dipilih</span></p>
                    <p id="selected-coordinates"><i class="fas fa-globe-asia"></i><span>Koordinat: -</span></p>
                    <p id="selected-area"><i class="fas fa-map"></i><span>Daerah: -</span></p>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-modal-btn custom-modal-btn-secondary"
                    id="close-map-modal">Tutup</button>
                <button type="button" class="custom-modal-btn custom-modal-btn-primary" id="select-location" disabled>
                    <i class="fas fa-check"></i> Pilih Lokasi Ini
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2-provinsi').select2({ placeholder: "Pilih Provinsi", allowClear: true });
            $('.select2-kota').select2({ placeholder: "Pilih Kota/Kabupaten", allowClear: true });

            // Cache for API responses
            const cache = { cities: {}, services: {} };

            // Pre-fetch initial cities if needed
            const defaultProvinceId = $('#provinsi').val() || <?= json_encode($provinsi_list[0]['province_id'] ?? 'null') ?>;
            if (defaultProvinceId) {
                fetchCities(defaultProvinceId);
            }

            // Initialize map variables
            let map, marker;

            // Custom Modal Functions
            function openCustomModal() {
                $('#custom-map-modal').fadeIn();
                $('body').css('overflow', 'hidden');

                // Initialize map if not already done
                if (!map) {
                    initMap();
                } else {
                    // Refresh map size when modal is opened
                    setTimeout(() => map.invalidateSize(), 0);
                }
            }

            function closeCustomModal() {
                $('#custom-map-modal').fadeOut();
                $('body').css('overflow', 'auto');
            }

            // Event handlers for custom modal
            $('#open-map-modal').click(openCustomModal);
            $('.custom-modal-close, #close-map-modal').click(closeCustomModal);

            // Close modal when clicking outside
            $(document).mouseup(function (e) {
                const modal = $('#custom-map-modal');
                if (!modal.is(e.target) && modal.has(e.target).length === 0) {
                    closeCustomModal();
                }
            });

            // Initialize the map
            function initMap() {
                map = L.map('map').setView([-2.548926, 118.0148634], 5); // Center of Indonesia
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Handler untuk klik pada peta
                map.on('click', function (e) {
                    const latlng = e.latlng;
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(latlng).addTo(map);
                    reverseGeocode(latlng.lat, latlng.lng);
                });

                // Add geocoder control
                L.Control.geocoder({
                    defaultMarkGeocode: false,
                    placeholder: 'Cari alamat...',
                    errorMessage: 'Alamat tidak ditemukan',
                    showResultIcons: true,
                    collapsed: false
                })
                    .on('markgeocode', function (e) {
                        const { center, name } = e.geocode;
                        map.setView(center, 16);
                        if (marker) map.removeLayer(marker);
                        marker = L.marker(center).addTo(map)
                            .bindPopup(name)
                            .openPopup();
                        updateLocationDetails(center.lat, center.lng, { display_name: name, address: e.geocode.properties.address });
                    })
                    .addTo(map);
            }

            // Fungsi untuk mengupdate detail lokasi di dalam modal
            function updateLocationDetails(lat, lng, addressData) {
                $('#selected-address span').text(addressData.display_name || 'Alamat tidak ditemukan');
                $('#selected-coordinates span').text(`Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`);

                let areaInfo = 'Informasi daerah tidak tersedia';
                if (addressData.address) {
                    const adr = addressData.address;
                    areaInfo = [adr.road, adr.village, adr.suburb, adr.city_district, adr.city, adr.state, adr.postcode].filter(Boolean).join(', ');
                }
                $('#selected-area span').text(areaInfo);
                $('#location-details').removeClass('d-none');

                if (marker) {
                    marker.bindPopup(addressData.display_name).openPopup();
                }

                $('#select-location').prop('disabled', false); // Aktifkan tombol pilih lokasi
            }

            // Fungsi pencarian alamat menggunakan Nominatim
            function performAddressSearch() {
                const query = $('#search-address').val();
                const resultsContainer = $('#search-results');
                const loadingSpinner = $('#search-loading');

                if (query.length < 3) {
                    resultsContainer.empty().addClass('d-none');
                    return;
                }

                loadingSpinner.removeClass('d-none');
                resultsContainer.empty().addClass('d-none');

                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/search',
                    data: {
                        q: query,
                        format: 'json',
                        addressdetails: 1,
                        limit: 5,
                        countrycodes: 'id',
                        viewbox: '95.31644,-11.20883,141.04686,6.27470',
                        bounded: 1,
                        'accept-language': 'id'
                    },
                    success: function (data) {
                        loadingSpinner.addClass('d-none');
                        resultsContainer.removeClass('d-none');
                        if (!data || data.length === 0) {
                            resultsContainer.append('<div class="autocomplete-item text-muted">Tidak ada hasil ditemukan</div>');
                            return;
                        }
                        data.forEach(item => {
                            const resultItem = $(`<div class="autocomplete-item">${item.display_name}</div>`);
                            resultItem.click(() => {
                                resultsContainer.empty().addClass('d-none');
                                const latlng = [parseFloat(item.lat), parseFloat(item.lon)];
                                map.setView(latlng, 16);
                                if (marker) map.removeLayer(marker);
                                marker = L.marker(latlng).addTo(map);
                                updateLocationDetails(latlng[0], latlng[1], item);
                                autoFillAddressForm(item);
                            });
                            resultsContainer.append(resultItem);
                        });
                    },
                    error: function () {
                        loadingSpinner.addClass('d-none');
                        resultsContainer.removeClass('d-none').append('<div class="autocomplete-item text-danger">Gagal mencari alamat</div>');
                    }
                });
            }

            // Fungsi Reverse Geocoding (dari koordinat ke alamat)
            function reverseGeocode(lat, lng) {
                $('#search-loading').removeClass('d-none');
                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/reverse',
                    data: { lat, lon: lng, format: 'json', addressdetails: 1, 'accept-language': 'id' },
                    success: function (data) {
                        $('#search-loading').addClass('d-none');
                        if (data) {
                            updateLocationDetails(lat, lng, data);
                            autoFillAddressForm(data);
                        }
                    },
                    error: function () {
                        $('#search-loading').addClass('d-none');
                        $('#selected-address span').text('Gagal mendapatkan detail alamat.');
                    }
                });
            }

            $('#search-button').click(performAddressSearch);
            $('#search-address').keypress(e => e.which === 13 ? performAddressSearch() : null);

            // Fungsi untuk mengisi otomatis form alamat utama
            function autoFillAddressForm(data) {
                if (!data || !data.address) return;

                const addr = data.address;
                const provinsiName = addr.state;

                // Isi provinsi jika ditemukan
                if (provinsiName) {
                    const provinsiOption = $(`#provinsi option`).filter(function () {
                        return $(this).text().toLowerCase() === provinsiName.toLowerCase();
                    });

                    if (provinsiOption.length) {
                        $('#provinsi').val(provinsiOption.val()).trigger('change');
                        $('#provinsi_nama').val(provinsiOption.text());

                        // Tunggu sebentar agar kota ter-load
                        setTimeout(() => {
                            const kotaName = addr.city || addr.county || addr.town || addr.village;
                            if (kotaName) {
                                // Cari opsi kota yang cocok (case insensitive)
                                const kotaOptions = $('#kota option');
                                let found = false;

                                kotaOptions.each(function () {
                                    const optionText = $(this).text().toLowerCase();
                                    if (optionText.includes(kotaName.toLowerCase())) {
                                        $('#kota').val($(this).val()).trigger('change');
                                        $('#kota_nama').val($(this).text());
                                        found = true;
                                        return false; // keluar dari loop
                                    }
                                });

                                // Jika tidak ditemukan exact match, coba partial match
                                if (!found) {
                                    kotaOptions.each(function () {
                                        const optionText = $(this).text().toLowerCase();
                                        const keywords = kotaName.toLowerCase().split(' ');

                                        if (keywords.some(kw => optionText.includes(kw))) {
                                            $('#kota').val($(this).val()).trigger('change');
                                            $('#kota_nama').val($(this).text());
                                            found = true;
                                            return false; // keluar dari loop
                                        }
                                    });
                                }
                            }
                        }, 1000); // Penundaan untuk fetch city
                    }
                }

                // Isi kode pos jika ditemukan
                if (addr.postcode) {
                    $('#kode_pos').val(addr.postcode);
                } else {
                    // Jika tidak ada kode pos dari API, coba ambil dari dropdown kota
                    setTimeout(() => {
                        const postalCode = $('#kota option:selected').data('postal-code');
                        if (postalCode) {
                            $('#kode_pos').val(postalCode);
                        }
                    }, 1500);
                }
            }

            // Tombol "Pilih Lokasi Ini"
            $('#select-location').click(function () {
                if (marker) {
                    $('#alamat').val($('#selected-address span').text());
                    closeCustomModal();
                }
            });

            // Reset modal when closed
            $('#custom-map-modal').on('hidden.bs.modal', function () {
                // Reset semua input dan hasil pencarian di dalam modal.
                $('#search-address').val('');
                $('#search-results').empty().addClass('d-none');
                $('#location-details').addClass('d-none');
                $('#select-location').prop('disabled', true);
                $('#search-loading').addClass('d-none');
            });

            // LOGIC PENGIRIMAN (PROVINSI, KOTA, ONGKIR)

            // Ketika provinsi berubah
            $('#provinsi').on('change', function () {
                const provinsiId = this.value;
                const provinsiNama = $(this).find('option:selected').data('province-name') || '';
                $('#provinsi_nama').val(provinsiNama);

                // Clear address when province changes
                $('#alamat').val('');

                resetAllShipping();
                $('#kota').html('<option value="">Pilih Kota/Kabupaten</option>').prop('disabled', true).trigger('change');
                if (provinsiId) {
                    fetchCities(provinsiId);
                }
            });
            function getRandomAddressWithFallback(cityName, provinceName) {
                // Show loading
                $('#alamat').val('Mencari alamat...');

                // Try multiple search strategies for better results
                const searchQueries = [
                    `jalan ${cityName}`, // Search for streets in the city
                    `${cityName} center`, // Search for city center
                    `${cityName} ${provinceName}`, // General city search
                    cityName // Fallback to just city name
                ];

                function trySearch(queryIndex = 0) {
                    if (queryIndex >= searchQueries.length) {
                        // All searches failed, use simple fallback
                        $('#alamat').val(`${cityName}, ${provinceName}`);
                        return;
                    }

                    $.ajax({
                        url: 'https://nominatim.openstreetmap.org/search',
                        data: {
                            q: searchQueries[queryIndex] + ', Indonesia',
                            format: 'json',
                            addressdetails: 1,
                            limit: 15,
                            countrycodes: 'id',
                            'accept-language': 'id'
                        },
                        success: function (data) {
                            if (data && data.length > 0) {
                                // Filter results to make sure they're in the right city/province
                                const filteredData = data.filter(item => {
                                    const address = item.address || {};
                                    const itemCity = address.city || address.town || address.county || '';
                                    const itemProvince = address.state || '';

                                    return itemCity.toLowerCase().includes(cityName.toLowerCase().split(' ').pop()) ||
                                        itemProvince.toLowerCase().includes(provinceName.toLowerCase());
                                });

                                if (filteredData.length > 0) {
                                    const randomIndex = Math.floor(Math.random() * filteredData.length);
                                    const randomAddress = filteredData[randomIndex];
                                    $('#alamat').val(randomAddress.display_name);

                                    // Update map if available
                                    updateMapMarker(randomAddress);
                                } else {
                                    // Try next search query
                                    trySearch(queryIndex + 1);
                                }
                            } else {
                                // Try next search query
                                trySearch(queryIndex + 1);
                            }
                        },
                        error: function () {
                            // Try next search query
                            trySearch(queryIndex + 1);
                        }
                    });
                }

                trySearch();
            }
            function updateMapMarker(addressData) {
                if (typeof map !== 'undefined' && map && addressData) {
                    const lat = parseFloat(addressData.lat);
                    const lng = parseFloat(addressData.lon);

                    // Remove existing marker
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Add new marker
                    marker = L.marker([lat, lng]).addTo(map);

                    // Only center map if modal is open
                    if ($('#custom-map-modal').is(':visible')) {
                        map.setView([lat, lng], 14);
                        marker.bindPopup(addressData.display_name).openPopup();
                    }
                }
            }

            // Fungsi untuk mengambil data kota dari server
            function fetchCities(provinsiId) {
                const kotaSelect = $('#kota');
                kotaSelect.html('<option value="">Memuat...</option>').prop('disabled', true);

                if (cache.cities[provinsiId]) {
                    populateCities(cache.cities[provinsiId]);
                } else {
                    $.ajax({
                        url: `<?= site_url('checkout/get_kota/') ?>${provinsiId}`,
                        dataType: 'json',
                        success: function (data) {
                            cache.cities[provinsiId] = data;
                            populateCities(data);
                        },
                        error: function () {
                            kotaSelect.html('<option value="">Gagal memuat</option>');
                        }
                    });
                }
            }

            function populateCities(data) {
                const kotaSelect = $('#kota');
                let options = '<option value="">Pilih Kota/Kabupaten</option>';

                if (data && Array.isArray(data) && data.length > 0) {
                    data.forEach(kota => {
                        const cityName = kota.type ? `${kota.type} ${kota.city_name}` : kota.city_name;
                        options += `
                        <option value="${kota.city_id}" 
                                data-city-name="${cityName}" 
                                data-postal-code="${kota.postal_code}">
                            ${cityName}
                        </option>`;
                    });
                    kotaSelect.html(options).prop('disabled', false);
                } else if (data.rajaongkir && data.rajaongkir.results) {
                    data.rajaongkir.results.forEach(kota => {
                        const cityName = `${kota.type} ${kota.city_name}`;
                        options += `
                        <option value="${kota.city_id}" 
                                data-city-name="${cityName}" 
                                data-postal-code="${kota.postal_code}">
                            ${cityName}
                        </option>`;
                    });
                    kotaSelect.html(options).prop('disabled', false);
                } else {
                    kotaSelect.html('<option value="">Tidak ada kota tersedia</option>');
                }
                kotaSelect.trigger('change');
            }

            // Ketika kota berubah
            $('#kota').on('change', function () {
                const kotaId = this.value;
                const kotaName = $(this).find('option:selected').data('city-name') || '';
                const postalCode = $(this).find('option:selected').data('postal-code') || '';
                const provinceName = $('#provinsi option:selected').data('province-name') || '';

                $('#kota_nama').val(kotaName);
                if (postalCode) $('#kode_pos').val(postalCode);

                // NEW: Auto-fill address when city changes
                if (kotaId && kotaName && provinceName) {
                    getRandomAddressForCity(kotaName, provinceName);
                } else {
                    $('#alamat').val(''); // Clear if no city selected
                }

                resetAllShipping(); // Reset setiap kali kota berubah
                if (kotaId) {
                    // Memicu perhitungan ongkir jika kurir sudah dipilih
                    $('.store-section').each(function () {
                        const storeName = $(this).data('store');
                        const courierRadio = $(`input[name="kurir[${storeName}]"]:checked`);
                        if (courierRadio.length > 0) {
                            courierRadio.trigger('change');
                        }
                    });
                }
            });

            // Ketika kurir dipilih
            $('input[name^="kurir"]').on('change', function () {
                const courier = this.value;
                const storeName = $(this).data('store');
                const weight = $(`.store-section[data-store="${storeName}"]`).data('weight');
                const destinationCityId = $('#kota').val();
                const originCityId = $(`.store-section[data-store="${storeName}"]`).data('origin-city');

                if (destinationCityId) {
                    loadShippingServices(originCityId, destinationCityId, courier, storeName, weight);
                } else {
                    // Mungkin tampilkan notifikasi untuk memilih kota dulu
                    const layananSelect = $(`.layanan-select[data-store="${storeName}"]`);
                    layananSelect.html('<option value="">Pilih kota tujuan dulu</option>').prop('disabled', true);
                }
            });

            // Fungsi untuk memuat layanan pengiriman
            function loadShippingServices(originCityId, destinationCityId, courier, storeName, weight) {
                const layananSelect = $(`.layanan-select[data-store="${storeName}"]`);
                layananSelect.html('<option value="">Memuat layanan...</option>').prop('disabled', true);
                resetShippingCostForStore(storeName);

                $.ajax({
                    url: `<?= site_url('checkout/get_layanan/') ?>${originCityId}/${destinationCityId}/${weight}/${courier}`,
                    dataType: 'json',
                    success: function (data) {
                        if (data.error) {
                            layananSelect.html(`<option value="">${data.error}</option>`);
                            showShippingError(storeName, data.error);
                        } else if (data.length > 0) {
                            let options = '<option value="">Pilih Layanan</option>';
                            data.forEach(service => {
                                const etd = service.etd ? ` (${service.etd} hari)` : '';
                                const costFormatted = service.cost ? ` - Rp ${service.cost.toLocaleString('id-ID')}` : '';
                                options += `
                                <option value="${service.service}" 
                                        data-cost="${service.cost}" 
                                        data-etd="${service.etd}">
                                    ${service.description}${etd}${costFormatted}
                                </option>`;
                            });
                            layananSelect.html(options).prop('disabled', false);
                        } else {
                            layananSelect.html('<option value="">Tidak ada layanan tersedia</option>');
                            showShippingError(storeName, 'Tidak ada layanan tersedia untuk kurir ini');
                        }
                    },
                    error: function (xhr) {
                        const errorMsg = xhr.responseJSON?.error || 'Gagal memuat layanan';
                        layananSelect.html(`<option value="">${errorMsg}</option>`);
                        showShippingError(storeName, errorMsg);
                        console.error('Error loading services:', xhr.responseText);
                    }
                });
            }

            function showShippingError(storeName, message) {
                $(`.shipping-error[data-store="${storeName}"]`).html(`
                <div class="alert alert-danger mt-2">
                    <i class="fas fa-exclamation-circle"></i> ${message}
                </div>
            `);
            }
            function getRandomAddressForCity(cityName, provinceName) {
                // Show loading indicator
                $('#alamat').val('Mencari alamat...');

                // Create search query for the selected city
                const searchQuery = `${cityName}, ${provinceName}, Indonesia`;

                $.ajax({
                    url: 'https://nominatim.openstreetmap.org/search',
                    data: {
                        q: searchQuery,
                        format: 'json',
                        addressdetails: 1,
                        limit: 20, // Get more results to have variety
                        countrycodes: 'id',
                        'accept-language': 'id'
                    },
                    success: function (data) {
                        if (data && data.length > 0) {
                            // Get a random result from the returned addresses
                            const randomIndex = Math.floor(Math.random() * data.length);
                            const randomAddress = data[randomIndex];

                            // Update the address field with the random address
                            $('#alamat').val(randomAddress.display_name);

                            // Optionally update the map marker if map is visible
                            if (typeof map !== 'undefined' && map) {
                                const lat = parseFloat(randomAddress.lat);
                                const lng = parseFloat(randomAddress.lon);

                                // Remove existing marker
                                if (marker) {
                                    map.removeLayer(marker);
                                }

                                // Add new marker
                                marker = L.marker([lat, lng]).addTo(map);

                                // Center map on the new location (only if modal is open)
                                if ($('#custom-map-modal').is(':visible')) {
                                    map.setView([lat, lng], 14);
                                    marker.bindPopup(randomAddress.display_name).openPopup();
                                }
                            }
                        } else {
                            // Fallback if no specific address found
                            $('#alamat').val(`${cityName}, ${provinceName}`);
                        }
                    },
                    error: function () {
                        // Fallback on error
                        $('#alamat').val(`${cityName}, ${provinceName}`);
                    }
                });
            }

            function populateServices(data, storeName) {
                const layananSelect = $(`.layanan-select[data-store="${storeName}"]`);
                let options = '<option value="">Pilih Layanan</option>';

                // Fix for potential error - check data structure before accessing
                if (data && data.rajaongkir && data.rajaongkir.results && data.rajaongkir.results[0] && data.rajaongkir.results[0].costs) {
                    $.each(data.rajaongkir.results[0].costs, function (_, service) {
                        const cost = service.cost[0].value;
                        const etd = service.cost[0].etd.replace(' HARI', '');
                        options += `<option value="${service.service}" data-cost="${cost}">${service.description} (${etd} hari) - Rp ${cost.toLocaleString('id-ID')}</option>`;
                    });
                    layananSelect.prop('disabled', false);
                } else {
                    options = '<option value="">Tidak ada layanan</option>';
                }
                layananSelect.html(options).trigger('change');
            }

            // Ketika layanan dipilih
            $(document).on('change', '.layanan-select', function () {
                const store = $(this).data('store');
                const selectedOption = $(this).find('option:selected');
                const ongkir = selectedOption.data('cost') || 0;

                $(`.ongkir-value[name="ongkir[${store}]"]`).val(ongkir);
                $(`.ongkir-text[data-store="${store}"]`).text(`Rp ${parseInt(ongkir).toLocaleString('id-ID')}`);
                updateGrandTotal();
            });

            // Fungsi untuk update total keseluruhan
            function updateGrandTotal() {
                let totalOngkir = 0;
                $('.ongkir-value').each(function () {
                    totalOngkir += parseInt($(this).val()) || 0;
                });
                $('#total-ongkir').text(`Rp ${totalOngkir.toLocaleString('id-ID')}`);
                const subtotal = <?= $grand_total ?>;
                const grandTotal = subtotal + totalOngkir;
                $('#grand-total').text(`Rp ${grandTotal.toLocaleString('id-ID')}`);
            }

            // Fungsi untuk mereset semua pilihan pengiriman
            function resetAllShipping() {
                $('.store-section').each(function () {
                    resetShippingForStore($(this).data('store'));
                });
            }

            function resetShippingForStore(storeName) {
                const storeSection = $(`.store-section[data-store="${storeName}"]`);
                storeSection.find('.layanan-select').html('<option value="">Pilih Kurir & Kota Tujuan Dulu</option>').prop('disabled', true);
                resetShippingCostForStore(storeName);
            }

            function resetShippingCostForStore(storeName) {
                const storeSection = $(`.store-section[data-store="${storeName}"]`);
                storeSection.find('.ongkir-text').text('Rp 0');
                storeSection.find('.ongkir-value').val(0);
                updateGrandTotal();
            }

            // Initialize form with validation errors
            const provinsiValue = "<?= set_value('provinsi') ?>";
            const kotaValue = "<?= set_value('kota') ?>";
            if (provinsiValue) {
                $('#provinsi').val(provinsiValue).trigger('change');
                if (kotaValue) {
                    setTimeout(() => $('#kota').val(kotaValue).trigger('change'), 500);
                }
            }
        });
    </script>
</body>

</html>