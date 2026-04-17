@extends('layouts.admin.master')

@section('title', 'Dashboard')

@section('breadcrumb')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
        border: 1px solid rgba(5, 150, 105, 0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(5, 150, 105, 0.15);
        border-color: rgba(5, 150, 105, 0.3);
    }

    .dark .stat-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(2, 6, 23, 0.8) 100%);
        border-color: rgba(16, 185, 129, 0.1);
    }

    .dark .stat-card:hover {
        border-color: rgba(16, 185, 129, 0.3);
        box-shadow: 0 12px 24px rgba(16, 185, 129, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 900;
        color: #10b981;
    }

    .dark .stat-number {
        color: #34d399;
    }

    .welcome-section {
        background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .card-gradient {
        background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border: 1px solid rgba(5, 150, 105, 0.2);
    }

    .dark .card-gradient {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-color: rgba(16, 185, 129, 0.3);
    }

    .btn-action {
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(5, 150, 105, 0.2);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="welcome-section rounded-2xl p-8 md:p-12 shadow-xl" style="color: #ffffff !important;">
        <div class="relative z-10">
            <h1 class="text-2xl md:text-5xl font-black mb-3" style="color: #ffffff !important;">
                Selamat Datang, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-lg md:text-xl max-w-2xl font-medium" style="color: #ecfdf5 !important;">
                @if($isLurahUser ?? false)
                    Kelola data harga pangan di {{ $namaPasar ?? 'Pasar' }} dengan sistem SIGAPAN.
                @else
                    Kelola data harga pangan dan informasi pasar dengan sistem SIGAPAN yang canggih.
                @endif
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                @if($isLurahUser ?? false)
                    <a href="{{ route('lurah') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-700 font-bold rounded-lg hover:bg-emerald-50 transition shadow-lg">
                        <i class="ri-price-tag-3-fill"></i> Kelola Harga
                    </a>
                @else
                    <a href="{{ route('settings.users.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-700 font-bold rounded-lg hover:bg-emerald-50 transition shadow-lg">
                        <i class="ri-group-fill"></i> Kelola Pengguna
                    </a>
                    <a href="{{ route('settings.preferences.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600/50 backdrop-blur-sm border border-emerald-400/30 font-semibold rounded-lg hover:bg-emerald-600 transition shadow-lg" style="color: #ffffff !important;">
                        <i class="ri-settings-3-fill"></i> Pengaturan
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Alert Badge untuk User Lurah yang belum update harga hari ini --}}
    @if(($isLurahUser ?? false) && !($hasUpdatedToday ?? true))
    <div class="rounded-xl p-4 shadow-lg" style="background: linear-gradient(to right, #dc2626, #ef4444); border: 1px solid rgba(248, 113, 113, 0.3);">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(255,255,255,0.2);">
                    <i class="ri-alarm-warning-fill text-xl" style="color: white;"></i>
                </div>
                <div>
                    <p class="font-bold text-base md:text-lg" style="color: white;">
                        ⚠️ Anda Belum Update Harga Komoditas Hari Ini!
                    </p>
                    <p class="text-sm" style="color: rgba(255,255,255,0.9);">
                        @if($lastUpdateDate ?? null)
                            Terakhir update: {{ \Carbon\Carbon::parse($lastUpdateDate)->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB
                        @else
                            Belum ada data harga yang diinput
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('lurah') }}" class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 font-bold rounded-lg transition shadow-md" style="background: white; color: #dc2626;">
                <i class="ri-edit-2-fill"></i> 
                <span class="hidden sm:inline">Update Sekarang</span>
            </a>
        </div>
    </div>
    @endif

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @if($isLurahUser ?? false)
            <!-- Stat Card 1 - Jumlah Komoditas -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Jumlah Komoditas</p>
                        <div class="stat-number">{{ $totalKomoditas ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-emerald-100 dark:bg-emerald-800/60 text-emerald-600 dark:text-emerald-300">
                        <i class="ri-box-3-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total komoditas di {{ $namaPasar ?? 'pasar' }}</p>
            </div>

            <!-- Stat Card 2 - Verified -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Verified</p>
                        <div class="stat-number">{{ $verifiedCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-blue-100 dark:bg-blue-800/60 text-blue-600 dark:text-blue-300">
                        <i class="ri-checkbox-circle-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Komoditas terverifikasi</p>
            </div>

            <!-- Stat Card 3 - Pending -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Pending</p>
                        <div class="stat-number">{{ $pendingCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-amber-100 dark:bg-amber-800/60 text-amber-600 dark:text-amber-300">
                        <i class="ri-time-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Komoditas menunggu verifikasi</p>
            </div>

            <!-- Stat Card 4 - Gagal -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Gagal</p>
                        <div class="stat-number">{{ $gagalCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-red-100 dark:bg-red-800/60 text-red-600 dark:text-red-300">
                        <i class="ri-close-circle-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Komoditas gagal verifikasi</p>
            </div>
        @else
            <!-- Stat Card 1 -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Pengguna Aktif</p>
                        <div class="stat-number">{{ $userCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-emerald-100 dark:bg-emerald-800/60 text-emerald-600 dark:text-emerald-300">
                        <i class="ri-group-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah pengguna terdaftar</p>
            </div>

            <!-- Stat Card 2 -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Pasar Aktif</p>
                        <div class="stat-number">{{ $pasarCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-blue-100 dark:bg-blue-800/60 text-blue-600 dark:text-blue-300">
                        <i class="ri-store-2-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah pasar terintegrasi</p>
            </div>

            <!-- Stat Card 3 -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Komoditas</p>
                        <div class="stat-number">{{ $komoditasCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-amber-100 dark:bg-amber-800/60 text-amber-600 dark:text-amber-300">
                        <i class="ri-box-3-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total komoditas dipantau</p>
            </div>

            <!-- Stat Card 4 -->
            <div class="stat-card rounded-xl p-6 shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-2">Peran Akses</p>
                        <div class="stat-number">{{ $roleCount ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-purple-100 dark:bg-purple-800/60 text-purple-600 dark:text-purple-300">
                        <i class="ri-shield-check-fill"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">Manajemen peran</p>
            </div>
        @endif
    </div>



    <!-- Info Section -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl p-6 border border-emerald-200 dark:border-emerald-800">
        <div class="flex gap-4">
            <div class="text-2xl">ℹ️</div>
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-2">Tentang SIGAPAN</h4>
                <p class="text-gray-700 dark:text-gray-300 text-sm">
                    SIGAPAN adalah Sistem Informasi Harga Pangan yang dirancang untuk memantau dan mengelola data harga komoditas pangan di pasar-pasar rakyat Kabupaten Bantul. 
                    Sistem ini membantu transparansi harga dan mendukung pengambilan keputusan yang lebih baik.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Animasi data loading (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.animation = `slideUp 0.6s ease forwards`;
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endsection

