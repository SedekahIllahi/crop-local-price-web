<!-- Sidebar -->
<div class="sidebar-area bg-white dark:bg-[#0c1427] fixed overflow-hidden z-[50] top-0 h-screen transition-all rounded-r-md" id="sidebar-area">
    <div class="logo bg-white dark:bg-[#0c1427] border-b border-gray-100 dark:border-[#172036] px-[15px] pt-[19px] pb-[15px] absolute z-[2] right-0 top-0 left-0">
        <a href="{{ route('dashboard') }}" class="transition-none relative flex items-center justify-center w-full">
            <img src="{{ URL::asset('images/logo_kabBantul.png') }}" alt="logo-bantul" class="h-10 w-auto">
            <span class="font-bold text-black dark:text-white relative ltr:ml-[10px] rtl:mr-[10px] top-px text-xl tracking-wide cursor-pointer">
                SIGAPAN
            </span>
        </a>
        <button type="button" class="burger-menu inline-block absolute z-[3] top-[24px] ltr:right-[15px] rtl:left-[15px] transition-all hover:text-primary-500" id="hide-sidebar-toggle2">
            <i class="material-symbols-outlined">
                close
            </i>
        </button>
    </div>
    <div class="pt-[89px] px-[25px] pb-[20px] h-screen" data-simplebar>
        {{-- Start : List Menu --}}
        @include('layouts.admin.partials.menu-list')
        {{-- End : List Menu --}}
    </div>
</div>
<!-- End Sidebar -->