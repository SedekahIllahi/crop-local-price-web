@extends('layouts.admin.auth')

@section('title', 'Reset Password')

@push('styles')
<style>
    .gradient-header {
        background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
        position: relative;
        padding-top: 4rem;
        padding-bottom: 3rem;
    }

    .hero-pattern {
        background-image: 
            radial-gradient(circle at 2px 2px, rgba(16, 185, 129, 0.08) 1px, transparent 1px);
        background-size: 25px 25px;
    }

    .dark .hero-pattern {
        background-image: 
            radial-gradient(circle at 2px 2px, rgba(16, 185, 129, 0.05) 1px, transparent 1px);
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .glass-input:hover {
        border-color: #059669 !important;
        box-shadow: 0 0 12px rgba(5, 150, 105, 0.1) !important;
    }

    .glass-input:focus {
        border-color: #059669 !important;
        box-shadow: 0 0 12px rgba(5, 150, 105, 0.2) !important;
    }

    .dark .glass-input {
        background-color: #020617 !important;
        border-color: #374151 !important;
    }

    .dark .glass-input:hover {
        border-color: #10b981 !important;
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.2) !important;
    }

    .dark .glass-input:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.25) !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        box-shadow: 0 8px 16px rgba(5, 150, 105, 0.3);
        transform: translateY(-2px);
    }

    .dark .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    }

    .dark .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
    }
</style>
@endpush

@section('content')
    <!-- Light/Dark Mode Button -->
    <button type="button" class="light-dark-toggle leading-none inline-block transition-all text-emerald-600 dark:text-emerald-400 absolute top-[20px] md:top-[25px] ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px] hover:scale-110 transform transition" id="light-dark-toggle">
        <i class="material-symbols-outlined !text-[24px] md:!text-[26px]">
            light_mode
        </i>
    </button>

    <!-- Reset Password -->
    <div class="bg-white dark:bg-[#020617] py-[60px] md:py-[80px] lg:py-[100px] hero-pattern">
        <div class="mx-auto px-[12.5px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1255px]">
            <!-- Header Section -->
            <div class="text-center mb-[50px] md:mb-[60px]">
                <div class="flex justify-center mb-6">
                    <img src="{{ URL::asset('images/logo_kabBantul.png') }}" alt="logo-bantul" class="h-20 w-auto">
                </div>
                <h1 class="font-black text-4xl md:text-5xl mb-4" 
                    style="background: linear-gradient(to right, #059669, #10b981); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    SIGAPAN
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">
                    Sistem Informasi Harga Pangan Kabupaten Bantul
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[40px] items-center">
                <!-- Image Section -->
                <div class="order-2 lg:order-1 rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ URL::asset('assets/admin/images/reset-password.jpg') }}" alt="reset-password-image" class="w-full h-full object-cover">
                </div>

                <!-- Form Section -->
                <div class="order-1 lg:order-2">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 md:p-10 shadow-xl border border-gray-100 dark:border-gray-800">
                        <div class="mb-8">
                            <h2 class="font-bold text-3xl md:text-4xl text-gray-900 dark:text-white mb-3">
                                Reset Password
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed">
                                Masukkan password baru Anda dan konfirmasi untuk melanjutkan keamanan akun.
                            </p>
                        </div>

                        {{-- START: Alert Message --}}
                        <div class="mb-6">
                            @if (session('status'))
                                <div class="text-sm text-green-600 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-4 py-3 font-medium">
                                    <i class="bi bi-check-circle"></i> {{ session('status') }}
                                </div>
                            @endif
                            <!-- Validation Errors -->
                            @if ($errors->any())
                                <div class="text-sm text-red-600 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg px-4 py-3 font-medium" role="alert">
                                    <i class="bi bi-exclamation-circle"></i> <strong>Error:</strong>
                                    <ul class="mt-2 list-disc list-inside">
                                        @foreach ($errors->get('password') as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        {{-- END: Alert Message --}}

                        {{-- START: Form reset password --}}
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-6 relative" id="passwordHideShow1">
                                <label class="mb-3 text-gray-700 dark:text-gray-300 font-semibold block text-sm">
                                    <i class="bi bi-envelope-fill text-emerald-600"></i> Email
                                </label>
                                <input type="email" class="glass-input h-[48px] rounded-lg text-gray-900 dark:text-white border border-gray-200 px-4 block w-full outline-0 placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm" 
                                    name="email" id="email" placeholder="Masukkan email Anda" required value="{{ request('email') }}">
                            </div>

                            <!-- New Password Field -->
                            <div class="mb-6 relative" id="passwordHideShow2">
                                <label class="mb-3 text-gray-700 dark:text-gray-300 font-semibold block text-sm">
                                    <i class="bi bi-shield-lock text-emerald-600"></i> Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" class="glass-input h-[48px] rounded-lg text-gray-900 dark:text-white border border-gray-200 px-4 block w-full outline-0 placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm" 
                                        name="password" id="new-password" placeholder="Masukkan password baru" required>
                                    <button class="absolute text-lg ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition" id="toggleButton2" type="button">
                                        <i class="ri-eye-off-line"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-8 relative" id="passwordHideShow3">
                                <label class="mb-3 text-gray-700 dark:text-gray-300 font-semibold block text-sm">
                                    <i class="bi bi-shield-check text-emerald-600"></i> Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <input type="password" class="glass-input h-[48px] rounded-lg text-gray-900 dark:text-white border border-gray-200 px-4 block w-full outline-0 placeholder:text-gray-400 dark:placeholder:text-gray-500 text-sm" 
                                        name="password_confirmation" id="new-password-confirmation" placeholder="Konfirmasi password baru" required>
                                    <button class="absolute text-lg ltr:right-4 rtl:left-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-emerald-600 transition" id="toggleButton3" type="button">
                                        <i class="ri-eye-off-line"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-primary w-full text-center rounded-lg font-bold py-3 px-4 text-white text-sm md:text-base transition-all duration-300">
                                <span class="flex items-center justify-center gap-2">
                                    <i class="material-symbols-outlined text-xl">lock_reset</i>
                                    Reset Password
                                </span>
                            </button>
                        </form>
                        {{-- END: Form reset password --}}

                        <!-- Back to Sign In -->
                        <p class="mt-6 text-center text-gray-600 dark:text-gray-400">
                            Kembali ke <a href="{{ route('login') }}" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline transition">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Reset Password -->
@endsection