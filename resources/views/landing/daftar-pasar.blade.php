@extends('layouts.app')

@section('title', 'Daftar Pasar - SIGAPAN Bantul')

@push('styles')
<style>
    .gradient-header {
        background: #064e3b; 
        position: relative;
        padding-top: 6rem; 
        padding-bottom: 3rem;
    }
    
    .header-title {
        font-weight: 800; 
        color: #ffffff;
        letter-spacing: -0.01em;
        line-height: 1.2;
    }

    .header-subtitle {
        color: #e2e8f0; 
        font-size: 1rem;
        margin-top: 0.75rem;
        max-width: 600px;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        color: #ffffff; 
        font-size: 0.875rem;
    }

    .breadcrumb-nav a {
        color: #ffffff;
        opacity: 0.9;
        text-decoration: none;
    }

    /* Mode Terang (Default) */
.hero-pattern {
    background-color: #f8fafc; /* Putih keabu-abuan seperti Home */
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(20, 184, 166, 0.05) 0%, transparent 50%),
        radial-gradient(rgba(15, 23, 42, 0.08) 1.5px, transparent 1.5px); /* Titik halus */
    background-size: 100% 100%, 100% 100%, 20px 20px;
    background-attachment: fixed;
}

/* Mode Gelap (Otomatis aktif saat klik toggle) */
.dark .hero-pattern {
    background-color: #0f172a !important; /* Navy Gelap */
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(20, 184, 166, 0.12) 0%, transparent 50%),
        radial-gradient(rgba(16, 185, 129, 0.1) 1.5px, transparent 1.5px) !important; /* Titik hijau transparan */
}

/* Keadaan normal card */
.pasar-card {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Animasi lebih halus dan membal */
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
}

/* Efek saat Cursor diarahkan ke Card (Timbul) */
.pasar-card:hover {
    transform: translateY(-15px); /* Card naik lebih tinggi */
    box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.25); /* Bayangan hijau emerald yang lembut */
    border-color: #10b981; /* Garis pinggir berubah jadi hijau */
    z-index: 10; /* Memastikan card yang naik berada di depan card lainnya */
}

