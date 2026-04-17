@extends('layouts.app')

@section('title', 'Matriks Harga - SIGAPAN Bantul')

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

    /* ================= DARK MODE SIGAPAN ================= */

    /* Background utama */
    .dark body {
        background-color: #020617; /* slate-950 */
    }

    /* Section */
    .dark section.bg-gray-50,
    .dark section.bg-white,
    .dark section.bg-transparent {
        background-color: #020617;
    }

    /* Card, container */
    .dark .bg-white,
    .dark .card {
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

    .dark .text-xl,
    .dark .text-lg:not(.text-emerald-700):not(.text-red-700):not(.text-blue-700):not(.text-purple-700) {
        color: #f8fafc !important;
    }

    /* Statistik cards - preserve color in dark mode */
    .dark .text-emerald-700 { color: #34d399 !important; }
    .dark .text-red-700 { color: #f87171 !important; }
    .dark .text-blue-700 { color: #60a5fa !important; }
    .dark .text-purple-700 { color: #a78bfa !important; }

    /* Statistik label - hitam di light dan dark */
    .statistik-label {
        color: #111827 !important; /* gray-900 / hitam */
        font-weight: 600 !important;
    }
    .dark .statistik-label {
        color: #111827 !important; /* tetap hitam */
        font-weight: 700 !important;
    }

    /* Shadow */
    .dark .shadow-lg {
        box-shadow: none;
    }

    /* White borders untuk dark mode */
    .dark .border {
        border-color: #64748b !important;
    }

    .dark .rounded-2xl {
        border: 2px solid #64748b !important;
    }

    .dark .mt-4.flex.items-center span.text-gray-600 {
        color: #ffffff !important;
    }
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
                <span class="font-medium">Matriks Harga</span>
            </nav>
            <h1 class="header-title text-3xl md:text-4xl lg:text-5xl">
                Matriks Harga <br class="hidden md:block">Komoditas Pangan
            </h1>
            <p class="header-subtitle">
                Overview harga komoditas di semua pasar rakyat Kabupaten Bantul dengan indikator warna.
            </p>
        </div>
    </section>

    <!-- Matriks Harga Section -->
    <section class="py-12 bg-transparent hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Matriks Harga Komoditas</h3>
                    <p class="text-gray-500 mt-1">Overview harga di 5 pasar pantauan (data terverifikasi terbaru)</p>
                </div>
                <div class="p-6">
                    <!-- Heatmap Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Komoditas</th>
                                    @foreach($pasarList as $pasar)
                                        <th class="text-center py-3 px-2 font-semibold text-gray-700">{{ $pasar->nama_pasar }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($matriksData as $row)
                                    <tr>
                                        <td class="py-3 px-2 font-medium text-gray-900">
                                            @if(!empty($row['icon']))
                                                <span class="mr-1">{!! $row['icon'] !!}</span>
                                            @endif
                                            {{ $row['nama'] }}
                                        </td>
                                        @foreach($pasarList as $pasar)
                                            @php
                                                $priceData = $row['prices'][$pasar->id] ?? null;
                                                $harga = $priceData['harga'] ?? 0;
                                                $trend = $priceData['trend'] ?? 'stabil';
                                                $selisih = $priceData['selisih'] ?? 0;
                                            @endphp
                                            <td class="py-2 px-1 text-center">
                                                @if(is_numeric($harga) && $harga > 0)
                                                    <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold cursor-pointer
                                                        @if($trend=='naik') bg-red-100 text-red-800
                                                        @elseif($trend=='turun') bg-emerald-100 text-emerald-800
                                                        @else bg-amber-100 text-amber-800 @endif"
                                                        title="@if($trend=='naik')Naik Rp{{ number_format(abs($selisih), 0, ',', '.') }}@elseif($trend=='turun')Turun Rp{{ number_format(abs($selisih), 0, ',', '.') }}@else Stabil @endif">
                                                        @if($trend=='naik') ▲ @elseif($trend=='turun') ▼ @else ▬ @endif
                                                        {{ number_format($harga/1000, 1) }}K
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                
                                <!-- Row Rata-rata per Pasar -->
                                <tr class="bg-gray-50 dark:bg-gray-800 border-t-2 border-gray-300">
                                    <td class="py-3 px-2 font-bold text-gray-900">
                                        📊 Rata-rata Semua Komoditas
                                    </td>
                                    @foreach($pasarList as $pasar)
                                        @php
                                            $avgData = $rataRataPerPasar[$pasar->id] ?? null;
                                            $avgHarga = $avgData['rata_rata'] ?? 0;
                                            $avgTrend = $avgData['trend'] ?? 'stabil';
                                            $avgSelisih = $avgData['selisih'] ?? 0;
                                        @endphp
                                        <td class="py-2 px-1 text-center">
                                            @if($avgHarga > 0)
                                                <span class="inline-block px-2.5 py-1 rounded-md text-xs font-semibold cursor-pointer
                                                    @if($avgTrend=='naik') bg-red-200 text-red-900
                                                    @elseif($avgTrend=='turun') bg-emerald-200 text-emerald-900
                                                    @else bg-amber-200 text-amber-900 @endif"
                                                    title="@if($avgTrend=='naik')Naik Rp{{ number_format(abs($avgSelisih), 0, ',', '.') }}@elseif($avgTrend=='turun')Turun Rp{{ number_format(abs($avgSelisih), 0, ',', '.') }}@else Stabil @endif">
                                                    @if($avgTrend=='naik') ▲ @elseif($avgTrend=='turun') ▼ @else ▬ @endif
                                                    {{ number_format($avgHarga/1000, 1) }}K
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Statistik Summary -->
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 text-center statistik-card">
                            <div class="text-xs mb-1 statistik-label">Harga Terendah</div>
                            <div class="text-lg font-bold text-emerald-700 dark:text-emerald-400">Rp{{ number_format($statistik['terendah']['rata_rata'] ?? 0, 0, ',', '.') }}</div>
                            <div class="text-xs statistik-label">{{ $statistik['terendah']['nama_pasar'] ?? '-' }}</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 text-center statistik-card">
                            <div class="text-xs mb-1 statistik-label">Harga Tertinggi</div>
                            <div class="text-lg font-bold text-red-700 dark:text-red-400">Rp{{ number_format($statistik['tertinggi']['rata_rata'] ?? 0, 0, ',', '.') }}</div>
                            <div class="text-xs statistik-label">{{ $statistik['tertinggi']['nama_pasar'] ?? '-' }}</div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 text-center statistik-card">
                            <div class="text-xs mb-1 statistik-label">Rata-rata Semua Pasar</div>
                            <div class="text-lg font-bold text-blue-700 dark:text-blue-400">Rp{{ number_format($statistik['rata_rata_semua'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 text-center statistik-card">
                            <div class="text-xs mb-1 statistik-label">Selisih Tertinggi-Terendah</div>
                            <div class="text-lg font-bold text-purple-700 dark:text-purple-400">Rp{{ number_format($statistik['selisih'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div class="mt-4 flex items-center justify-center gap-6 text-xs flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-emerald-100 border border-emerald-200 dark:border-emerald-800/50"></span>
                            <span class="text-gray-600 dark:text-white">Turun</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-amber-100 border border-amber-200 dark:border-amber-800/50"></span>
                            <span class="text-gray-600 dark:text-white">Stabil</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-4 h-4 rounded bg-red-100 border border-red-200 dark:border-red-800/50"></span>
                            <span class="text-gray-600 dark:text-white">Naik</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ url('/perbandingan-harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Perbandingan Harga
                </a>
                <a href="{{ url('/trend-harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Trend Harga
                </a>
                <a href="{{ url('/pasar') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow-lg shadow-emerald-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Lihat Info Pasar
                </a>
            </div>
        </div>
    </section>
@endsection
