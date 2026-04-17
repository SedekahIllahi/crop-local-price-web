@extends('layouts.app')

@section('title', 'Perbandingan Harga - SIGAPAN Bantul')

@push('styles')
<style>
    /* 1. PERBAIKAN HEADER (Warna Emerald Gelap & Ukuran Pas) */
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

    /* Memastikan Breadcrumb terlihat jelas */
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

    /* 2. MERAPIKAN DROPDOWN (MENGHILANGKAN DOUBLE ARROW/SHADOW) */
    .custom-select-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .custom-select-wrapper:hover {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .custom-select-wrapper:has(select:focus) {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    
    .custom-select-wrapper select {
        /* PENTING: Menghapus panah bawaan browser agar tidak ganda */
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        
        background: transparent;
        border: none;
        padding: 0.5rem 2.5rem 0.5rem 1rem; /* Ruang kanan untuk ikon kustom */
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        outline: none;
        min-width: 180px;
    }

    .custom-select-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%); /* Presisi vertikal di tengah */
        pointer-events: none; /* Klik tembus ke select */
        display: flex;
        color: #94a3b8;
    }

    /* Menghilangkan shadow pada SVG */
    .custom-select-icon svg {
        filter: none;
    }

    /* Search Input Styling */
    #search-komoditas {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    #search-komoditas:hover {
        border-color: #059669 !important;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
    }

    #search-komoditas:focus {
        border-color: #059669 !important;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1) !important;
    }

    /* ================= DARK MODE SIGAPAN ================= */

    /* Hero Pattern Background */
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

    /* Transisi halus saat pindah mode */
    section, div, table, tr, td, input, select {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    /* Card, container, table wrapper */
    .dark .bg-white,
    .dark .card,
    .dark .table-container {
        background-color: #020617 !important;
        border-color: #1e293b !important;
    }

    /* Border global */
    .dark .border-gray-100,
    .dark .border-gray-200,
    .dark .border-gray-300 {
        border-color: #1e293b !important;
    }

    /* Text */
    .dark .text-gray-900 {
        color: #f8fafc !important;
    }

    .dark .text-gray-700,
    .dark .text-gray-600,
    .dark .text-gray-500 {
        color: #cbd5f5 !important;
    }

        /* ================= TABLE ================= */

    .dark table {
        background-color: #020617;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 2px solid #ffffff;
    }

    /* Header tabel */
    .dark thead tr {
        background: linear-gradient(
            to right,
            rgba(16, 185, 129, 0.12),
            rgba(20, 184, 166, 0.08)
        );
    }

    .dark thead th {
        color: #a7f3d0;
        font-weight: 700;
        border-color: #1e293b;
        color: #f8fafc;
    }

    /* Body tabel */
    .dark tbody tr {
        background-color: #020617;
        transition: background-color 0.2s ease;
    }

    .dark tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.06);
    }

    /* Text warna putih di dalam tabel */
    .dark tbody td {
        color: #f8fafc !important;
        border-color: #1e293b;
    }

    .dark tbody .table-row-item {
        color: #f8fafc;
    }

    .dark .font-semibold {
        color: #f8fafc !important;
    }

    .dark .text-gray-500,
    .dark .text-gray-600 {
        color: #cbd5f5 !important;
    }

    /* Divider */
    .dark .divide-gray-100 > :not([hidden]) ~ :not([hidden]) {
        border-color: #1e293b;
    }

    /* ================= INPUT & SELECT ================= */

    .dark input,
    .dark select {
        background-color: #020617 !important;
        color: #cbd5e1 !important;
        border: 2px solid #ffffff !important;
    }

    .dark input::placeholder {
        color: #64748b;
    }

    /* Border abu-abu untuk custom select wrapper di dark mode */
    .dark .custom-select-wrapper {
        border-color: #374151 !important;
        background-color: #020617;
        border: 1px solid #374151;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .dark .custom-select-wrapper:hover {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }

    .dark .custom-select-wrapper:has(select:focus) {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }

    .dark .custom-select-wrapper select {
        color: #cbd5e1 !important;
        background-color: #020617 !important;
    }

    .dark .custom-select-icon {
        color: #a7f3d0;
    }

    /* Dark mode search input */
    .dark #search-komoditas {
        background-color: #020617 !important;
        color: #cbd5e1 !important;
        border: 1px solid #374151 !important;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .dark #search-komoditas::placeholder {
        color: #64748b;
    }

    .dark #search-komoditas:hover {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }

    .dark #search-komoditas:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }

    /* ================= TABLE CARD ================= */

    .dark .bg-white.rounded-2xl {
        background-color: #020617 !important;
        border: 2px solid #ffffff !important;
    }

    /* Chart title brightness */
    .dark #chart-title {
        color: #e2e8f0 !important;
    }
    

