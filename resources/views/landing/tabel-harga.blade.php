@extends('layouts.app')

@section('title', 'Perbandingan Harga - SIGAPAN Bantul')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flowbite-datepicker@1.3.0/dist/css/datepicker.min.css">
<style>
    /* 1. HEADER & GLOBAL */
    .gradient-header {
        background: #064e3b; 
        position: relative;
        padding-top: 6rem; 
        padding-bottom: 3rem;
    }
    
    .header-title { font-weight: 800; color: #ffffff !important; letter-spacing: -0.01em; line-height: 1.2; }
    .header-subtitle { color: #e2e8f0 !important; font-size: 1rem; margin-top: 0.75rem; max-width: 600px; }

    .breadcrumb-nav { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; color: #ffffff; font-size: 0.875rem; }
    .breadcrumb-nav a { color: #ffffff; opacity: 0.9; text-decoration: none; }

    /* 2. CUSTOM SELECT & INPUT FIX */
    .custom-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .custom-select-wrapper:hover { border-color: #059669; box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1); }
    
    .custom-select-wrapper select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: transparent;
        border: none;
        padding: 0.6rem 2.5rem 0.6rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        outline: none;
        width: 100%;
        min-width: 160px;
    }

    .custom-select-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none; /* Klik tembus ke select */
        color: #94a3b8;
    }

    /* 3. BUTTON SEARCH FIX */
    .btn-search-fix {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        background-color: #047857;
        color: white;
        border-radius: 9999px;
        transition: all 0.2s;
        cursor: pointer !important;
        border: none;
        flex-shrink: 0;
        z-index: 20;
    }

    .btn-search-fix:hover { background-color: #065f46; transform: scale(1.05); }
    .btn-search-fix:active { transform: scale(0.95); }

    /* 4. DARK MODE FIXES */
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
        background-color: #020617;
        background-image: 
            radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.12) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(20, 184, 166, 0.12) 0%, transparent 50%),
            radial-gradient(rgba(16, 185, 129, 0.08) 1.5px, transparent 1.5px);
        background-size: 100% 100%, 100% 100%, 20px 20px;
    }

    .dark .custom-select-wrapper {
        background-color: #0f172a; /* slate-900 */
        border-color: #334155; /* slate-700 */
    }
    .dark .custom-select-wrapper select {
        color: #f8fafc;
    }
    .dark .dropdown-trigger {
        background: #0f172a;
        border-color: #334155;
        color: #f8fafc;
    }
    .dark .bg-white { background-color: #020617 !important; border-color: #1e293b !important; }
    .dark .text-gray-900 { color: #f8fafc !important; }
    .dark .text-gray-600, .dark .text-gray-500 { color: #cbd5e1 !important; }
    
    /* Table Dark Mode */
    .dark table { border: 1px solid #1e293b; }
    .dark thead tr { background: #111827; }
    .dark tbody tr:hover { background-color: rgba(16, 185, 129, 0.05); }

    .datepicker { z-index: 2000 !important; }

    /* === DARK MODE DROPDOWN (LEBIH TERANG) === */
.dark #dropdown-options {
    background: #0f172a; /* slate-900 */
    border: 1px solid #334155; /* slate-700 */
    border-radius: 0.75rem;
    padding: 0.25rem;
    margin-top: 0.5rem;
    box-shadow:
        0 12px 30px rgba(0,0,0,0.45),
        inset 0 1px 0 rgba(255,255,255,0.05);
}

.dark #dropdown-options a {
    color: #e5e7eb;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.dark #dropdown-options a:hover {
    background: #1e293b; /* slate-800 */
    color: #ffffff;
}


/* === DARK MODE SELECT (PASAR & KOMODITAS) === */
.dark .custom-select-wrapper {
    background: #0f172a; /* slate-900 */
    border: 1px solid #334155; /* slate-700 */
    border-radius: 0.75rem;
}

.dark .custom-select-wrapper select {
    color: #e5e7eb;
}

.dark .custom-select-wrapper:hover {
    border-color: #10b981; /* emerald */
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}

.dark .custom-select-icon {
    color: #94a3b8;
}

/* ===== CUSTOM DROPDOWN ===== */
.custom-dropdown {
    width: 180px;
    z-index: 30;
}

/* trigger */
.dropdown-trigger {
    width: 100%;
    min-height: 42px;
    padding: 0.6rem 1rem;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.25s ease;
}

.dropdown-trigger:hover {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
}

/* arrow */
.dropdown-arrow {
    transition: transform 0.25s ease;
}

/* menu */
.custom-dropdown {
    position: relative;
    width: 180px;
    z-index: 999;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    display: none;
    z-index: 1000; /* TAMBAHKAN */
}


/* item */
.dropdown-menu button {
    width: 100%;
    text-align: left;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
    color: #374151;
    transition: background 0.2s;
}

.dropdown-menu button:hover {
    background: #f1f5f9;
}

/* open state */
.custom-dropdown.open .dropdown-menu {
    display: block;
}

.custom-dropdown.open .dropdown-arrow {
    transform: rotate(180deg);
}

/* ===== DARK MODE – BLACK DASHBOARD STYLE ===== */
.dark .dropdown-trigger {
    background: #020617;    /* slate-950 */
    border-color: #1e293b;
    color: #f8fafc;
}

.dark .dropdown-menu {
    background: #020617;
    border: 1px solid #1e293b;
    box-shadow: 0 20px 40px rgba(0,0,0,0.7);
}

.dark .dropdown-menu button {
    color: #e5e7eb;
}

.dark .dropdown-menu button:hover {
    background: rgba(255, 255, 255, 0.12);
}

/* ===== LIGHT MODE – NAVBAR STYLE DROPDOWN ===== */
.dropdown-trigger {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    color: #0f172a;
}

.dropdown-trigger:hover {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
}

.dropdown-menu {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 0.25rem;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.dropdown-menu button {
    color: #0f172a;
    border-radius: 0.5rem;
    font-weight: 500;
}

/* 🌿 HOVER HIJAU MUDA (KAYA NAVBAR) */
.dropdown-menu button:hover {
    background: #d1fae5;   /* emerald-100 */
    color: #065f46;
}

/* ===== LIGHT MODE – SAVE AS (FLOWBITE) ===== */
#dropdown-options {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    padding: 0.25rem;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

#dropdown-options a {
    color: #0f172a;
    border-radius: 0.5rem;
    font-weight: 500;
}

/* 🌿 HOVER HIJAU MUDA (SAMA KAYA NAVBAR) */
#dropdown-options a:hover {
    background: #d1fae5;   /* emerald-100 */
    color: #065f46;
}

.custom-dropdown {
    position: relative;   /* PALING PENTING */
    width: 180px;
    z-index: 999;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.custom-dropdown.open .dropdown-menu {
    display: block;
}

/* Pastikan tombol download tidak tertutup elemen lain */
.btn-download {
    cursor: pointer !important;
    position: relative;
    z-index: 10;
}

/* Dropdown filter z-index lebih rendah agar tidak di atas navbar */
.custom-dropdown {
    position: relative;
    z-index: 10;
}
.dropdown-menu {
    position: absolute;
    z-index: 100;
}

/* Fix modal agar selalu di paling depan */
#pdf-preview-modal {
    z-index: 99999 !important;
}

/* Beri pointer agar user tahu itu bisa diklik */
.btn-download:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* ===== MOBILE FILTER RESPONSIVE ===== */
@media (max-width: 768px) {
    .filter-form-wrapper {
        flex-direction: column;
        align-items: stretch !important;
        gap: 0.75rem !important;
    }

    .filter-form-wrapper .custom-dropdown {
        width: 100% !important;
    }

    .filter-form-wrapper .dropdown-trigger {
        width: 100%;
    }

    .filter-form-wrapper .dropdown-menu {
        width: 100%;
    }

    .filter-date-group {
        flex-direction: row !important;
        gap: 0.5rem;
        width: 100%;
    }

    .filter-date-group .relative {
        width: 100% !important;
        flex: 1;
    }

    .filter-date-group .relative input {
        width: 100% !important;
    }

    .filter-search-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
    }

    .filter-actions-wrapper {
        width: 100%;
        justify-content: center !important;
        margin-left: 0 !important;
        margin-top: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .filter-actions-wrapper .btn-download {
        width: 100%;
        justify-content: center;
    }

    .custom-select-wrapper select {
        min-width: unset !important;
    }
}

</style>
@endpush

@section('content')
    <section class="gradient-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="breadcrumb-nav">
                <a href="{{ url('/') }}" class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Beranda
                </a>
                <span>&gt;</span>
                <span class="font-medium">Tabel Harga</span>
            </nav>
            <h1 class="header-title text-3xl md:text-4xl lg:text-5xl">Tabel Harga Pasar<br class="hidden md:block">Kabupaten Bantul</h1>
            <p class="header-subtitle">Informasi lengkap harga dan ketersediaan stok bahan pokok di seluruh pasar rakyat Kabupaten Bantul.</p>
        </div>
    </section>
    <section class="py-12 hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                <div class="w-full">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 dark:text-white">Tabel Harga Komoditas</h2>
                    <div class="flex flex-col gap-3 w-full">
                        <!-- FILTER PASAR -->
                        <form id="filter-form" method="GET" action="{{ route('tabel-harga.index') }}" class="filter-form-wrapper flex flex-wrap items-center gap-3">
                            <!-- hidden inputs pasar & komoditas -->
                            <input type="hidden" name="pasar" id="filter-pasar" value="{{ $pasarAktifId }}">
                            <input type="hidden" name="komoditas" id="filter-komoditas" value="{{ $komoditasFilterId }}">
                            
                            <!-- hidden inputs tanggal -->
                            <input type="hidden" name="start" id="filter-start" value="{{ request('start') }}">
                            <input type="hidden" name="end" id="filter-end" value="{{ request('end') }}">

                            <!-- Dropdown Pasar -->
                            <div class="custom-dropdown" data-input="filter-pasar">
                                <button type="button" class="dropdown-trigger">
                                    <span class="dropdown-label">Pilih Pasar</span>
                                    <svg class="w-4 h-4 ml-2 dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    @foreach ($pasarList as $pasar)
                                        <button type="button" data-value="{{ $pasar->id }}">{{ $pasar->nama_pasar }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Dropdown Komoditas -->
                            <div class="custom-dropdown inline-block relative" data-input="filter-komoditas">
                                <button type="button" class="dropdown-trigger flex items-center justify-between whitespace-nowrap px-4 py-2 border border-gray-300 rounded-lg">
                                    <span class="dropdown-label">Semua Komoditas</span>
                                    <svg class="w-4 h-4 ml-2 dropdown-arrow transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div class="dropdown-menu hidden absolute mt-1 left-0 bg-white border border-gray-300 rounded-lg shadow-md min-w-full">
                                    <button type="button" data-value="all" class="block w-full text-left px-4 py-2 whitespace-nowrap hover:bg-gray-100">
                                        Semua Komoditas
                                    </button>
                                    @foreach ($komoditasList as $komoditas)
                                        <button type="button" data-value="{{ $komoditas->id }}" class="block w-full text-left px-4 py-2 whitespace-nowrap hover:bg-gray-100">
                                            {{ $komoditas->nama_komoditas }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Date Range Picker + Search Button -->
                            <div class="filter-search-row flex items-center gap-2">
                                <div class="filter-date-group flex gap-2">
                                    <div class="relative w-48">
                                        <input id="datepicker-start" type="text" readonly
                                            class="block w-full ps-10 pe-3 py-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-xl cursor-pointer dark:bg-slate-900 dark:border-slate-700 dark:text-white"
                                            placeholder="Tanggal Mulai"
                                            value="{{ $start->format('Y-m-d') }}">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="relative w-48">
                                        <input id="datepicker-end" type="text" readonly
                                            class="block w-full ps-10 pe-3 py-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-xl cursor-pointer dark:bg-slate-900 dark:border-slate-700 dark:text-white"
                                            placeholder="Tanggal Akhir"
                                            value="{{ $end->format('Y-m-d') }}">
                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tombol Search -->
                                <button type="submit" class="btn-search-fix flex-shrink-0" title="Cari">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                        <div class="filter-actions-wrapper flex items-center gap-2 ms-auto relative z-20">
                            <button type="button" 
                                class="btn-download inline-flex items-center text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl text-sm px-4 py-2.5 focus:outline-none dark:bg-slate-900 dark:text-white dark:border-slate-700">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-visible relative">
                <div class="w-full overflow-x-auto rounded-t-2xl">
                    <table class="w-full border-collapse">
                        <thead class="rounded-t-2xl">
                            <tr class="bg-emerald-50 dark:bg-emerald-900 border-b-4 border-emerald-700 dark:border-emerald-500">
                                <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 dark:text-white uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 dark:text-white uppercase tracking-wider">Komoditas</th>

                                @foreach($tanggalList as $tanggal)
                                    <th class="px-4 py-2 text-center text-xs font-bold text-emerald-800 dark:text-white uppercase tracking-wider">
                                        {{ $tanggal->format('d M') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($komoditasData as $item)
                            <tr class="hover:bg-emerald-50/40 dark:hover:bg-emerald-900/20 transition-colors">
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-200 font-medium">{{ $item['no'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item['nama'] }}</span>
                                        @if(!empty($item['satuan']))
                                            <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ $item['satuan'] }}</span>
                                        @endif
                                    </div>
                                </td>
                                <!-- Harga per tanggal -->
                                @foreach($tanggalList as $tanggal)
                                    @php
                                        $key = $tanggal->format('Y-m-d');
                                        $hargaString = $item['harga'][$key] ?? '-';
                                        $hargaNumeric = isset($item['harga'][$key]) && $item['harga'][$key] !== '-' 
                                            ? (int) str_replace(['Rp', '.', ' '], '', $item['harga'][$key]) 
                                            : null;
                                        $trendData = $item['trend'][$key] ?? null;
                                        $trend = $trendData['trend'] ?? 'stabil';
                                        $selisih = $trendData['selisih'] ?? 0;
                                    @endphp
                                    <td class="px-4 py-2 text-center">
                                        @if($hargaNumeric)
                                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded-lg whitespace-nowrap
                                                @if($hargaNumeric == $item['max']) bg-red-100 dark:bg-red-900/30
                                                @elseif($hargaNumeric == $item['min']) bg-emerald-100 dark:bg-emerald-900/30
                                                @elseif($trend == 'naik') bg-red-50 dark:bg-red-900/20
                                                @elseif($trend == 'turun') bg-emerald-50 dark:bg-emerald-900/20
                                                @else bg-gray-50 dark:bg-gray-800 @endif"
                                                title="@if($trend == 'naik')Naik Rp{{ number_format(abs($selisih), 0, ',', '.') }}@elseif($trend == 'turun')Turun Rp{{ number_format(abs($selisih), 0, ',', '.') }}@else Stabil @endif">
                                                @if($trend == 'naik')
                                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                                @elseif($trend == 'turun')
                                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                                @endif
                                                <span class="font-semibold text-[13px]
                                                    @if($hargaNumeric == $item['max']) text-red-600 dark:text-red-400
                                                    @elseif($hargaNumeric == $item['min']) text-emerald-600 dark:text-emerald-400
                                                    @elseif($trend == 'naik') text-red-600 dark:text-red-400
                                                    @elseif($trend == 'turun') text-emerald-600 dark:text-emerald-400
                                                    @else text-gray-700 dark:text-gray-300 @endif">
                                                    {{ $hargaString }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite-datepicker@1.3.0/dist/js/datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. LOGIK DROPDOWN (Pasar & Komoditas)
    const dropdowns = document.querySelectorAll('.custom-dropdown');
    dropdowns.forEach(dd => {
        const trigger = dd.querySelector('.dropdown-trigger');
        const menu = dd.querySelector('.dropdown-menu');
        const label = dd.querySelector('.dropdown-label');
        const inputId = dd.dataset.input;
        const hiddenInput = inputId ? document.getElementById(inputId) : null;

        trigger.addEventListener('click', e => {
            e.stopPropagation();
            dropdowns.forEach(d => {
                if(d !== dd) {
                    d.classList.remove('open');
                    d.querySelector('.dropdown-menu').classList.add('hidden');
                }
            });
            dd.classList.toggle('open');
            menu.classList.toggle('hidden');
        });

        menu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                label.textContent = btn.textContent.trim();
                if(hiddenInput) hiddenInput.value = btn.dataset.value;
                dd.classList.remove('open');
                menu.classList.add('hidden');
            });
        });

        if(hiddenInput && hiddenInput.value) {
            const selectedBtn = menu.querySelector(`button[data-value="${hiddenInput.value}"]`);
            if(selectedBtn) label.textContent = selectedBtn.textContent.trim();
        }
    });

    document.addEventListener('click', () => {
        dropdowns.forEach(dd => {
            dd.classList.remove('open');
            dd.querySelector('.dropdown-menu').classList.add('hidden');
        });
    });

    // 2. LOGIK DATEPICKER
    const startInput = document.getElementById('datepicker-start');
    const endInput = document.getElementById('datepicker-end');
    const startHidden = document.getElementById('filter-start');
    const endHidden = document.getElementById('filter-end');

    if (startInput && endInput) {
        const startPicker = new Datepicker(startInput, { format: 'yyyy-mm-dd', autohide: true });
        const endPicker = new Datepicker(endInput, { format: 'yyyy-mm-dd', autohide: true });

        // update hidden input setiap tanggal dipilih
        startInput.addEventListener('changeDate', (e) => {
            startHidden.value = startInput.value;
        });

        endInput.addEventListener('changeDate', (e) => {
            endHidden.value = endInput.value;
        });
    }


    // 3. LOGIK DOWNLOAD PDF
    const downloadBtn = document.querySelector('.btn-download');

    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const pasar = document.getElementById('filter-pasar').value;
            const komoditas = document.getElementById('filter-komoditas').value;
            const start = document.getElementById('filter-start').value;
            const end = document.getElementById('filter-end').value;

            const params = new URLSearchParams({ pasar, komoditas, start, end }).toString();

            // Pindah ke halaman preview
            window.location.href = `/tabel-harga/preview?${params}`;
        });
    }
});
</script>


@endpush