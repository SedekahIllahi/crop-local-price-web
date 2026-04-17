@extends('layouts.app')

@section('title', isset($pasar) ? $pasar->nama_pasar . ' - Profil Pasar' : 'Profil Pasar')

@push('styles')
<style>
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
</style>
@endpush

@section('content')
    <section class="pt-24 pb-4 py-12 bg-transparent hero-pattern"> 
        <div class="absolute left-4 md:left-8 top-28 z-40">
            <a href="{{ route('daftar-pasar') }}" class="group flex items-center gap-2 px-4 py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur rounded-full shadow-md text-gray-700 dark:text-gray-900 hover:bg-emerald-50 dark:hover:bg-gray-700 hover:text-emerald-700 transition-all">
                <svg class="w-6 h-6 text-gray-800 dark:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>
                <span class="text-sm font-semibold whitespace-nowrap">
                    Kembali
                </span>
            </a>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-emerald-50 dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-emerald-100 dark:border-gray-700 mb-0">
                <!-- Carousel (card) -->
                <div class="relative carousel-wrap group" style="height: 20rem;">
                    <div class="carousel-container h-full">
                        @php
                            // Normalize images from `img_pasar` column for pasar detail
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
                                        // comma-separated fallback
                                        $parts = preg_split('/\s*,\s*/', trim($raw), -1, PREG_SPLIT_NO_EMPTY);
                                        $imgs = array_map(function($s) { return trim($s, "'\" \t\n\r\0\x0B"); }, $parts);
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
                                        if (strpos($clean, '/') !== false) {
                                            $src = asset($clean);
                                        } else {
                                            $src = asset('images/pasar/' . $clean);
                                        }
                                    }
                                @endphp
                                <div class="carousel-slide {{ $i === 0 ? 'active' : '' }} h-full">
                                    <img src="{{ $src }}" alt="{{ $pasar->nama_pasar }} {{ $i+1 }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-slide active h-full">
                                <img src="{{ asset('images/pasar/default.png') }}" alt="{{ $pasar->nama_pasar }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <!-- Arrows -->
                        <button class="carousel-arrow prev absolute left-6 top-1/2 -translate-y-1/2 opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 ease-out bg-white p-2 rounded-full shadow-md" aria-label="Prev">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button class="carousel-arrow next absolute right-6 top-1/2 -translate-y-1/2 opacity-0 translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 ease-out bg-white p-2 rounded-full shadow-md" aria-label="Next">
                            <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <!-- Dots -->
                        <div class="carousel-dots absolute left-1/2 -translate-x-1/2 bottom-4 flex gap-2"></div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <div class="flex-1">
                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">{{ $pasar->nama_pasar }}</h1>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ $pasar->alamat_lengkap ?? '-' }}</p>

                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white text-emerald-800 font-semibold">{{ ucfirst($pasar->tipe ?? 'tradisional') }}</span>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-50 text-gray-800">Kecamatan: {{ $pasar->kecamatan?->nama ?? '-' }}</span>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-50 text-gray-800">Kelurahan: {{ $pasar->kalurahan?->nama ?? '-' }}</span>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-50 text-gray-800">Dusun: {{ $pasar->dusun?->nama?? '-' }}</span>
                            </div>
                        </div>

                        <div class="w-full md:w-64">
                            <div class="p-4 bg-emerald-50 dark:bg-gray-700 rounded-xl shadow-sm border border-emerald-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        {{-- Tambahkan dark:text-white di sini --}}
                        <p class="text-xs text-gray-500 dark:text-gray-300">Jumlah Pedagang</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $pasar->jml_pedagang ?? 0 }}</p>
                    </div>
                    <div>
                        {{-- Tambahkan dark:text-white di sini --}}
                        <p class="text-xs text-gray-500 dark:text-gray-300">Kios / Los</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ ($pasar->jml_kios ?? 0) + ($pasar->jml_los ?? 0) }}</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a target="_blank" href="https://www.google.com/maps?q={{ $pasar->latitude }},{{ $pasar->longitude }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-2xl">Buka di Google Maps</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-emerald-50 dark:bg-gray-700 rounded-xl shadow-sm border border-emerald-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Info Umum</h3>
                            <ul class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-2">
                                <li><strong>Tipe:</strong> {{ ucfirst($pasar->tipe ?? '-') }}</li>
                                <li><strong>Kecamatan:</strong> {{ $pasar->kecamatan?->nama ?? '-' }}</li>
                                <li><strong>Kelurahan:</strong> {{ $pasar->kalurahan?->nama ?? '-' }}</li>
                                <li><strong>Dusun:</strong> {{ $pasar->dusun?->nama ?? '-' }}</li>
                                <li><strong>Alamat:</strong> {{ $pasar->alamat_lengkap ?? '-' }}</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-emerald-50 dark:bg-gray-700 rounded-xl shadow-sm border border-emerald-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Kontak & Jadwal</h3>
                            <ul class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-2">
                                @php
                                    $jamBuka = $pasar->jam_buka ?? null;
                                    $jamTutup = $pasar->jam_tutup ?? null;
                                    $jamBukaFormatted = $jamBuka ? substr($jamBuka,0,5) : '-';
                                    $jamTutupFormatted = $jamTutup ? substr($jamTutup,0,5) : '-';
                                @endphp
                                <li><strong>Jam Operasional:</strong> {{ $jamBukaFormatted }} &ndash; {{ $jamTutupFormatted }}</li>
                                <li><strong>Wajib Pantau:</strong> {{ $pasar->wajib_pantau ? 'Ya' : 'Tidak' }}</li>
                                <li><strong>Terakhir Diperbarui:</strong> {{ $pasar->hargaTerbaru?->tanggal ?? '-' }}</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-emerald-50 dark:bg-gray-700 rounded-xl shadow-sm border border-emerald-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Fasilitas</h3>
                            <ul class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-2">
                                <li>Pedagang: {{ $pasar->jml_pedagang ?? 0 }}</li>
                                <li>Kios: {{ $pasar->jml_kios ?? 0 }}</li>
                                <li>Los: {{ $pasar->jml_los ?? 0 }}</li>
                                <li>MCK: {{ $pasar->jml_mck ?? 0 }}</li>
                                <li>TPS: {{ $pasar->jml_tps ?? 0 }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Spacer adjusted to better match top spacing -->
    {{-- <div class="h-6 lg:h-8"></div> --}}

    @push('styles')
    <style>
        .carousel-container { position: relative; overflow: hidden; }
        .carousel-slide { display: none; opacity: 0; transition: opacity 0.45s ease; }
        .carousel-slide.active { display: block; opacity: 1; }
        .carousel-arrow { z-index: 30; }
        .carousel-dots button { width: 10px; height: 10px; border-radius: 9999px; border: none; background: rgba(255,255,255,0.6); }
        .carousel-dots button.active { background: white; width: 28px; border-radius: 9999px; }
    </style>
    @endpush

    @push('scripts')
    <script>
        (function(){
            const container = document.querySelector('.carousel-container');
            if (!container) return;
            const slides = Array.from(container.querySelectorAll('.carousel-slide'));
            const dotsWrap = container.querySelector('.carousel-dots');
            const prevBtn = container.querySelector('.carousel-arrow.prev');
            const nextBtn = container.querySelector('.carousel-arrow.next');
            let idx = 0;

            // build dots
            slides.forEach((s,i)=>{
                const btn = document.createElement('button');
                btn.className = i===0? 'active':'';
                btn.addEventListener('click', ()=>{ go(i); resetTimer(); });
                dotsWrap.appendChild(btn);
            });

            const dots = Array.from(dotsWrap.children);

            function go(i){
                idx = (i + slides.length) % slides.length;
                slides.forEach((s,si)=> s.classList.toggle('active', si===idx));
                dots.forEach((d,di)=> d.classList.toggle('active', di===idx));
            }

            prevBtn.addEventListener('click', ()=>{ go(idx-1); resetTimer(); });
            nextBtn.addEventListener('click', ()=>{ go(idx+1); resetTimer(); });

            let timer = setInterval(()=>{ go(idx+1); }, 4000);
            function resetTimer(){ clearInterval(timer); timer = setInterval(()=>{ go(idx+1); }, 4000); }
        })();
    </script>
    @endpush

@endsection