/* Penyesuaian khusus Mode Gelap agar efek timbul tetap terlihat */
.dark .pasar-card:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 20px rgba(16, 185, 129, 0.2);
}

    /* Carousel styles */
    .carousel-container {
        position: relative;
        overflow: hidden;
    }
    .carousel-slide {
        display: none;
    }
    .carousel-slide.active {
        display: block;
    }
    .carousel-dots {
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
    }
    .carousel-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .carousel-dot.active {
        background: white;
        width: 20px;
        border-radius: 4px;
    }
    
    /* Carousel arrows */
    .carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 10;
    }
    .carousel-arrow:hover {
        background: white;
        transform: translateY(-50%) scale(1.1);
    }
    .carousel-arrow.prev { left: 8px; }
    .carousel-arrow.next { right: 8px; }
    .carousel-container:hover .carousel-arrow {
        opacity: 1;
    }

    /* Dark mode */
    .dark .bg-white { background-color: #111827; }
    .dark .text-gray-900 { color: #f9fafb; }
    .dark .text-gray-600 { color: #9ca3af; }
    .dark .text-gray-500 { color: #94a3b8; }
    .dark .border-gray-200 { border-color: #374151; }
    .dark .bg-gray-50 { background-color: #0f172a; }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <section class="gradient-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="breadcrumb-nav">
                <a href="{{ url('/sigapan') }}" class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Beranda
                </a>
                <span>&gt;</span>
                <span class="font-medium">Daftar Pasar</span>
            </nav>
            
            <h1 class="header-title text-3xl md:text-4xl lg:text-5xl">
                Daftar Pasar Rakyat <br class="hidden md:block">Kabupaten Bantul
            </h1>
            <p class="header-subtitle">
                Temukan informasi lengkap tentang pasar-pasar rakyat di Kabupaten Bantul.
            </p>
        </div>
    </section>

    <!-- Filter & Search Section -->
    <section class="py-8 bg-transparent hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search -->
                <div class="relative w-full max-w-2xl mx-auto">
    <div id="search-pasar-wrapper" class="flex items-center justify-center w-full bg-white border border-gray-200 rounded-full shadow-sm focus-within:ring-2 focus-within:ring-emerald-500 dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 h-12"
        style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        
        <div class="flex items-center justify-center w-full px-4">
            
            <svg id="search-icon" class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            
            <input type="text" 
                    id="search-pasar" 
                    placeholder="Cari nama pasar..." 
                    oninput="handleSearch(this)"
                    class="bg-transparent text-left border-none outline-none focus:ring-0 focus:outline-none py-2 pl-2 pr-2 text-sm text-gray-700 placeholder-gray-400 dark:text-white w-full shadow-none"
                    style="box-shadow: none !important; border: none !important; outline: none !important;">
        </div>
        
    </div>
    
    <!-- CSS for search input hover/focus -->
    <style>
        #search-pasar-wrapper:hover {
            border-color: #059669;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 0 12px rgba(5,150,105,0.15) !important;
        }
        
        #search-pasar-wrapper:focus-within {
            border-color: #059669;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 0 12px rgba(5,150,105,0.2) !important;
        }
        
        .dark #search-pasar-wrapper:hover {
            border-color: #10b981;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3), 0 0 12px rgba(16,185,129,0.2) !important;
        }
        
        .dark #search-pasar-wrapper:focus-within {
            border-color: #10b981;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3), 0 0 12px rgba(16,185,129,0.25) !important;
        }
        
        /* Filter select styling */
        #filter-kecamatan:hover {
            border-color: #059669;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), 0 0 12px rgba(5,150,105,0.15) !important;
        }
        
        #filter-kecamatan:focus {
            border-color: #059669;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), 0 0 12px rgba(5,150,105,0.2) !important;
            outline: none;
        }
        
        .dark #filter-kecamatan:hover {
            border-color: #10b981;
            box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 0 12px rgba(16,185,129,0.2) !important;
        }
        
        .dark #filter-kecamatan:focus {
            border-color: #10b981;
            box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 0 12px rgba(16,185,129,0.25) !important;
            outline: none;
        }
    </style>
</div>

