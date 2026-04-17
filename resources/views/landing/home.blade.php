@extends('layouts.app')

@section('title', 'SIGAPAN - Dinas KUKMPP Bantul')

@section('content')
    <!-- Hero Section - Full Viewport -->
    <section class="min-h-screen flex flex-col justify-center hero-pattern relative transition-colors duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-block py-1.5 px-4 rounded-full bg-white border border-emerald-100 text-emerald-700 text-s font-bold tracking-wider uppercase mb-6 shadow-sm">
            Sistem Informasi Harga Pangan
        </span>
        
        <h1 class="text-6xl md:text-8xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight tracking-tight">
            Selamat Datang di <span class="text-emerald-600 dark:text-emerald-500">SIGAPAN</span>
        </h1>
        
        <p class="text-gray-600 dark:text-white text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
            Pantau ketersediaan dan perkembangan harga kebutuhan pokok di Pasar Rakyat Kabupaten Bantul secara real-time, akurat, dan transparan.
        </p>

        <form id="search-form" class="max-w-2xl mx-auto relative group z-20">
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full blur opacity-25 group-hover:opacity-40 transition duration-500"></div>
            <div class="relative flex items-center justify-center bg-white dark:bg-gray-800 rounded-full shadow-2xl border border-gray-100 dark:border-gray-700">
                <div class="absolute left-6 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                id="search-input"
                type="text"
                class="block w-full h-16
                    bg-white dark:bg-gray-800
                    border-none focus:ring-0 rounded-full
                    text-gray-900 dark:text-white
                    placeholder-gray-500 dark:placeholder-gray-400
                    text-lg"
                style="padding-left: 3.5rem !important; padding-right: 7rem !important;"
                placeholder="Cari harga komoditas pangan...">
                <div class="absolute right-2 top-2 bottom-2">
                    <button type="submit" class="h-full bg-emerald-600 hover:bg-emerald-700 text-white px-8 rounded-full font-bold transition-all shadow-md" style="background-color: #059669; color: white;">
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- <div class="absolute bottom-0 left-0 w-full h-96 bg-gradient-to-t from-gray-50 dark:from-gray-900 to-transparent pointer-events-none"></div> -->
</section>

    <!-- Harga Harian Komoditas (Price Grid) -->
   <section id="harga-harian" class="py-16 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Harga Harian Komoditas</h2>
                <div class="h-1 w-20 bg-emerald-500 mx-auto rounded-full"></div>
                <p class="mt-4 text-gray-500 dark:text-white">Update harga rata-rata tingkat konsumen hari ini</p>
            </div>

            {{-- Komoditas diambil dari controller. Tampilkan nama pasar yang dipilih. --}}
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-300">Menampilkan komoditas: <span class="font-bold text-gray-900 dark:text-white">{{ is_object($selectedPasar) ? $selectedPasar->nama_pasar : ($selectedPasar ?? 'Pasar Bantul') }}</span></p>
            </div>

            <!-- Grid Container: 4 kolom -->
            <div id="komoditas-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($komoditas as $item)
                <div class="card-item bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-white hover:border-emerald-200 hover:-translate-y-1 transition duration-300 group">
                    
                    <div class="h-48 {{ $item['bgClass'] ?? 'bg-emerald-50' }} rounded-xl mb-5 flex items-center justify-center relative overflow-hidden">
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                        @else
                            <span class="text-6xl group-hover:scale-110 transition duration-300">{{ $item['icon'] ?? '📦' }}</span>
                        @endif
                        
                        <div class="absolute top-2.5 right-2.5 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200 shadow-sm border border-gray-200 dark:border-gray-600">
                            {{ $item['satuan'] }}
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-gray-900 dark:text-white text-base mb-3 group-hover:text-emerald-600 transition-colors">
                        {{ $item['nama'] }}
                    </h3>
                    
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Harga Rata-rata</p>
                            <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400" title="{{ $item['harga_display'] }}">{{ $item['harga_singkat'] }}</p>
                        </div>
                        
                        <div>
                            @if($item['trend'] === 'down')
                            <div class="tooltip-wrapper relative">
                                <span class="inline-flex items-center gap-1 text-red-600 text-sm font-bold bg-red-100 px-3 py-1.5 rounded-md border border-red-200 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    {{ $item['persen_display'] }}
                                </span>
                                <!-- Tooltip -->
                                <div class="tooltip-content">
                                    <div class="bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                                        Turun {{ $item['selisih_display'] }}
                                    </div>
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </div>
                            @elseif($item['trend'] === 'up')
                            <div class="tooltip-wrapper relative">
                                <span class="inline-flex items-center gap-1 text-emerald-700 text-sm font-bold bg-emerald-100 px-3 py-1.5 rounded-md border border-emerald-200 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    {{ $item['persen_display'] }}
                                </span>
                                <!-- Tooltip -->
                                <div class="tooltip-content">
                                    <div class="bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                                        Naik {{ $item['selisih_display'] }}
                                    </div>
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </div>
                            @else
                            <div class="tooltip-wrapper relative">
                                <span class="inline-flex items-center gap-1 text-gray-700 dark:text-gray-200 text-sm font-bold bg-gray-200 dark:bg-gray-600 px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-500 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    {{ $item['persen_display'] }}
                                </span>
                                <!-- Tooltip -->
                                <div class="tooltip-content">
                                    <div class="bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap">
                                        Harga stabil ({{ $item['selisih_display'] }})
                                    </div>
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <div id="no-results" class="hidden text-center py-16">
                <div class="text-6xl mb-4">🔍</div>
                <p class="text-xl font-bold text-gray-700 mb-2">Komoditas tidak ditemukan</p>
                <button onclick="resetSearch()" class="text-emerald-600 hover:text-emerald-700 font-semibold underline underline-offset-2">Lihat semua komoditas</button>
            </div>

            <div class="mt-12 text-center">
                <button id="show-all-btn" class="inline-flex items-center justify-center px-8 py-3.5 border border-emerald-600 text-base font-bold rounded-full text-emerald-600 bg-white hover:bg-emerald-50 transition shadow-sm hover:shadow-md">
                    Lihat Semua Komoditas
                </button>
            </div>
        </div>
    </section>

    <!-- Peta Sebaran Pasar -->
    <section class="py-16 peta-section dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
                <div>
                    <span class="inline-block bg-white text-emerald-600 text-sm font-bold uppercase tracking-wider mb-2 px-4 py-1 rounded-full">Lokasi Pantauan</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Peta Sebaran Pasar</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">Lokasi pasar rakyat di Kabupaten Bantul</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Leaflet Map Container -->
                <div class="lg:col-span-2 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 dark:bg-gradient-to-br dark:from-emerald-900/20 dark:via-teal-900/20 dark:to-cyan-900/20 rounded-2xl relative overflow-hidden border border-emerald-100 dark:border-emerald-800 shadow-lg" style="height:600px; min-height:600px;">
                    <div id="leaflet-map" class="w-full h-full rounded-2xl z-10"></div>
                    <!-- Floating Center Badge overlay -->
                    <div class="absolute left-6 bottom-6 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 shadow-lg border border-gray-100 dark:border-gray-700 flex items-center gap-3 z-20" style="pointer-events:auto;">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white mb-0">{{ $semuaPasar->count() }} Pasar</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-0">Terdaftar di Sistem</p>
                        </div>
                    </div>
                    <!-- Legend -->
                    <div class="absolute right-6 top-6 bg-white dark:bg-gray-800 rounded-xl px-4 py-3 shadow-lg border border-gray-100 dark:border-gray-700 z-20 text-xs">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-gray-700 dark:text-gray-300">Pasar Pantauan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: #f59e0b;"></span>
                            <span class="text-gray-700 dark:text-gray-300">Pasar Lainnya</span>
                        </div>
                    </div>
                </div>

                <!-- Market List (Scrollable) -->
                <div class="space-y-3 overflow-y-auto pr-2 custom-scrollbar" style="max-height: 600px;">
                    @foreach($semuaPasar as $pasar)
                    @php
                        $isPantauan = $pasar->wajib_pantau || $pasar->is_monitored;
                        $bgClass = $isPantauan ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-100 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-950/30 border-amber-100 dark:border-amber-800';
                        $iconBg = $isPantauan ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600' : 'bg-amber-100 dark:bg-amber-900/50 text-amber-600';
                        $statusText = $isPantauan ? 'Pasar Pantauan' : 'Pasar Rakyat';
                        $statusColor = $isPantauan ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400';
                        $dotStyle = $isPantauan ? 'background-color: #10b981;' : 'background-color: #f59e0b;';
                        $komoditasCount = $isPantauan ? 'Komoditas dipantau' : 'Info Pasar';
                    @endphp
                    <div class="card-hover {{ $bgClass }} rounded-xl p-4 border shadow-sm cursor-pointer transition-all hover:shadow-md" onclick="focusMap({{ $pasar->latitude }}, {{ $pasar->longitude }})">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 {{ $iconBg }} rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 truncate text-sm">{{ $pasar->nama_pasar }}</h4>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/50 border border-gray-100 dark:border-gray-600 text-xs font-medium shrink-0 {{ $statusColor }}">
                                        <span class="w-1.5 h-1.5 rounded-full" style="{{ $dotStyle }}"></span>
                                        {{ $statusText }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1 truncate">{{ $pasar->alamat_lengkap ?? 'Alamat belum tersedia' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; }
            /* Fix Leaflet divIcon default styling */
            .custom-div-icon {
                background: none !important;
                border: none !important;
            }
        </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
    // Global function to focus map
    window.focusMap = function(lat, lng) {
        if (!lat || !lng) return;
        const mapContainer = document.getElementById('leaflet-map');
        if (mapContainer && mapContainer._leaflet_map) {
             mapContainer._leaflet_map.flyTo([lat, lng], 15);
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const semuaPasar = @json($semuaPasar);

        function initLeafletMap() {
            if (typeof L === 'undefined') {
                setTimeout(initLeafletMap, 200);
                return;
            }
            
            if (document.getElementById('leaflet-map')) {
                try {
                    let centerLat = -7.888063, centerLng = 110.328797;
                    if (semuaPasar.length > 0) {
                        const validPasar = semuaPasar.filter(p => p.latitude && p.longitude);
                        if (validPasar.length > 0) {
                             const avgLat = validPasar.reduce((sum, p) => sum + parseFloat(p.latitude), 0) / validPasar.length;
                             const avgLng = validPasar.reduce((sum, p) => sum + parseFloat(p.longitude), 0) / validPasar.length;
                             centerLat = avgLat;
                             centerLng = avgLng;
                        }
                    }

                    const map = L.map('leaflet-map', {
                        center: [centerLat, centerLng],
                        zoom: 12,
                        scrollWheelZoom: false,
                        zoomControl: true,
                    });
                    
                    document.getElementById('leaflet-map')._leaflet_map = map;

                    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors',
                    });
                    osm.addTo(map);

                    // Custom Icons using SVG to avoid external image issues
                    const pantauanIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: #10b981; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" style="width: 18px; height: 18px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                               </div>
                               <div style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 8px solid transparent; border-right: 8px solid transparent; border-top: 10px solid #10b981;"></div>`,
                        iconSize: [30, 42],
                        iconAnchor: [15, 42],
                        popupAnchor: [0, -40]
                    });

                    const otherIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: #f59e0b; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" style="width: 14px; height: 14px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                               </div>
                               <div style="position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-top: 8px solid #f59e0b;"></div>`,
                        iconSize: [24, 34],
                        iconAnchor: [12, 34],
                        popupAnchor: [0, -32]
                    });

                    semuaPasar.forEach(pasar => {
                        if (pasar.latitude && pasar.longitude) {
                            const isPantauan = pasar.wajib_pantau || pasar.is_monitored;
                            const marker = L.marker(
                                [parseFloat(pasar.latitude), parseFloat(pasar.longitude)], 
                                { icon: isPantauan ? pantauanIcon : otherIcon }
                            ).addTo(map);

                            const statusLabel = isPantauan 
                                ? '<span style="color:#059669; font-weight:bold;">Pasar Pantauan</span>' 
                                : '<span style="color:#d97706; font-weight:bold;">Pasar Rakyat</span>';
                            
                            marker.bindPopup(`
                                <div style="text-align:center;">
                                    <h3>${pasar.nama_pasar}</h3>
                                    <p>${pasar.alamat_lengkap ?? ''}</p>
                                    ${statusLabel}
                                </div>
                            `);
                        }
                    });
                } catch (e) {
                    console.error('Leaflet map error:', e);
                }
            }
        }
        initLeafletMap();
    });
    </script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const cards = Array.from(document.querySelectorAll('.card-item'));
            const noResultsDiv = document.getElementById('no-results');
            const gridContainer = document.getElementById('komoditas-grid');
            const showAllBtn = document.getElementById('show-all-btn');

            const MAX_INITIAL = 8;
            let expanded = false;

            function applyInitialLimit() {
                if (cards.length > MAX_INITIAL) {
                    cards.forEach((card, idx) => {
                        if (idx >= MAX_INITIAL) {
                            card.style.display = 'none';
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.95)';
                        } else {
                            card.style.display = '';
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }
                    });
                    if (showAllBtn) showAllBtn.style.display = '';
                } else {
                    if (showAllBtn) showAllBtn.style.display = 'none';
                }
            }

            applyInitialLimit();

            function performSearch() {
                const searchText = searchInput.value.toLowerCase().trim();
                let found = false;

                cards.forEach((card, idx) => {
                    const name = card.querySelector('h3').innerText.toLowerCase();

                    if (name.includes(searchText)) {
                        card.style.display = "";
                        card.style.opacity = "1";
                        card.style.transform = "scale(1)";
                        found = true;
                    } else {
                        // When no search text, respect the initial limit unless expanded
                        if (searchText === "") {
                            if (!expanded && idx >= MAX_INITIAL) {
                                card.style.opacity = "0";
                                card.style.transform = "scale(0.95)";
                                setTimeout(() => {
                                    if (!expanded && !card.querySelector('h3').innerText.toLowerCase().includes(searchInput.value.toLowerCase().trim())) {
                                        card.style.display = "none";
                                    }
                                }, 200);
                            } else {
                                card.style.display = "";
                                card.style.opacity = "1";
                                card.style.transform = "scale(1)";
                            }
                        } else {
                            card.style.opacity = "0";
                            card.style.transform = "scale(0.95)";
                            setTimeout(() => {
                                if (!name.includes(searchText)) {
                                    card.style.display = "none";
                                }
                            }, 200);
                        }
                    }
                });

                if (found || searchText === "") {
                    noResultsDiv.classList.add('hidden');
                    gridContainer.classList.remove('hidden');
                } else {
                    noResultsDiv.classList.remove('hidden');
                    gridContainer.classList.add('hidden');
                }
            }

            // Jalankan saat submit
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
                document.getElementById('harga-harian').scrollIntoView({ behavior: 'smooth' });
            });

            // Real-time search
            searchInput.addEventListener('input', performSearch);

            if (showAllBtn) {
                showAllBtn.addEventListener('click', function() {
                    expanded = true;
                    cards.forEach(card => {
                        card.style.display = "";
                        card.style.opacity = "1";
                        card.style.transform = "scale(1)";
                    });
                    showAllBtn.style.display = 'none';
                });
            }
        });

        function resetSearch() {
            const input = document.getElementById('search-input');
            input.value = '';
            const cards = Array.from(document.querySelectorAll('.card-item'));
            const noResultsDiv = document.getElementById('no-results');
            const gridContainer = document.getElementById('komoditas-grid');
            const showAllBtn = document.getElementById('show-all-btn');
            const MAX_INITIAL = 8;

            cards.forEach((card, idx) => {
                if (idx >= MAX_INITIAL) {
                    card.style.display = 'none';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                } else {
                    card.style.display = '';
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }
            });

            if (showAllBtn) showAllBtn.style.display = '';
            noResultsDiv.classList.add('hidden');
            gridContainer.classList.remove('hidden');
            input.focus();
        }
    </script>

    <style>
        /* Leaflet map custom style */
        #leaflet-map {
            min-height: 450px;
            height: 100%;
            width: 100%;
            border-radius: 1rem;
            z-index: 10;
        }
        .leaflet-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background: #fff;
            color: #222;
        }
        /* Removed dark mode override to keep popup white with dark text for better visibility/contrast */
        .leaflet-marker-icon {
            filter: drop-shadow(0 2px 8px rgba(16,185,129,0.15));
        }
        .dark .leaflet-marker-icon {
            filter: drop-shadow(0 2px 8px rgba(16,185,129,0.25));
        }
        /* Tooltip CSS - Pure CSS hover effect */
        .tooltip-wrapper {
            position: relative;
        }
        .tooltip-content {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            pointer-events: none;
            z-index: 50;
        }
        .tooltip-wrapper:hover .tooltip-content {
            opacity: 1;
            visibility: visible;
        }

        /* ============ TEXTURE BACKGROUNDS ============ */

        /* Hero Section - Visible Dot Pattern + Gradient */
        .hero-pattern {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(20, 184, 166, 0.08) 0%, transparent 50%),
                radial-gradient(rgba(16, 185, 129, 0.15) 1.5px, transparent 1.5px);
            background-size: 100% 100%, 100% 100%, 20px 20px;
            background-position: 0 0, 0 0, 0 0;
        }

        .dark .hero-pattern {
            background-color: #0f172a;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(20, 184, 166, 0.12) 0%, transparent 50%),
                radial-gradient(rgba(16, 185, 129, 0.2) 1.5px, transparent 1.5px);
            background-size: 100% 100%, 100% 100%, 20px 20px;
        }

        /* Harga Harian Section - Grid Pattern */
        #harga-harian {
            background-color: #f9fafb;
            background-image: 
                linear-gradient(rgba(16, 185, 129, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16, 185, 129, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .dark #harga-harian {
            background-color: #0f172a;
            background-image: 
                linear-gradient(rgba(16, 185, 129, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16, 185, 129, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Peta Sebaran Section - Wave Pattern */
        .peta-section {
            background-color: #ffffff;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='100' height='20' viewBox='0 0 100 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M21.184 20c.357-.13.72-.264 1.088-.402l1.768-.661C33.64 15.347 39.647 14 50 14c10.271 0 15.362 1.222 24.629 4.928.955.383 1.869.74 2.75 1.072h6.225c-2.51-.73-5.139-1.691-8.233-2.928C65.888 13.278 60.562 12 50 12c-10.626 0-16.855 1.397-26.66 5.063l-1.767.662c-2.475.923-4.66 1.674-6.724 2.275h6.335zm0-20C13.258 2.892 8.077 4 0 4V2c5.557 0 9.84-.395 14.473-1.368 2.12-.447 3.922-.95 5.47-1.537 1.01-.384 1.902-.758 2.869-1.095h4.005c-1.87.664-3.54 1.24-5.093 1.737C19.273 .628 17.01 1.16 14.477 1.632 9.937 2.569 5.677 3 0 3v1c5.557 0 9.842-.394 14.477-1.368 2.12-.447 3.842-.927 5.273-1.37L21.184 0zM0 18c5.556 0 9.84-.394 14.473-1.368 2.12-.447 3.922-.95 5.47-1.537 1.01-.384 1.902-.758 2.869-1.095l3.868-1.5C35.76 9.54 41.647 8 50 8c8.353 0 14.24 1.54 23.32 4.5-.77-.302-1.506-.59-2.212-.872C62.098 8.25 56.915 7 50 7c-9.074 0-15.378 1.573-24.629 5.072l-3.768 1.44c-1.064.405-2.092.783-3.188 1.12-1.464.447-3.063.857-4.847 1.218C8.937 16.605 4.67 17 0 17v1zm0-16c5.556 0 9.84-.395 14.473-1.368 2.12-.447 3.922-.95 5.47-1.537 1.01-.384 1.902-.758 2.869-1.095L27.518.5C36.598-2.46 42.485-4 50-4c6.915 0 12.098 1.25 21.108 4.628a73.687 73.687 0 0 0 2.212.872C64.24-1.46 58.353-3 50-3c-8.353 0-14.24 1.54-23.32 4.5l-3.868 1.5c-.967.337-1.86.71-2.869 1.095-1.548.588-3.35 1.09-5.47 1.537C9.842 6.605 5.557 7 0 7V6c4.67 0 8.937-.395 13.568-1.15 1.784-.361 3.383-.77 4.847-1.218 1.096-.337 2.124-.715 3.188-1.12l3.768-1.44C34.622 1.573 40.926 0 50 0c6.915 0 12.098 1.25 21.108 4.628.706.282 1.442.57 2.212.872C64.24.46 58.353-1 50-1c-8.353 0-14.24 1.54-23.32 4.5L21.184 2z' fill='%2310b981' fill-opacity='0.02' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .dark .peta-section {
            background-color: #0f172a;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='100' height='20' viewBox='0 0 100 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M21.184 20c.357-.13.72-.264 1.088-.402l1.768-.661C33.64 15.347 39.647 14 50 14c10.271 0 15.362 1.222 24.629 4.928.955.383 1.869.74 2.75 1.072h6.225c-2.51-.73-5.139-1.691-8.233-2.928C65.888 13.278 60.562 12 50 12c-10.626 0-16.855 1.397-26.66 5.063l-1.767.662c-2.475.923-4.66 1.674-6.724 2.275h6.335zm0-20C13.258 2.892 8.077 4 0 4V2c5.557 0 9.84-.395 14.473-1.368 2.12-.447 3.922-.95 5.47-1.537 1.01-.384 1.902-.758 2.869-1.095h4.005c-1.87.664-3.54 1.24-5.093 1.737C19.273 .628 17.01 1.16 14.477 1.632 9.937 2.569 5.677 3 0 3v1c5.557 0 9.842-.394 14.477-1.368 2.12-.447 3.842-.927 5.273-1.37L21.184 0zM0 18c5.556 0 9.84-.394 14.473-1.368 2.12-.447 3.922-.95 5.47-1.537 1.01-.384 1.902-.758 2.869-1.095l3.868-1.5C35.76 9.54 41.647 8 50 8c8.353 0 14.24 1.54 23.32 4.5-.77-.302-1.506-.59-2.212-.872C62.098 8.25 56.915 7 50 7c-9.074 0-15.378 1.573-24.629 5.072l-3.768 1.44c-1.064.405-2.092.783-3.188 1.12-1.464.447-3.063.857-4.847 1.218C8.937 16.605 4.67 17 0 17v1zm0-16c5.556 0 9.84-.395 14.473-1.368 2.12-.447 3.922-.95 5.47-1.537 1.01-.384 1.902-.758 2.869-1.095L27.518.5C36.598-2.46 42.485-4 50-4c6.915 0 12.098 1.25 21.108 4.628a73.687 73.687 0 0 0 2.212.872C64.24-1.46 58.353-3 50-3c-8.353 0-14.24 1.54-23.32 4.5l-3.868 1.5c-.967.337-1.86.71-2.869 1.095-1.548.588-3.35 1.09-5.47 1.537C9.842 6.605 5.557 7 0 7V6c4.67 0 8.937-.395 13.568-1.15 1.784-.361 3.383-.77 4.847-1.218 1.096-.337 2.124-.715 3.188-1.12l3.768-1.44C34.622 1.573 40.926 0 50 0c6.915 0 12.098 1.25 21.108 4.628.706.282 1.442.57 2.212.872C64.24.46 58.353-1 50-1c-8.353 0-14.24 1.54-23.32 4.5L21.184 2z' fill='%2310b981' fill-opacity='0.04' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Decorative floating circles - subtle accent */
        .hero-pattern::before {
            content: '';
            position: absolute;
            top: 10%;
            right: 5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-pattern::after {
            content: '';
            position: absolute;
            bottom: 15%;
            left: 5%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .dark .hero-pattern::before {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
        }

        .dark .hero-pattern::after {
            background: radial-gradient(circle, rgba(20, 184, 166, 0.12) 0%, transparent 70%);
        }

        /* Card hover effect enhancement */
        .card-item {
            transition: all 0.3s ease;
        }

        .card-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(16, 185, 129, 0.15);
        }
    /* Leaflet Popup Content Styling */
        .leaflet-popup-content h3 {
            margin: 0;
            font-weight: bold;
            font-size: 14px;
            color: #111827; /* gray-900 */
        }
        /* Keep h3 dark in dark mode for contrast against white background (if we force white bg) 
           OR if we keep dark bg, we need light text. 
           User asked for "dark gray" color. This implies user wants light background.
           So I will NOT add .dark override for color here. 
        */
        
        .leaflet-popup-content p {
            margin: 4px 0 8px 0;
            font-size: 11px;
            color: #4b5563; /* gray-600 */
        }

    </style>
@endsection