</style>
@endpush


@section('content')
    <section class="gradient-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="breadcrumb-nav">
                <a href="{{ url('/') }}" class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Beranda
                </a>
                <span class="breadcrumb-separator">&gt;</span>
                <span class="font-medium">Pasar</span>
            </nav>
            
            <h1 class="header-title text-3xl md:text-4xl lg:text-5xl">
                Pasar Rakyat <br class="hidden md:block">Kabupaten Bantul
            </h1>
            <p class="header-subtitle">
                Informasi lengkap pasar pantauan beserta ketersediaan stok bahan pokok di seluruh pasar rakyat Kabupaten Bantul.
            </p>
        </div>
    </section>

    <!-- Informasi Stok Komoditas Table -->
    <section class="py-12 bg-transparent hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                <div>
                    <div class="flex items-baseline gap-3 mb-2">
                        <h2 id="display-nama-pasar" class="text-2xl md:text-3xl font-bold text-gray-900">
                            Informasi Stok <span id="nama-pasar-judul">Pasar Bantul</span>
                        </h2>
                        <span id="current-date" class="text-sm font-bold text-gray-500"></span>
                    </div>
                    <p class="text-gray-500">Ketersediaan bahan pokok di pasar-pasar pantauan Kabupaten Bantul</p>
                </div>
                
                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="custom-select-wrapper">
                        <select id="filter-pasar" onchange="filterPasar()" class="appearance-none bg-white border border-gray-200 py-2 pl-4 pr-10 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500">
                            @foreach($pasarList->whereIn('id', [1,2,3,4,5]) as $pasar)
                                <option value="{{ $pasar->id }}" @if($selectedPasar && $selectedPasar->id == $pasar->id) selected @endif>{{ $pasar->nama_pasar }}</option>
                            @endforeach
                        </select>
                        <div class="custom-select-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="text" 
                            id="search-komoditas" 
                            placeholder="Cari komoditas..." 
                            oninput="handleSearch()"
                            class="bg-white border border-gray-200 py-3 pl-10 pr-4 rounded-xl text-sm text-gray-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-64 shadow-sm">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="w-full"> 
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 dark:from-gray-800 dark:to-gray-700 dark:border-gray-600">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Komoditas</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="komoditas-table-body" class="divide-y divide-gray-100">
                            @foreach($komoditas as $item)
                            <tr class="table-row-item table-row-hover transition-colors">
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-200 font-medium">
                                    {{ $item['no'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">{{ $item['emoji'] ?? '' }}</span>
                                        <span class="font-semibold text-gray-900">{{ $item['nama'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-500 text-sm">{{ $item['harga'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-500 text-sm">{{ $item['stok'] ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="showDetail('{{ $item['nama'] }}', '{{ $item['stok'] ?? '-' }}', '{{ $item['status'] ?? '-' }}', '{{ $item['harga'] }}', '{{ $item['emoji'] ?? '' }}')" 
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-sm font-semibold transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            <tr id="no-results" class="hidden">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Komoditas tidak ditemukan di pasar ini</h3>
                                        <!-- <button onclick="resetSearch()" class="mt-4 text-emerald-600 font-semibold hover:text-emerald-700">Lihat semua komoditas</button> -->
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


    <!-- Detail Modal -->
    <div id="detail-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[60] overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="relative w-full max-w-3xl"> 
            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Detail Stok <span id="modal-header-komoditas" class="text-emerald-600"></span>
                        </h3>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-g ray-400 bg-white hover:bg-gray-100 hover:text-gray-900 rounded-xl w-10 h-10 inline-flex justify-center items-center transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-8 items-center justify-center">
                        <div class="flex flex-row items-start gap-6 shrink-0">
                            <div class="w-28 h-28 md:w-32 md:h-32 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-emerald-200">
                                <span id="modal-emoji" class="text-5xl md:text-6xl">-</span>
                            </div>
                            <div class="text-left">
                                <h3 id="modal-nama-komoditas" class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">-</h3>
                                <div class="mb-4">
                                    <p class="text-gray-500 text-sm font-medium">Harga Sekarang :</p>
                                    <div class="flex items-baseline gap-1 text-black dark:text-white font-bold">
                                        <span id="modal-harga-sekarang" class="text-2xl md:text-3xl dark:text-white">Rp 0</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <p class="text-gray-500 text-sm font-medium">Stok Saat Ini :</p>
                                    <div class="flex items-baseline gap-1 text-black dark:text-white font-bold">
                                        <span id="modal-stok-sekarang" class="text-xl md:text-2xl dark:text-white">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block w-px h-32 bg-gray-100"></div>
                            <!-- Period Week Display -->
                            <div class="flex flex-col items-center justify-center gap-6">
                                <div class="bg-emerald-600 text-white dark:bg-emerald-800 px-6 py-2 rounded-full shadow-lg">
                                    <span id="modal-periode" class="text-sm font-bold text-white uppercase tracking-widest">
                                        Januari 2026 - Week 2
                                    </span>
                                </div>
                                <div class="flex flex-row justify-center items-center gap-8"> <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center mb-2 border border-red-200 dark:border-red-800">
                                        <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Tertinggi</p>
                                    <p class="text-base font-black text-white" id="modal-harga-tertinggi">-</p>
                                </div>
                                <div class="flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center mb-2 border border-amber-200 dark:border-amber-800">
                                            <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                            </svg>
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">Terendah</p>
                                        <p class="text-base font-black text-white" id="modal-harga-terendah">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <h4 id="chart-title" class="text-xs font-bold uppercase tracking-widest mb-4 text-center">Tren Harga 7 Hari Terakhir</h4>
                    <div id="priceTrendChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // 1. Tambahkan variabel global untuk menyimpan instance chart agar bisa di-reset
        let priceChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Update Tanggal
            const dateElement = document.getElementById('current-date');
            const periodeElement = document.getElementById('modal-periode');
            if (dateElement) {
                const now = new Date();
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                dateElement.textContent = "Update: " + now.toLocaleDateString('id-ID', options);
                
                // Calculate week of month for modal period
                const weekOfMonth = Math.ceil(now.getDate() / 7);
                const monthName = now.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
                if (periodeElement) {
                    periodeElement.textContent = monthName + ' - Week ' + weekOfMonth;
                }
            }
        });

        // FUNGSI UTAMA SEARCH (Dibatasi oleh Filter Pasar)
        function handleSearch() {
            const input = document.getElementById('search-komoditas');
            const filterText = input.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.table-row-item');
            const noResults = document.getElementById('no-results');
            let foundCount = 0;

            rows.forEach((row) => {
                const namaKomoditas = row.querySelector('.font-semibold').textContent.toLowerCase();
                if (namaKomoditas.includes(filterText)) {
                    row.style.display = "";
                    foundCount++;
                } else {
                    row.style.display = "none";
                }
            });

            if (noResults) {
                foundCount === 0 ? noResults.classList.remove('hidden') : noResults.classList.add('hidden');
            }
        }

        function filterPasar() {
            const selectPasar = document.getElementById('filter-pasar');
            const pasarId = selectPasar.value;
            // reload halaman dengan query ?pasar=ID
            window.location.search = '?pasar=' + pasarId;
        }
        function resetSearch() {
            const input = document.getElementById('search-komoditas');
            input.value = '';
            handleSearch();
        }

        // 2. Perbaiki fungsi showDetail agar memanggil renderPriceChart
        // Data komoditas untuk JS
        const komoditasData = @json($komoditas);

        function showDetail(nama, stok, status, harga, emoji) {
            const selectPasar = document.getElementById('filter-pasar');
            const namaPasar = selectPasar.options[selectPasar.selectedIndex].text;

            // Cari data komoditas dari array
            const komoditas = komoditasData.find(k => k.nama === nama);

            if(document.getElementById('modal-header-komoditas')) 
                document.getElementById('modal-header-komoditas').innerText = nama;
            if(document.getElementById('modal-nama-komoditas'))
                document.getElementById('modal-nama-komoditas').innerText = nama;
            if(document.getElementById('modal-emoji'))
                document.getElementById('modal-emoji').innerText = emoji;
            if(document.getElementById('modal-harga-sekarang'))
                document.getElementById('modal-harga-sekarang').innerText = harga;
            if(document.getElementById('modal-stok-sekarang'))
                document.getElementById('modal-stok-sekarang').innerText = stok;
            if(document.getElementById('modal-harga-tertinggi'))
                document.getElementById('modal-harga-tertinggi').innerText = komoditas && komoditas.max_harga ? komoditas.max_harga : '-';
            if(document.getElementById('modal-harga-terendah'))
                document.getElementById('modal-harga-terendah').innerText = komoditas && komoditas.min_harga ? komoditas.min_harga : '-';

            // Tampilkan Modal
            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex'); 
            document.body.classList.add('overflow-hidden');

            // Render Chart
            setTimeout(() => {
                if(komoditas && komoditas.chart_prices && komoditas.chart_labels) {
                    renderPriceChart(komoditas.chart_prices, komoditas.chart_labels);
                }
            }, 150);
        }

        // 4. Perbaiki fungsi closeModal agar membersihkan instance chart
        function closeModal() {
            const modal = document.getElementById('detail-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');

            // Hancurkan instance chart jika ada agar tidak memberatkan memori
            if (priceChart !== null) {
                priceChart.destroy();
                priceChart = null;
            }

            const backdrops = document.querySelectorAll('[md-backdrop], .modal-backdrop, [role="presentation"]');
            backdrops.forEach(el => el.remove());
            
            const anyBackdrop = document.querySelector('div[class="bg-gray-900/50"], div[class="bg-black/60"]');
            if (anyBackdrop && anyBackdrop !== modal) {
                anyBackdrop.remove();
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('detail-modal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // 5. Pastikan fungsi renderPriceChart mengelola instance global
        function renderPriceChart(data, categories) {
            if (priceChart !== null) {
                priceChart.destroy();
            }

            // Ambil tanggal hari ini (Format harus sama dengan array 'categories', misal: "05 Jan")
            const now = new Date();
            const todayLabel = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });

            const options = {
                series: [{ name: "Harga", data: data }],
                chart: {
                    height: 250,
                    type: 'area',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#2196F3'], // Warna garis biru
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.2,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    }
                },
                annotations: {
                    xaxis: [{
                        x: todayLabel, // Menyorot tanggal hari ini
                        fillColor: '#10B981', // Highlight Hijau
                        opacity: 0.2,
                        borderWidth: 0,
                        label: {
                            text: 'Hari Ini',
                            style: { color: '#064E3B', background: '#D1FAE5', fontSize: '10px', fontWeight: 'bold' }
                        }
                    }]
                },
                grid: { borderColor: '#f1f1f1', xaxis: { lines: { show: false } } },
                xaxis: { categories: categories, labels: { style: { colors: '#94a3b8' } } },
                yaxis: { 
                    tickAmount: 4,
                    labels: { 
                        formatter: (val) => val.toLocaleString('id-ID'),
                        style: { colors: '#94a3b8' } 
                    } 
                }
            };

            priceChart = new ApexCharts(document.querySelector("#priceTrendChart"), options);
            priceChart.render();
        }
    </script>
@endsection