@extends('layouts.admin.auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
    <!-- Reset Password -->
    <div class="bg-white dark:bg-[#0a0e19] min-h-screen flex items-center justify-center py-[60px]">
        <div class="mx-auto px-[12.5px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1255px]">
            <div class="max-w-[480px] mx-auto">
                
                
                <!-- Header -->
                <div class="my-[17px] md:my-[25px] text-center">
    
    <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 25px;">
        <img src="{{ URL::asset('images/logo_kabBantul.png') }}" 
            alt="Logo Kabupaten Bantul" 
            style="height: 60px !important; width: auto !important;">
        
        <span style="font-size: 34px; font-weight: 800; color: #065f46; font-family: sans-serif; letter-spacing: 1px; line-height: 1;">
            SIGAPAN
        </span>
    </div>

    <h1 class="font-semibold text-[22px] md:text-xl lg:text-2xl mb-[5px] md:mb-[10px]">
        Ubah kata sandi anda
    </h1>
    <p class="font-medium leading-[1.5] lg:text-md text-[#445164] dark:text-gray-400">
        Masukkan kata sandi baru di bawah ini untuk mengubah kata sandi Anda.
    </p>
</div>

                <!-- Alert Messages -->
                @if (session('status'))
                    <div class="font-medium text-sm text-green-600 border border-transparent rounded-md bg-green-50 px-4 py-3 mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="py-4 px-4 text-red-500 bg-red-50 border border-red-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md mb-4" role="alert">
                        <b><i class="bi bi-x-octagon"></i> Error:</b>
                        <ul class="mt-2 ml-4 list-disc">
                            @foreach ($errors->get('password') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    
                    

                    <div class="space-y-[20px]">
    <div>
        <label for="new-password" class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">
            Kata Sandi Baru*
        </label>
        <div class="relative">
            <input 
                type="password" 
                name="password" 
                id="new-password" 
                placeholder="Ketik kata sandi baru"
                class="h-[45px] w-full rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] pr-[50px] outline-0 transition-all focus:border-emerald-500 placeholder:text-gray-400"
                required
            >
            <button 
                type="button" 
                class="toggle-password absolute right-[15px] top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-emerald-500 transition-colors"
                data-target="new-password">
                <i class="ri-eye-off-line"></i>
            </button>
        </div>
    </div>

    <div>
        <label for="confirm-password" class="mt-[20px] mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">
    Masukkan Kembali Kata Sandi Baru*
</label>
<div class="relative">
    <input 
        type="password" 
        name="confirm_password" 
        id="confirm-password" 
        placeholder="Ketik ulang kata sandi baru untuk konfirmasi"
        class="h-[45px] w-full rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] pr-[50px] outline-0 transition-all focus:border-emerald-500 placeholder:text-gray-400"
        required
    >
    <button 
        type="button" 
        class="toggle-password absolute right-[15px] top-1/2 -translate-y-1/2 text-xl text-gray-400 hover:text-emerald-500 transition-colors"
        data-target="confirm-password">
        <i class="ri-eye-off-line"></i>
    </button>
        </div>
    </div>
</div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="md:text-md block w-full text-center transition-all rounded-md font-medium mt-[20px] md:mt-[25px] py-[12px] px-[25px] text-white bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700"
                    >
                        <span class="flex items-center justify-center gap-[5px]">
                            <i class="material-symbols-outlined">autorenew</i>
                            Reset Password
                        </span>
                    </button>
                </form>

                <!-- Back to Sign In -->
                <p class="mt-[15px] md:mt-[20px] text-center">
                    Back to <a href="{{ route('login') }}" class="text-emerald-500 transition-all font-semibold hover:underline">Sign In</a>
                </p>

            </div>
        </div>
    </div>
    <!-- End Reset Password -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('ri-eye-off-line');
                        icon.classList.add('ri-eye-line');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('ri-eye-line');
                        icon.classList.add('ri-eye-off-line');
                    }
                });
            });
        });
    </script>
    @endpush
@endsection