<script>
function handleSearch(input) {
    const icon = document.getElementById('search-icon');
    
    // Hanya sembunyikan ikon, jangan ubah alignment teks
    if (input.value.length > 0) {
        icon.style.display = 'none';
    } else {
        icon.style.display = 'block';
    }

    // Jalankan fungsi filter
    if (typeof filterPasar === "function") {
        filterPasar();
    }
}
</script>
                
                <!-- Filter Kecamatan -->
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Filter:</span>
                    <select id="filter-kecamatan" onchange="filterPasar()" class="bg-white border border-gray-200 py-3 px-4 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-all duration-300">
                        <option value="all">Semua Kecamatan</option>
                        @foreach($kecamatanList as $kec)
                            <option value="{{ strtolower(str_replace(' ', '', $kec->nama)) }}">Kec. {{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Pasar Grid -->
    <section class="py-12 bg-transparent hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="pasar-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                {{-- Using real `$pasarList` from controller (Eloquent models) to avoid ID mismatches --}}

                @foreach($pasarList as $pasar)
                 <div class="pasar-card bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden dark:bg-gray-800 dark:border-gray-700" 
                     data-nama="{{ strtolower($pasar->nama_pasar ?? '') }}" 
                     data-kecamatan="{{ strtolower(str_replace(' ', '', $pasar->kecamatan?->nama ?? '')) }}">
                    
                    <!-- Image Carousel -->
                    <div class="carousel-container h-48 relative" data-current-slide="0">
                        @php
                            // Normalize images from `img_pasar` column.
                            // Accept formats:
                            // - JSON array (preferred)
                            // - plain comma-separated filenames: "a.jpg","b.jpg","c.jpg" or a.jpg,b.jpg,c.jpg
                            // - already public paths like images/pasar/a.jpg
                            $imgs = [];
                            $raw = $pasar->img_pasar ?? null;
                            if ($raw) {
                                if (is_array($raw)) {
                                    $imgs = $raw;
                                } else {
                                    $decoded = json_decode($raw, true);
                                    if (is_array($decoded)) {
                                        $imgs = $decoded;
                                    } else {
                                        // fallback: split by comma and trim quotes/spaces
                                        $parts = preg_split('/\s*,\s*/', trim($raw), -1, PREG_SPLIT_NO_EMPTY);
                                        $imgs = array_map(function($s) {
                                            return trim($s, "'\" \t\n\r\0\x0B");
                                        }, $parts);
                                    }
                                }
                            }
                        @endphp

                        @if(count($imgs) > 0)
                            @foreach($imgs as $i => $img)
                                @php
                                    if (preg_match('~^https?://~i', $img)) {
                                        $src = $img;
                                    } else {
                                        $clean = ltrim($img, '/');
                                        // If already a path with directory (contains '/'), use as-is
                                        if (strpos($clean, '/') !== false) {
                                            $src = asset($clean);
                                        } else {
                                            // Bare filename -> prefix with images/pasar/
                                            $src = asset('images/pasar/' . $clean);
                                        }
                                    }
                                @endphp
                                <div class="carousel-slide {{ $i === 0 ? 'active' : '' }}">
                                    <img src="{{ $src }}" alt="{{ $pasar->nama_pasar ?? '' }} {{ $i+1 }}" class="w-full h-48 object-cover">
                                </div>
                            @endforeach

                            <!-- Status Badge -->
                            @php $status = $pasar->status ?? 'buka'; @endphp
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $status === 'buka' ? 'bg-emerald-500' : 'bg-red-500' }} text-white text-xs font-bold shadow-lg">
                                <span class="w-2 h-2 bg-white rounded-full {{ $status === 'buka' ? 'animate-pulse' : '' }}"></span> {{ ucfirst($status) }}
                            </span>

                            <!-- Carousel Dots -->
                            <div class="carousel-dots">
                                @foreach($imgs as $i => $img)
                                    <span class="carousel-dot {{ $i === 0 ? 'active' : '' }}" onclick="slideCarousel(this, {{ $i }})"></span>
                                @endforeach
                            </div>

                            <!-- Carousel Arrows -->
                            <button class="carousel-arrow prev" onclick="prevSlide(this)">
                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button class="carousel-arrow next" onclick="nextSlide(this)">
                                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        @else
                            <div class="carousel-slide active">
                                <img src="{{ asset('images/pasar/default.png') }}" alt="{{ $pasar->nama_pasar ?? '' }}" class="w-full h-48 object-cover">
                            </div>
                            <div class="carousel-dots">
                                <span class="carousel-dot active"></span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $pasar->nama_pasar ?? '-' }}</h3>
                        
                        <div class="flex items-start gap-2 text-gray-600 dark:text-gray-400 mb-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm">{{ $pasar->alamat_lengkap ?? '-' }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 mb-4">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @php
                                $jamBuka = $pasar->jam_buka ?? null;
                                $jamTutup = $pasar->jam_tutup ?? null;
                                $jamBukaFormatted = $jamBuka ? substr($jamBuka,0,5) : '-';
                                $jamTutupFormatted = $jamTutup ? substr($jamTutup,0,5) : '-';
                            @endphp
                            <span class="text-sm font-medium">{{ $jamBukaFormatted }} - {{ $jamTutupFormatted }} WIB</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3" style="margin-top: 1rem;">
                            <a href="https://www.google.com/maps?q={{ $pasar->latitude ?? '' }},{{ $pasar->longitude ?? '' }}" 
                            target="_blank"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-emerald-500 rounded-xl text-emerald-600 text-sm font-bold hover:bg-emerald-50 transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Petunjuk
                            </a>

                            <button type="button" 
                                    onclick="showPasarDetail({{ $pasar->id }})" 
                                    style="background-color: #059669 !important;" 
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-white rounded-xl text-sm font-bold transition shadow-lg hover:bg-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Detail
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            
            <!-- Empty State -->
            <div id="empty-state" class="hidden py-16 text-center">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Pasar tidak ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-400">Coba ubah kata kunci atau filter pencarian</p>
            </div>
        </div>
    </section>

    <div id="pasar-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl transition-all">
            <div class="relative h-64">
                <img id="modal-img" src="" class="w-full h-full object-cover">
                <button onclick="closeModal()" class="absolute top-4 right-4 bg-black/20 hover:bg-black/40 text-white p-2 rounded-full backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-8">
                <h2 id="modal-nama" class="text-2xl font-bold text-gray-900 dark:text-white mb-4"></h2>
                <div class="space-y-4 text-gray-600 dark:text-gray-400">
                    <div class="flex gap-4">
                        <span class="text-emerald-600 font-bold">📍 Alamat:</span>
                        <p id="modal-alamat" class="text-sm"></p>
                    </div>
                    <div class="flex gap-4">
    <span class="text-emerald-600 font-bold">⏰ Jam Operasional:</span>
    <p id="modal-jam" class="text-sm font-medium"></p>
</div>
                </div>
                <button onclick="closeModal()" class="w-full mt-8 py-3 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Carousel functionality
    function slideCarousel(dot, index) {
        const container = dot.closest('.carousel-container');
        updateCarousel(container, index);
    }
    
    function updateCarousel(container, index) {
        const slides = container.querySelectorAll('.carousel-slide');
        const dots = container.querySelectorAll('.carousel-dot');
        
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === index);
        });
        container.dataset.currentSlide = index;
    }
    
    function prevSlide(btn) {
        const container = btn.closest('.carousel-container');
        const slides = container.querySelectorAll('.carousel-slide');
        let current = parseInt(container.dataset.currentSlide || 0);
        let newIndex = (current - 1 + slides.length) % slides.length;
        updateCarousel(container, newIndex);
    }
    
    function nextSlide(btn) {
        const container = btn.closest('.carousel-container');
        const slides = container.querySelectorAll('.carousel-slide');
        let current = parseInt(container.dataset.currentSlide || 0);
        let newIndex = (current + 1) % slides.length;
        updateCarousel(container, newIndex);
    }
    
    // Auto-slide carousels (use updateCarousel so dataset.currentSlide stays in sync)
    document.querySelectorAll('.carousel-container').forEach(container => {
        let currentSlide = 0;
        const slides = container.querySelectorAll('.carousel-slide');
        if (slides.length <= 1) return; // nothing to autoplay

        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousel(container, currentSlide);
        }, 4000);
    });
    
    // Filter functionality
    function filterPasar() {
        const searchText = document.getElementById('search-pasar').value.toLowerCase();
        const kecamatan = document.getElementById('filter-kecamatan').value;
        const cards = document.querySelectorAll('.pasar-card');
        const emptyState = document.getElementById('empty-state');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const nama = card.dataset.nama;
            const kec = card.dataset.kecamatan;
            
            const matchSearch = nama.includes(searchText);
            const matchKec = kecamatan === 'all' || kec === kecamatan;
            
            if (matchSearch && matchKec) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        emptyState.classList.toggle('hidden', visibleCount > 0);
    }
    
    // Show detail modal (placeholder)
    function showPasarDetail(id) {
        // Redirect to dedicated pasar detail page
        window.location.href = "{{ url('/pasar') }}/" + id;
    }
</script>
@endsection