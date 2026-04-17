@extends('layouts.admin.auth')

@section('title', 'Lupa Kata Sandi')

@section('content')
    
    <!-- Forgot Password -->
    <div class="bg-white dark:bg-[#0a0e19] py-[60px] md:py-[80px] lg:py-[135px]">
        <div class="mx-auto px-[12.5px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1255px]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] items-center">
                <!-- Image Section -->
                <div class="xl:ltr:-mr-[25px] xlrtl:-ml-[25px] 2xl:ltr:-mr-[45px] 2xl:rtl:-ml-[45px] rounded-[25px] order-2 lg:order-1" style="transform: translateX(100px);">
    <img src="{{ URL::asset(path: 'images/logo_kabBantul.png') }}" alt="forgot-password-image" class="rounded-[25px]">
</div>

                <!-- Form Section -->
                <div class="xl:ltr:pl-[90px] xl:rtl:pr-[90px] 3xl:ltr:pl-[120px] 3xl:rtl:pr-[120px] order-1 lg:order-2">
                    <h2 class="font-extrabold" style="color: #065f46; font-size: 3.75rem;">SIGAPAN</h2>
                    
                    <div class="my-[17px] md:my-[25px]">
                        <h1 class="!font-semibold !text-[22px] md:!text-xl lg:!text-2xl !mb-[5px] md:!mb-[7px]">
                            Lupa Kata Sandi?
                        </h1>
                        <p class="font-medium lg:text-md text-[#445164] dark:text-gray-400">
                            Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                        </p>
                    </div>

                    {{-- Alert Messages --}}
                    @if (session('status'))
                        <div class="font-medium text-sm text-green-600 border border-transparent rounded-md bg-green-50 px-4 py-3 mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="py-[1rem] px-[1rem] text-danger-500 bg-danger-50 border border-danger-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md mb-4" role="alert">
                            <b><i class="bi bi-x-octagon"></i> Error :</b>
                            <ul>
                                @foreach ($errors->get('email') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-[15px] relative">
                            <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">
                                Email Address
                            </label>
                            <input type="email" 
                                class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-emerald-500"
                                placeholder="Masukkan email anda" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                autocomplete="email" />
                        </div>

                        <button type="submit" class="md:text-md block w-full text-center transition-all rounded-md font-medium mt-[20px] md:mt-[25px] py-[12px] px-[25px] text-white bg-emerald-600 hover:bg-emerald-500">
                            <span class="flex items-center justify-center gap-[5px]">
                                <i class="material-symbols-outlined">
                                    mail
                                </i>
                                Kirim Email Reset Password
                            </span>
                        </button>
                    </form>

                    <p class="mt-[15px] md:mt-[20px]">
                        Kembali ke <a href="{{ route('login') }}" class="text-emerald-600 transition-all font-semibold hover:underline">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Forgot Password -->
@endsection