@extends('layouts.app')

@section('title', 'Trend Harga - SIGAPAN Bantul')

@push('styles')
<style>
    .gradient-header {
        background: #064e3b; 
        position: relative;
        padding-top: 7rem; 
        padding-bottom: 3.5rem;
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

    /* iOS Glass Button */
    .glass-btn {
        background: rgba(16, 185, 129, 0.15);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 18px;
        color: #065f46;
        transition: background .35s, transform .25s, box-shadow .35s;
        position: relative;
        overflow: hidden;
        font-weight: 600;
    }
    .glass-btn:hover {
        transform: translateY(-1px) scale(1.03);
        background: rgba(16, 185, 129, 0.25);
    }
    .glass-btn:active {
        transform: scale(0.94);
    }
    .glass-btn.active {
        background: linear-gradient(135deg, #059669, #047857);
        color: #ffffff;
        box-shadow: 0 8px 22px rgba(5, 150, 105, 0.45);
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

    .dark .text-gray-400 {
        color: #94a3b8 !important;
    }

    .dark .text-xl,
    .dark .text-lg {
        color: #f8fafc !important;
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

    /* Glass button dark mode */
    .dark .glass-btn {
        background: rgba(16, 185, 129, 0.15);
        color: #a7f3d0;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .dark .glass-btn:hover {
        background: rgba(16, 185, 129, 0.25);
    }
    .dark .glass-btn.active {
        background: linear-gradient(135deg, #059669, #047857);
        color: #ffffff;
        box-shadow: 0 8px 22px rgba(5, 150, 105, 0.45);
    }

    /* Period tabs container dark mode */
    .dark #period-tabs {
        background-color: #0f172a;
    }

    /* Legend dan stat boxes styling untuk dark mode */
    .dark .px-6.py-4 {
        background-color: #0f172a !important;
    }

    .dark .px-6.py-5 {
        background-color: #0f172a !important;
    }

    .dark .px-6.py-5 .bg-white {
        background-color: #111827 !important;
        border-color: #1e293b !important;
    }

    .dark .px-6.py-5 .text-center p {
        color: #f8fafc !important;
    }

    /* Jangan ubah warna teks Harga Tertinggi di dark mode */
    .dark .harga-tertinggi .text-gray-500 {
        color: #6b7280 !important; /* gray-500 default */
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
                <span class="font-medium">Trend Harga</span>
            </nav>
            
            <h1 class="header-title text-3xl md:text-4xl lg:text-5xl">
                Trend Harga <br class="hidden md:block">Komoditas Pangan
            </h1>
            <p class="header-subtitle">
                Perbandingan harga rata-rata komoditas pada pasar dalam periode tertentu.
            </p>
        </div>
    </section>

    <!-- Main Chart Section -->
    <section class="py-12 bg-transparent hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Chart Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
                <!-- Chart Header -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Trend Harga Komoditas</h3>
                            <p class="text-gray-500 mt-1">Rata-rata harga semua komoditas per pasar dalam periode tertentu</p>
                        </div>
                        
                        <!-- Controls -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Period Tabs -->
                            <div id="period-tabs" class="flex items-center bg-gray-100 rounded-xl p-1">
                                <button data-period="7" class="period-btn glass-btn px-4 py-2 text-sm font-medium">
                                    7 Hari
                                </button>
                                <button data-period="14" class="period-btn glass-btn active px-4 py-2 text-sm font-medium">
                                    14 Hari
                                </button>
                                <button data-period="30" class="period-btn glass-btn px-4 py-2 text-sm font-medium">
                                    30 Hari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Legend -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <div class="flex flex-wrap gap-4 md:gap-6">
                        @php
                            $colors = ['#059669', '#2563eb', '#dc2626', '#9333ea', '#ca8a04'];
                        @endphp
                        @foreach($pasarList as $idx => $pasar)
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full" style="background-color: {{ $colors[$idx] ?? '#6b7280' }};"></span>
                            <span class="text-sm text-gray-600 font-medium">{{ $pasar->nama_pasar }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="p-6">
                    <div class="relative h-96">
                        <canvas id="hargaTrendChart"></canvas>
                    </div>
                </div>
                
                <!-- Chart Footer Stats -->
                <div id="chart-stats" class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-sm">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <div class="font-semibold text-gray-700">Harga Terendah</div>
                            <div id="stat-terendah" class="text-emerald-700 font-bold"></div>
                            <div id="stat-terendah-pasar" class="text-xs text-gray-500"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Harga Tertinggi</div>
                            <div id="stat-tertinggi" class="text-red-700 font-bold"></div>
                            <div id="stat-tertinggi-pasar" class="text-xs text-gray-500"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Rata-rata Semua Pasar</div>
                            <div id="stat-rata" class="font-bold"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Selisih Tertinggi-Terendah</div>
                            <div id="stat-selisih" class="font-bold"></div>
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
                <a href="{{ url('/matriks-harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Matriks Harga
                </a>
                <a href="{{ url('/pasar') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow-lg shadow-emerald-500/25">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Lihat Info Pasar
                </a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('hargaTrendChart').getContext('2d');
            const periodBtns = document.querySelectorAll('.period-btn');
            let currentPeriod = 14;
            
            // Data dinamis dari controller
            let pasarIds = @json($pasarList->pluck('id')->toArray());
            let pasarLabels = @json($pasarList->pluck('nama_pasar')->toArray());

            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
            const tickColor = isDark ? '#9ca3af' : '#6b7280';

            // Inisialisasi chart
            const hargaTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: pasarLabels.map((label, idx) => ({
                        label: label,
                        data: [],
                        borderColor: ['#059669','#2563eb','#dc2626','#9333ea','#ca8a04'][idx],
                        backgroundColor: ['rgba(5,150,105,0.1)','rgba(37,99,235,0.1)','rgba(220,38,38,0.1)','rgba(147,51,234,0.1)','rgba(202,138,4,0.1)'][idx],
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointBackgroundColor: ['#059669','#2563eb','#dc2626','#9333ea','#ca8a04'][idx],
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        spanGaps: true // Skip null values
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { 
                        legend: { display: false }, 
                        tooltip: { 
                            backgroundColor: isDark ? '#1f2937' : '#fff',
                            titleColor: isDark ? '#f3f4f6' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12,
                            callbacks: { 
                                title: ctx => ctx[0].label, 
                                label: ctx => {
                                    if (ctx.raw === null || ctx.raw === 0) return ctx.dataset.label + ': Data tidak tersedia';
                                    return ctx.dataset.label + ': Rp ' + ctx.raw.toLocaleString('id-ID');
                                }
                            } 
                        } 
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { 
                                autoSkip: false, // Tampilkan semua label tanggal
                                maxRotation: 45, // Rotasi miring 45 derajat
                                minRotation: 45, // Selalu miring 45 derajat
                                color: tickColor, 
                                font: { size: 10, weight: '500' }
                            },
                            border: { display: false }
                        },
                        y: {
                            beginAtZero: false,
                            // Dynamic min/max - akan di-set setelah fetch data
                            grid: { 
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: { 
                                color: tickColor, 
                                font: { size: 11 },
                                callback: function(value) {
                                    if (value === 0) return 'Rp 0';
                                    if (value >= 1000) return 'Rp ' + (value/1000) + 'K';
                                    return 'Rp ' + value;
                                }
                            },
                            border: { display: false }
                        }
                    },
                    animation: {
                        duration: 600,
                        easing: 'easeOutQuart'
                    }
                }
            });

            async function fetchAndUpdate(period = 14) {
                try {
                    const fetches = pasarIds.map(id => 
                        fetch(`/api/chart-data?pasar_id=${id}&period=${period}`)
                            .then(res => res.json())
                    );
                    const results = await Promise.all(fetches);
                    
                    if (!results.length || !results[0].labels) {
                        console.error('No data returned from API');
                        return;
                    }
                    
                    const labels = results[0].labels;
                    const allData = results.map(data => 
                        // Keep prices as is, null values will create gaps
                        data.prices
                    );
                    
                    // Calculate dynamic min/max for Y-axis to show price fluctuations clearly
                    const allPrices = allData.flat().filter(p => p !== null && p > 0);
                    if (allPrices.length > 0) {
                        const minPrice = Math.min(...allPrices);
                        const maxPrice = Math.max(...allPrices);
                        const range = maxPrice - minPrice;
                        const padding = range * 0.2 || 2000; // 20% padding or minimum 2000
                        
                        // Round to nice numbers (nearest 1000)
                        const yMin = Math.max(0, Math.floor((minPrice - padding) / 1000) * 1000);
                        const yMax = Math.ceil((maxPrice + padding) / 1000) * 1000;
                        
                        hargaTrendChart.options.scales.y.min = yMin;
                        hargaTrendChart.options.scales.y.max = yMax;
                    }
                    
                    hargaTrendChart.data.labels = labels;
                    hargaTrendChart.data.datasets.forEach((ds, idx) => { 
                        ds.data = allData[idx] || []; 
                    });
                    hargaTrendChart.update('active');
                    
                    // Update statistik
                    const stat = results[0].statistik;
                    if (stat) {
                        document.getElementById('stat-terendah').textContent = stat.terendah.rata > 0 
                            ? 'Rp ' + Math.round(stat.terendah.rata).toLocaleString('id-ID') 
                            : '-';
                        document.getElementById('stat-terendah-pasar').textContent = stat.terendah.pasar || '-';
                        document.getElementById('stat-tertinggi').textContent = stat.tertinggi.rata > 0 
                            ? 'Rp ' + Math.round(stat.tertinggi.rata).toLocaleString('id-ID') 
                            : '-';
                        document.getElementById('stat-tertinggi-pasar').textContent = stat.tertinggi.pasar || '-';
                        document.getElementById('stat-rata').textContent = stat.rata_rata > 0 
                            ? 'Rp ' + Math.round(stat.rata_rata).toLocaleString('id-ID') 
                            : '-';
                        document.getElementById('stat-selisih').textContent = stat.selisih > 0 
                            ? 'Rp ' + Math.round(stat.selisih).toLocaleString('id-ID') 
                            : '-';
                    }
                } catch (error) {
                    console.error('Error fetching chart data:', error);
                }
            }

            // Initial fetch
            fetchAndUpdate(currentPeriod);

            periodBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    periodBtns.forEach(b => b.classList.remove('bg-emerald-600','text-white','shadow','active'));
                    this.classList.add('bg-emerald-600','text-white','shadow','active');
                    currentPeriod = parseInt(this.dataset.period);
                    fetchAndUpdate(currentPeriod);
                });
            });

            // Re-init on theme change
            const observer = new MutationObserver(() => {
                const isDarkNow = document.documentElement.classList.contains('dark');
                hargaTrendChart.options.scales.y.grid.color = isDarkNow ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
                hargaTrendChart.options.scales.y.ticks.color = isDarkNow ? '#9ca3af' : '#6b7280';
                hargaTrendChart.options.scales.x.ticks.color = isDarkNow ? '#9ca3af' : '#6b7280';
                hargaTrendChart.update();
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        });
    </script>
@endsection