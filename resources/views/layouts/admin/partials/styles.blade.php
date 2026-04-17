<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/remixicon.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/apexcharts.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/simplebar.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/prism.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/jsvectormap.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/swiper-bundle.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/quill.snow.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/css/sweetalert2.min.css') }}">
{{-- <link rel="stylesheet" href="{{ URL::asset('assets/admin/css/style.css') }}"> --}}

{{-- Favicon --}}
<link rel="icon" type="image/png" href="{{ URL::asset($prefs_composer['favicon']) }}">

<!-- Font Family -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

<!-- Material Icons -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

@stack('styles')