<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIGAPAN Bantul')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class', // PENTING: Mengaktifkan mode dark berbasis class
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        },
                        colors: {
                            emerald: {
                                50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7',
                                400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857',
                                800: '#065f46', 900: '#064e3b',
                            }
                        }
                    }
                }
            }
        </script>
    @endif
    <script>
        // Satu logic untuk semua: Cek localStorage atau sistem OS
        const savedTheme = localStorage.getItem('color-theme');
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Gunakan warna yang sama persis dengan bg-gray-50 Tailwind agar gradasi tidak belang */
        .hero-pattern {
        background-color: #f9fafb; /* gray-50 */
        }
        .dark .hero-pattern {
        background-color: #111827; /* gray-900 */
        }
    </style>
    
    @stack('styles')
    @yield('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <nav id="navbar-top" class="fixed z-50 top-0 left-0 right-0 w-full mx-auto transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)]" 
        style="background: linear-gradient(180deg, #059669 0%, #065f46 100%); max-width: 100%;">
        <div id="navbar-container" class="w-full px-6 md:px-10 transition-all duration-500">
            <div class="flex justify-between items-center h-20 gap-4">
                <a href="{{ url('/sigapan') }}" class="flex items-center gap-4 shrink-0">
                    <img src="{{ asset('images/logo_kabBantul.png') }}" alt="Logo Bantul" class="h-10 md:h-12 w-auto object-contain">
                    <div class="hidden sm:flex flex-col border-l border-white/20 pl-4">
                        <span class="text-emerald-100 text-[10px] md:text-[11px] font-medium tracking-wide leading-tight uppercase whitespace-nowrap">
                            Pemerintah Kabupaten Bantul
                        </span>
                        <span class="text-white text-[11px] md:text-[13px] font-extrabold tracking-wide leading-tight uppercase whitespace-nowrap">
                            DINAS KOPERASI, UKM, PERINDUSTRIAN DAN PERDAGANGAN
                        </span>
                    </div>
                    <div class="flex sm:hidden flex-col border-l border-white/20 pl-3">
                        <span class="text-emerald-100 text-[9px] font-medium tracking-wide leading-tight uppercase">
                            Kab. Bantul
                        </span>
                        <span class="text-white text-[11px] font-extrabold tracking-wide leading-tight uppercase">
                            DKUKMPP
                        </span>
                    </div>
                </a>
                <div id="nav-links" class="hidden md:flex flex-1 justify-end items-center gap-2 lg:gap-4">
                    <a href="{{ url('/sigapan') }}" class="nav-item px-3 py-2 rounded-lg font-semibold text-sm transition text-white hover:text-emerald-300 whitespace-nowrap">
    Home
</a>

<a href="{{ url('/daftar-pasar') }}" class="px-3 py-2 rounded-lg font-semibold text-sm transition text-white hover:text-emerald-300 whitespace-nowrap">
    Pasar
</a>
                    <a href="{{ url('/stock-pasar') }}" class="px-3 py-2 rounded-lg font-semibold text-sm transition text-white hover:text-emerald-300 whitespace-nowrap">
    Stock
</a>
                    <button id="dropdownHargaLink" data-dropdown-toggle="dropdownHarga" class="flex items-center justify-between w-auto px-3 py-2 rounded-lg font-semibold text-sm transition text-white hover:text-emerald-300 whitespace-nowrap">
                        Informasi Harga 
                        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <div id="dropdownHarga"
                        class="z-10 hidden font-normal bg-white divide-y divide-gray-100
                                rounded-xl shadow-xl w-52 border border-gray-200
                                dark:bg-gray-800 dark:border-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                            <li>
                                <a href="{{ url('/perbandingan-harga') }}"
                                class="block px-4 py-2 mx-2 rounded-lg transition-all duration-200
                                        hover:bg-emerald-100 hover:text-emerald-800
                                        hover:ring-1 hover:ring-emerald-200 hover:translate-x-1
                                        dark:hover:bg-gray-700 dark:hover:text-white dark:hover:ring-0">
                                    Perbandingan Harga
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/matriks-harga') }}"
                                class="block px-4 py-2 mx-2 rounded-lg transition-all duration-200
                                        hover:bg-emerald-100 hover:text-emerald-800
                                        hover:ring-1 hover:ring-emerald-200 hover:translate-x-1
                                        dark:hover:bg-gray-700 dark:hover:text-white dark:hover:ring-0">
                                    Matriks Harga
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/trend-harga') }}"
                                class="block px-4 py-2 mx-2 rounded-lg transition-all duration-200
                                        hover:bg-emerald-100 hover:text-emerald-800
                                        hover:ring-1 hover:ring-emerald-200 hover:translate-x-1
                                        dark:hover:bg-gray-700 dark:hover:text-white dark:hover:ring-0">
                                    Trend Harga
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/tabel-harga') }}"
                                class="block px-4 py-2 mx-2 rounded-lg transition-all duration-200
                                        hover:bg-emerald-100 hover:text-emerald-800
                                        hover:ring-1 hover:ring-emerald-200 hover:translate-x-1
                                        dark:hover:bg-gray-700 dark:hover:text-white dark:hover:ring-0">
                                    Tabel Harga
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="#kontak" class="nav-item px-3 py-2 rounded-lg font-semibold text-sm transition text-white hover:text-emerald-300 whitespace-nowrap">Kontak</a>
                </div>
                <div class="flex items-center gap-2">
                    <button id="mobile-menu-btn" class="md:hidden text-white hover:bg-white/10 p-2 rounded-lg transition">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <button id="theme-toggle" type="button" class="text-white bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/20 rounded-lg text-sm p-2.5 transition-all">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    </button>
                </div>
            </div>
            <!-- Mobile Menu - Fullscreen Overlay -->
            <div id="mobile-menu" class="hidden md:hidden fixed inset-0 z-[100]">
                <!-- Dark overlay backdrop -->
                <div class="absolute inset-0 backdrop-blur-sm" style="background: rgba(0,0,0,0.6);" onclick="document.getElementById('mobile-menu').classList.add('hidden')"></div>
                <!-- Menu Panel -->
                <div class="absolute top-0 right-0 w-[85%] max-w-sm h-full shadow-2xl overflow-y-auto" style="background: linear-gradient(180deg, #065f46 0%, #064e3b 50%, #022c22 100%);">
                    <!-- Close Button -->
                    <div class="flex justify-between items-center p-5 border-b border-white/10">
                        <span class="text-white font-bold text-lg">Menu</span>
                        <button onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="max-w-lg mx-auto px-5 py-6">
                        
                        <!-- Main Nav Links -->
                        <div class="space-y-1">
                            <a href="{{ url('/sigapan') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl text-white font-semibold bg-white/5 hover:bg-white/15 active:scale-[0.98] transition-all duration-200">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span>Beranda</span>
                            </a>
                        </div>
                        
                        <!-- Pasar Section -->
                        <div class="mt-5">
                            <p class="px-4 mb-2 text-emerald-400/80 text-[11px] font-bold uppercase tracking-widest">Pasar</p>
                            <div class="space-y-1">
                                <a href="{{ url('/daftar-pasar') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 active:scale-[0.98] transition-all duration-200 {{ Request::is('daftar-pasar*') ? 'bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.3)] border border-white/20' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <span class="{{ Request::is('daftar-pasar*') ? 'font-bold text-white' : '' }}">Daftar Pasar</span>
                                </a>
                                <a href="{{ url('/stock-pasar') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 active:scale-[0.98] transition-all duration-200 {{ Request::is('stock-pasar*') ? 'bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.3)] border border-white/20' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <span class="{{ Request::is('stock-pasar*') ? 'font-bold text-white' : '' }}">Stock Pasar</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Informasi Harga Section -->
                        <div class="mt-5">
                            <p class="px-4 mb-2 text-emerald-400/80 text-[11px] font-bold uppercase tracking-widest">Informasi Harga</p>
                            <div class="space-y-1">
                                <a href="{{ url('/perbandingan-harga') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 active:scale-[0.98] transition-all duration-200 {{ Request::is('perbandingan-harga*') ? 'bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.3)] border border-white/20' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <span class="{{ Request::is('perbandingan-harga*') ? 'font-bold text-white' : '' }}">Perbandingan Harga</span>
                                </a>
                                <a href="{{ url('/matriks-harga') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 active:scale-[0.98] transition-all duration-200 {{ Request::is('matriks-harga*') ? 'bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.3)] border border-white/20' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    </div>
                                    <span class="{{ Request::is('matriks-harga*') ? 'font-bold text-white' : '' }}">Matriks Harga</span>
                                </a>
                                <a href="{{ url('/trend-harga') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 active:scale-[0.98] transition-all duration-200 {{ Request::is('trend-harga*') ? 'bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.3)] border border-white/20' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                    </div>
                                    <span class="{{ Request::is('trend-harga*') ? 'font-bold text-white' : '' }}">Trend Harga</span>
                                </a>
                                <a href="{{ url('/tabel-harga') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 active:scale-[0.98] transition-all duration-200 {{ Request::is('tabel-harga*') ? 'bg-white/10 shadow-[0_0_15px_rgba(255,255,255,0.3)] border border-white/20' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"></path><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M3 9h18"></path><path d="M3 15h18"></path></svg>
                                    </div>
                                    <span class="{{ Request::is('tabel-harga*') ? 'font-bold text-white' : '' }}">Tabel Harga</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Contact Button -->
                        <div class="mt-6 pt-5 border-t border-white/10">
                            <a href="#kontak" class="flex items-center justify-center gap-2 w-full px-6 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 active:scale-[0.98] transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Hubungi Kami
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="text-white font-sans" style="background: linear-gradient(180deg, #064e3b 0%, #022c22 100%);" id="kontak">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
                
                <div class="space-y-5 lg:col-span-2">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/logo_kabBantul.png') }}" alt="Logo Bantul" class="h-16 w-auto">
                        <div>
                            <h3 class="text-white font-bold text-lg leading-tight uppercase sm:whitespace-nowrap">Pemerintah Kabupaten Bantul</h3>
                            <p class="text-emerald-400 text-xs font-semibold mt-1">DINAS KOPERASI, UMKM, PERINDUSTRIAN, DAN PERDAGANGAN</p>
                        </div>
                    </div>
                    
                    <p class="text-white/70 text-sm leading-relaxed max-w-[55ch]">
                        Mewujudkan perdagangan yang adil, industri yang tangguh, dan koperasi yang mandiri untuk kesejahteraan masyarakat Bantul.
                    </p>

                    <div class="flex gap-3 pt-2">
                        <a href="https://www.facebook.com/kominfobantul/?locale=id_ID" class="w-9 h-9 flex items-center justify-center text-white transition-all duration-300">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
    </svg>
</a>
                        <a href="https://www.instagram.com/diskominfobantul/" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center text-white transition-all duration-300">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
    </svg>
</a>
                        <a href="https://x.com/kominfobantul" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center text-white transition-all duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/></svg>
    </a>
                        <a href="https://www.youtube.com/@bantultv_id" target="_blank" rel="noopener noreferrer" class="w-9 h-9 flex items-center justify-center text-white transition-all duration-300">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
    </a>
                    </div>
                </div>

                <div class="w-full">
                    <h4 class="text-white font-bold text-sm mb-5 uppercase tracking-wide border-b-2 border-amber-500 pb-2 inline-block">Tautan Terkait</h4>
                    <ul class="space-y-3">
                        <li><a href="https://bantulkab.go.id" target="_blank" class="text-white/80 hover:text-amber-400 text-sm transition-colors flex items-center gap-2"><span>→</span> Pemkab Bantul</a></li>
                        <li><a href="https://www.kemendag.go.id/" target="_blank" class="text-white/80 hover:text-amber-400 text-sm transition-colors flex items-center gap-2"><span>→</span> Kementerian Perdagangan</a></li>
                        <li><a href="https://badanpangan.go.id" target="_blank" class="text-white/80 hover:text-amber-400 text-sm transition-colors flex items-center gap-2"><span>→</span> Badan Pangan Nasional</a></li>
                        <li><a href="https://data.bantulkab.go.id/" class="text-white/80 hover:text-amber-400 text-sm transition-colors flex items-center gap-2"><span>→</span> Satu Data Bantul</a></li>
                    </ul>
                </div>

                <div class="w-full">
                    <h4 class="text-white font-bold text-sm mb-5 uppercase tracking-wide border-b-2 border-amber-500 pb-2 inline-block">Kontak Kami</h4>
                    <ul class="space-y-4 text-white/80 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Komplek Pemda II Manding Bantul</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:02742810422" class="hover:text-amber-400">0274 2810422</a>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-white/80">
                            <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:diskukmpp@bantulkab.go.id" class="hover:text-amber-400 transition-colors">diskukmpp@bantulkab.go.id</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <div class="border-t border-white/10 pt-6">
                <p class="text-white/50 text-sm text-center">
                    © {{ date('Y') }} <span class="text-white font-semibold">SIGAPAN</span> · Dinas Koperasi, UKM, Perindustrian dan Perdagangan Kabupaten Bantul
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika Dark Mode Toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Set icon awal
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });

            // Mobile Menu Logic
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
            }

            // Navbar Scroll Logic
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('navbar-top');
                if (window.scrollY > 50) {
                    navbar.style.maxWidth = '92%';
                    navbar.style.marginTop = '0,9rem'; 
                    navbar.classList.add('rounded-full', 'shadow-2xl');
                } else {
                    navbar.style.maxWidth = '100%';
                    navbar.style.marginTop = '0px';
                    navbar.classList.remove('rounded-full', 'shadow-2xl');
                }
            });
        });
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>