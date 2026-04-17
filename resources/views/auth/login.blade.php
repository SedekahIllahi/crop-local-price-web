@extends('layouts.admin.auth')

@section('title', 'Masuk')

@section('content')
<div class="bg-white dark:bg-[#0a0e19] py-[60px] md:py-[80px] lg:py-[135px]">
    <div class="mx-auto px-[12.5px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1255px]">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] items-center">
            <div class="flex justify-center items-center order-1 lg:order-2 xl:ltr:!-mr-[20px] 2xl:ltr:!-mr-[30px] rounded-[25px]">
    <img src="{{ URL::asset('images/logo_kabBantul.png') }}" 
        alt="logo-bantul" 
        class="max-h-[500px] w-auto">
</div>
            <div class="xl:ltr:pl-[120px] 2xl:ltr:pl-[120px] order-1 lg:order-2">
                
                <div class="my-[20px] md:my-[30px]">
                    <h1 class="font-black !leading-tight !mb-[10px] tracking-tight block" 
                        style="font-size: 55px !important; background: linear-gradient(to right, #1e293b, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Selamat datang di <span style="background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">SIGAPAN</span>
                    </h1>
                    <p class="font-bold text-[22px] md:text-[26px] text-[#445164] dark:text-gray-400 !mt-0 !mb-[35px] block !leading-tight">
                        Admin silahkan masuk dengan akun yang telah anda buat.
                    </p>
                </div>

                <div>
                    @if (session('status'))
                        <div class="font-medium text-sm text-green-600 border border-transparent rounded-md bg-green-50 px-4 py-3 mt-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="py-[1rem] px-[1rem] text-danger-500 bg-danger-50 border border-danger-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md" role="alert">
                            <b><i class="bi bi-x-octagon"></i> Error :</b>
                            <ul>
                                @error('email') <li>{{ $message }}</li> @enderror
                                @error('password') <li>{{ $message }}</li> @enderror
                            </ul>
                        </div>
                    @endif
                </div>

                <form action="{{ route('login') }}" method="POST">
                @csrf
                    <div class="mb-[15px] relative">
                        <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">Email</label>
                        <input name="email" type="email" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-emerald-500" placeholder="Masukkan email anda">
                    </div>

                    <div class="mb-[15px] relative">
                        <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">Password</label>
                        <input name="password" type="password" class="h-[45px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 focus:border-emerald-500" placeholder="Masukkan password anda">
                    </div>

                    <a href="/forgot-password" class="inline-block text-emerald-600 transition-all font-semibold hover:underline">
                        Forgot Password?
                    </a>

                    <button type="submit" class="md:text-md block w-full text-center transition-all rounded-md font-medium mt-[20px] md:mt-[25px] py-[12px] px-[25px] text-white bg-emerald-600 hover:bg-emerald-500">
                        <span class="flex items-center justify-center gap-[5px]">
                            <i class="material-symbols-outlined">login</i>
                            Sign In
                        </span>
                    </button>
                </form>

                <p class="mt-[15px] md:mt-[20px]">
                    <a href="{{ route('register') }}" class="text-emerald-600 transition-all font-bold hover:underline">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection