{{-- Start list menus --}}
<div class="accordion">

    {{-- START: Title Section Menus --}}
    <span class="block relative font-medium uppercase text-gray-400 mb-[10px] text-xs">SECTION MENU</span>
    {{-- END: Title Section Menus --}}

    @foreach ($navs as $nav)
        {{-- Route prefix to determine menu activity  --}}
        @php
            /* 
            * ========================================================================================================
            * DISINI url nav yang bersumber dari DB yang tadinya hanya berupa nama route (ex : dashboard.index) telah diubah menjadi URL hasil dari route($nav->url)
            * Contoh jadinya, $nav['url'] = route('dashboard.index'), yang dimana hasilnya akan berbentuk URL (ex $nav['url'] : http://127.0.0.0:8000/admin/dashboard)
            * ========================================================================================================
            * ========================================================================================================
            * $navUrl, untuk mengambil path url dari menu yang diambil dari database
            * fungsi pase_url(), Fungsi ini digunakan untuk memecah sebuah URL menjadi komponen-komponennya, seperti skema, host, path, query, dll.
            * PHP_URL_PATH untuk mengambil path url yang berasal dari parse_url() yanng sebelumnya berupa array (skema, host, path, query, dll)
            * ltrim() untuk menghapus karakter spasi atau karakter lain dari sisi kiri string. Disini digunakan untuk menghapus karakter "/" dari sisi kiri string
            * Contoh: $navUrl = 127.0.0.0:8000/admin, maka hasilnya adalah admin
            * ========================================================================================================
            */
            $navUrl = ltrim(parse_url($nav['url'], PHP_URL_PATH), '/');
            /* 
            * ========================================================================================================
            * $routeName, untuk mengambil nama route yang sedang aktif
            * Contoh: routeName = admin.dashboard
            * ========================================================================================================
            */
            $routeName = Route::currentRouteName();
            /* 
            * ========================================================================================================
            * $routePrefix, untuk mengambil prefix dari route yang sedang aktif
            * Contoh: routeName = admin.dashboard, maka prefixnya adalah admin
            * ========================================================================================================
            */
            $routePrefix = explode('.', $routeName)[0];
            
            // Cek active state berdasarkan URL saat ini
            $currentUrl = url()->current();
            $isActive = ($routePrefix == $navUrl) || ($currentUrl == $nav['url']);
        @endphp

        {{-- Determines between a single menu and a menu that has children --}}
        @if (count($nav['child']) == 0)
            {{-- Single menu --}}
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ $nav['url'] }}" class="{{ $isActive ? 'active' : '' }} accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        {{ $nav['icon'] }}
                    </i>
                    <span class="title leading-none">
                        {{ $nav['name'] }}
                    </span>
                </a>
            </div>

            {{-- START: Menu Update Harga untuk User ID 3-7 (Lurah) - Setelah Dashboard --}}
            @if($loop->first)
                @php
                    $currentUserId = auth()->user()->id ?? null;
                    $isLurahUser = $currentUserId && in_array($currentUserId, [3, 4, 5, 6, 7]);
                    $isLurahPage = Request::is('lurah') || Request::is('lurah/*');
                    $isLurahStokPage = Request::is('lurah/stok') || Request::is('lurah/stok/*');
                @endphp
                @if($isLurahUser)
                <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                    <a href="{{ route('lurah') }}" class="{{ $isLurahPage && !$isLurahStokPage ? 'active' : '' }} accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                        <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                            price_change
                        </i>
                        <span class="title leading-none">
                            Update Harga
                        </span>
                    </a>
                </div>
                {{-- Menu Update Stock untuk User ID 3-7 (Lurah) --}}
                <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                    <a href="{{ route('lurah.stok.index') }}" class="{{ $isLurahStokPage ? 'active' : '' }} accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                        <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                            inventory_2
                        </i>
                        <span class="title leading-none">
                            Update Stock
                        </span>
                    </a>
                </div>
                @endif
            @endif
            {{-- END: Menu Update Harga untuk User ID 3-7 (Lurah) --}}
        @else

        {{-- Menu with children --}}
        @php
            /* 
            * ========================================================================================================
            * $urlCurrent, untuk mengambil url yang sedang aktif
            *
            * Request::segments() untuk mengambil segment url yang sedang aktif
            * Contoh: Request::segments() = ['admin', 'dashboard'], maka hasilnya adalah admin/dashboard
            * Disini, karena induk menu, maka yang diambil hanya segment pertama saja
            * 
            * url('/') untuk mengambil url dari root project
            * Contoh: url('/') = 127.0.0.1:8000
            *
            * Lalu jika digabungkan, maka hasilnya adalah
            * Contoh: urlCurrent = 127.0.0.1:8000/admin/dashboard
            * ========================================================================================================

            * ========================================================================================================
            * Request::is untuk mengecek apakah request yang sedang aktif, sama dengan request yang diinginkan
            * Contoh: kita mendapati pengecekan dari parse_url($nav['url'], PHP_URL_PATH) yakni url dari DB yang hasilnya adalah admin/dashboard
            * Lalu kita cek [dengan menggunakan Request::is()] apakah request yang sedang aktif di browser, sama dengan admin/dashboard atau tidak
            * ========================================================================================================

            * ========================================================================================================
            * collect($nav['child'])->pluck('url') untuk mengambil kumpulan url dari child menu [anak dari parent menu tersebut] dari DB yang akan disajikan dalam bentuk array
            * Contoh: collect($nav['child'])->pluck('url') = ['admin/dashboard', 'admin/profile']
            * contains($urlCurrent) untuk mengecek apakah url yang sedang aktif, terdapat dalam array yang dihasilkan oleh collect($nav['child'])->pluck('url')
            * ========================================================================================================
            */
            $urlCurrent = url('/') . '/' . Request::segments()[0];
            
            $isHaveSegment4 = false;
            // Tambahkan URL segment ke 2 jika ada
            if (isset(Request::segments()[1])) {
                $urlCurrent .= '/' . Request::segments()[1];
                $isHaveSegment4 = false;
            }
            // Tambahkan URL segment ke 3 jika ada
            if (isset(Request::segments()[2])) {
                $urlCurrent .= '/' . Request::segments()[2];
                $isHaveSegment4 = false;
            }
            // Tambahkan URL segment ke 4 jika ada
            if (isset(Request::segments()[3])) {
                $urlCurrent .= '/' . Request::segments()[3];
                $isHaveSegment4 = true;
            }
        @endphp

        {{-- START: Menu Parent With Childs Menu --}}
        <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
            {{-- START: Parent Menu --}}
            <button class="accordion-button toggle {{ Request::is(ltrim(parse_url($nav['url'], PHP_URL_PATH), '/')) || collect($nav['child'])->pluck('url')->contains($urlCurrent)? 'open active': '' }} flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                    {{ $nav['icon'] }}
                </i>
                <span class="title leading-none">
                    {{ $nav['name'] }}
                </span>
            </button>
            {{-- END: Parent Menu --}}

            {{-- START: Menu Childs --}}
            @php
                // Cek apakah ada sub_child yang aktif
                $hasActiveSubChild = false;
                foreach ($nav['child'] as $child) {
                    if (isset($child['sub_child']) && count($child['sub_child']) > 0) {
                        if (collect($child['sub_child'])->pluck('url')->contains($urlCurrent)) {
                            $hasActiveSubChild = true;
                            break;
                        }
                    }
                }
            @endphp
            <div class="accordion-collapse" style="display: {{ Request::is(ltrim(parse_url($nav['url'], PHP_URL_PATH), '/')) || collect($nav['child'])->pluck('url')->contains($urlCurrent) || $hasActiveSubChild ? 'block': 'none' }};">
                <div class="pt-[4px]">
                    <ul class="sidebar-sub-menu" id="{{ $nav['name'] }}-item-list">
                        {{-- START: Foreach List Childs Menu --}}
                        @foreach ($nav['child'] as $child)
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                {{-- Check if the child has subChild --}}
                                @if ($child['sub_child'] && count($child['sub_child']) > 0)
                                    {{-- START: Sub-Child Menu --}}
                                    @php
                                        // Cek apakah sub_child aktif
                                        $isSubChildActive = collect($child['sub_child'])->pluck('url')->contains($urlCurrent);
                                    @endphp
                                    <button type="button" class="{{ Request::is(ltrim(parse_url($child['url'], PHP_URL_PATH), '/')) || $isSubChildActive ? 'open active': '' }} sidemenu-link toggle rounded-md flex items-center relative transition-all font-medium text-gray-500 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                        {{ $child['name'] }}
                                    </button>
                                    
                                    {{-- START: Foreach Sub-Child Menu --}}
                                    <div class="accordion-collapse" style="display: {{ Request::is(ltrim(parse_url($child['url'], PHP_URL_PATH), '/')) || $isSubChildActive ? 'block': 'none' }};">
                                        <div class="pt-[6px] ltr:pl-[20px] rtl:pr-[20px]">
                                            <ul class="sidebar-sub-menu">
                                                {{-- @dd($urlCurrent) --}}
                                                @foreach ($child['sub_child'] as $subChild)
                                                    <li class="sidemenu-item mb-[4px] last:mb-0">
                                                        <a href="{{ $subChild['url'] }}" class="{{ $urlCurrent == $subChild['url'] ? 'active' : '' }} sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                                            @if (strlen($subChild['name']) > 25)
                                                                {{ substr($subChild['name'], 0, 25) . '...' }}
                                                            @else
                                                                {{ $subChild['name'] }}
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    {{-- END: Foreach Sub-Child Menu --}}

                                    {{-- END: Sub-Child Menu --}}
                                @else
                                    {{-- START: Child Menu not Have SubChild --}}
                                    <a href="{{ $child['url'] }}" class="{{ $urlCurrent == $child['url'] ? 'active' : '' }} sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                        {{ $child['name'] }}
                                    </a>
                                    {{-- END: Child Menu not Have SubChild --}}
                                @endif
                            </li>
                        @endforeach
                        {{-- END: Foreach List Childs Menu --}}
                    </ul>
                </div>
            </div>
            {{-- END: Menu Childs --}}
        </div>
        {{-- END: Menu Parent With Childs Menu --}}
        @endif

        {{-- START: Menu Pasar (tampil setelah menu pertama) --}}
        @if($loop->first)
            @php
                $userRoleIds = auth()->user()->roles->pluck('id')->toArray();
                $allowedRoleIds = [1, 2, 3];
                $canSeePasarMenu = count(array_intersect($userRoleIds, $allowedRoleIds)) > 0;
                $pasarList = \App\Models\Pasar::whereIn('id', [1, 2, 3, 4, 5])->get();
                
                // Cek apakah sedang berada di halaman pasar
                $currentPasarSlug = Request::segment(2); // segment: pasar/{slug}/harga-bapok
                $isPasarPage = Request::segment(1) === 'pasar' && Request::segment(3) === 'harga-bapok';
            @endphp
            @if($canSeePasarMenu)
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                {{-- START: Parent Menu Pasar --}}
                <button class="accordion-button toggle {{ $isPasarPage ? 'open active' : '' }} flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        store
                    </i>
                    <span class="title leading-none">
                        Update Harga Pasar
                    </span>
                </button>
                {{-- END: Parent Menu Pasar --}}

                {{-- START: Menu Childs Pasar --}}
                <div class="accordion-collapse" style="display: {{ $isPasarPage ? 'block' : 'none' }};">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu" id="pasar-item-list">
                            @foreach ($pasarList as $pasar)
                                @php
                                    // Ekstrak slug dari nama pasar (hilangkan prefix "Pasar " dan buat lowercase)
                                    $pasarSlug = \Illuminate\Support\Str::slug(str_replace('Pasar ', '', $pasar->nama_pasar));
                                    $isActivePasar = $currentPasarSlug === $pasarSlug;
                                @endphp
                                <li class="sidemenu-item mb-[4px] last:mb-0">
                                    <a href="{{ route('pasar.harga-bapok.index', $pasarSlug) }}" class="{{ $isActivePasar ? 'active' : '' }} sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                        {{ $pasar->nama_pasar }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- END: Menu Childs Pasar --}}
            </div>

            {{-- START: Menu Stok Pasar (hanya untuk role ID 1) --}}
            @php
                $isRoleId1 = in_array(1, $userRoleIds);
                $currentStokPasarSlug = Request::segment(2);
                $isStokPasarPage = Request::segment(1) === 'pasar' && Request::segment(3) === 'stok-bapok';
            @endphp
            @if($isRoleId1)
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                {{-- START: Parent Menu Stok Pasar --}}
                <button class="accordion-button toggle {{ $isStokPasarPage ? 'open active' : '' }} flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        inventory_2
                    </i>
                    <span class="title leading-none">
                        Update Stok Pasar
                    </span>
                </button>
                {{-- END: Parent Menu Stok Pasar --}}

                {{-- START: Menu Childs Stok Pasar --}}
                <div class="accordion-collapse" style="display: {{ $isStokPasarPage ? 'block' : 'none' }};">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu" id="stok-pasar-item-list">
                            @foreach ($pasarList as $pasar)
                                @php
                                    $pasarSlugStok = \Illuminate\Support\Str::slug(str_replace('Pasar ', '', $pasar->nama_pasar));
                                    $isActiveStokPasar = $currentStokPasarSlug === $pasarSlugStok && $isStokPasarPage;
                                @endphp
                                <li class="sidemenu-item mb-[4px] last:mb-0">
                                    <a href="{{ route('pasar.stok-bapok.index', $pasarSlugStok) }}" class="{{ $isActiveStokPasar ? 'active' : '' }} sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                        {{ $pasar->nama_pasar }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- END: Menu Childs Stok Pasar --}}
            </div>
            @endif
            {{-- END: Menu Stok Pasar --}}
            @endif
        @endif
        {{-- END: Menu Pasar --}}

    @endforeach
    <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
        {{-- START: Logout Button --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
            <button type="submit" id="logout-btn" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                    logout
                </i>
                <span class="title leading-none">
                    Logout
                </span>
            </button>
        </form>
        <script>
            document.getElementById('logout-form').addEventListener('submit', function(e) {
                setTimeout(function() {
                    window.location.href = '/'; // redirect ke home setelah logout
                }, 500);
            });
        </script>
        {{-- END: Logout Button --}}
    </div>
</div>
{{-- End List Menus --}}