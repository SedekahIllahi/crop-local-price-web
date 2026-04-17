<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Styles --}}
        @include('layouts.admin.partials.styles')
        

        <title>@yield('title') | {{ $prefs_composer['title'] }}</title>

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        @vite('resources/css/app.css')
    </head>

    <body>
        {{-- Start: Sidebar --}}
        @include('layouts.admin.partials.sidebar')
        {{-- End: Sidebar --}}

        {{-- Start: Topbar --}}
        @include('layouts.admin.partials.topbar')
        {{-- End: Topbar --}}

        <div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
            <!-- Start: Breadcrumb -->
            <div class="mb-[25px] md:flex items-center justify-between">
                <h5 class="mb-0">
                    @yield('title')
                </h5>
                <ol class="breadcrumb mt-[12px] md:mt-0">
                    @yield('breadcrumb')
                </ol>
            </div>
            <!-- End : Breadcrumb -->
            
            {{-- Start : Main Content --}}
            
            @yield('content')
            {{-- End : Main Content --}}

            {{-- Start: Footer --}}
            @include('layouts.admin.partials.footer')
            {{-- End: Footer --}}
        </div>
        {{-- Scripts --}}
        @include('layouts.admin.partials.scripts')
        {{-- SweetAlert2 --}}
        @include('layouts.admin.partials.alerts-script')
    </body>

</html>