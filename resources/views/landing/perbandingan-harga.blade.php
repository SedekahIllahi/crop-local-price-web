@extends('layouts.app')

@section('title', 'Perbandingan Harga - SIGAPAN Bantul')

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

    /* Legend section styling di dark mode */
    .dark .bg-gray-50 {
        background-color: #1e293b !important;
    }

    /* Legend text styling di dark mode - lebih cerah */
    .dark .text-gray-600 {
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
                <span class="font-medium">Perbandingan Harga</span>
            </nav>
            
            <h1 class="header-title text-3xl md:text-4xl lg:text-5xl">
                Perbandingan Harga <br class="hidden md:block">Komoditas Pangan
            </h1>
            <p class="header-subtitle">
                Pantau perbandingan harga rata-rata komoditas di berbagai pasar rakyat Kabupaten Bantul.
            </p>
        </div>
    </section>

    <!-- Perbandingan Harga Section -->
    <section class="py-12 bg-transparent hero-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Perbandingan Harga Rata-rata Komoditas</h3>
                    <p class="text-gray-500 mt-1">Rata-rata harga seluruh komoditas di setiap pasar (data terverifikasi terbaru)</p>
                </div>
                <div class="p-6">
                    <div class="h-96 md:h-[450px]">
                        <canvas id="barChartPasar"></canvas>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            <span class="text-sm text-gray-600">Termurah: <strong class="text-emerald-600">{{ $pasarTermurah }}</strong> <span class="text-xs text-gray-500">(Rp{{ number_format($hargaTermurah ?? 0, 0, ',', '.') }})</span></span>
        </div>

                        <div class="flex items-center gap-2">
            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
            <span class="text-sm text-gray-600">Termahal: <strong class="text-red-600">{{ $pasarTermahal }}</strong> <span class="text-xs text-gray-500">(Rp{{ number_format($hargaTermahal ?? 0, 0, ',', '.') }})</span></span>
        </div>
    </div>
</div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ url('/matriks-harga') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Matriks Harga
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

@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        let barChart = null;

        function initChart() {
            // Destroy existing chart if it exists
            if (barChart) {
                barChart.destroy();
            }

            const barCtx = document.getElementById('barChartPasar').getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            
            // Create horizontal gradients for horizontal bars (left to right)
            const createGradient = (ctx, colorStart, colorEnd) => {
                const gradient = ctx.createLinearGradient(0, 0, ctx.canvas.width, 0);
                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(0.5, colorEnd);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            };
            
            // 3 warna saja: Hijau, Kuning, Merah
            const gradientGreen = createGradient(barCtx, '#6ee7b7', '#059669');  // Hijau (termurah)
            const gradientYellow = createGradient(barCtx, '#fde047', '#eab308'); // Kuning (tengah)
            const gradientRed = createGradient(barCtx, '#fca5a5', '#dc2626');    // Merah (termahal)
            
            // Get dynamic data from PHP
            const barLabels = @json($barChartData['labels']);
            const barPrices = @json($barChartData['prices']);
            
            // Assign colors: Hijau = termurah, Merah = termahal, Kuning = lainnya
            let bgColors = [];
            let hoverColors = [];
            if (barPrices.length > 0) {
                const nonZeroPrices = barPrices.filter(p => p > 0);
                const min = nonZeroPrices.length ? Math.min(...nonZeroPrices) : 0;
                const max = nonZeroPrices.length ? Math.max(...nonZeroPrices) : 0;
                
                barPrices.forEach((price) => {
                    if (price === min && price > 0) {
                        // Termurah = Hijau
                        bgColors.push(gradientGreen);
                        hoverColors.push('#10b981');
                    } else if (price === max && price > 0) {
                        // Termahal = Merah
                        bgColors.push(gradientRed);
                        hoverColors.push('#ef4444');
                    } else if (price > 0) {
                        // Lainnya = Kuning
                        bgColors.push(gradientYellow);
                        hoverColors.push('#eab308');
                    } else {
                        // Data kosong = Abu-abu
                        bgColors.push(isDark ? '#374151' : '#e5e7eb');
                        hoverColors.push(isDark ? '#4b5563' : '#d1d5db');
                    }
                });
            }

            // Dynamic bar thickness based on number of items
            const barThickness = barPrices.length <= 3 ? 55 : barPrices.length <= 5 ? 45 : 35;
            
            // Dynamic max scale with 20% padding
            const maxPrice = Math.max(...barPrices.filter(p => p > 0), 1);
            const maxScale = maxPrice * 1.25;

            barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: barLabels,
                    datasets: [{
                        label: 'Rata-rata Harga Komoditas (Rp)',
                        data: barPrices,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: hoverColors,
                        borderWidth: 0,
                        borderRadius: {
                            topLeft: 8,
                            topRight: 8,
                            bottomLeft: 8,
                            bottomRight: 8
                        },
                        borderSkipped: false,
                        barThickness: barThickness,
                        maxBarThickness: 60
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    layout: {
                        padding: {
                            right: 80,
                            left: 10,
                            top: 20,
                            bottom: 20
                        }
                    },
                    animation: {
                        duration: 800,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#f3f4f6' : '#111827',
                            bodyColor: isDark ? '#d1d5db' : '#374151',
                            borderColor: isDark ? '#374151' : '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 14,
                            displayColors: false,
                            titleFont: {
                                size: 14,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                title: (context) => context[0].label,
                                label: (context) => {
                                    if (context.raw === 0) return 'Data tidak tersedia';
                                    return 'Rata-rata: Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            offset: 8,
                            color: isDark ? '#e5e7eb' : '#374151',
                            font: {
                                size: 13,
                                weight: '700'
                            },
                            formatter: function(value) {
                                if (value === 0) return '-';
                                if (value >= 1000000) {
                                    return 'Rp ' + (value/1000000).toFixed(1) + 'Jt';
                                }
                                return 'Rp ' + (value/1000).toFixed(0) + 'K';
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: false,
                            grid: {
                                display: false
                            },
                            max: maxScale
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: isDark ? '#e5e7eb' : '#374151',
                                font: { 
                                    size: 14, 
                                    weight: '600'
                                },
                                padding: 12
                            }
                        }
                    },
                    onHover: (event, elements) => {
                        event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Register datalabels plugin
            Chart.register(ChartDataLabels);
            
            // Initialize chart
            initChart();

            // Re-initialize chart when theme changes
            const html = document.documentElement;
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        initChart();
                    }
                });
            });

            observer.observe(html, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
@endsection