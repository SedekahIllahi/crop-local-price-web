<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Styles --}}
        @include('layouts.admin.partials.styles')
        

        <title>@yield('title') | {{ $prefs_composer['title'] }}</title>

        @vite('resources/css/app.css')
    </head>

    <body>
        {{-- Auth Content --}}
        @yield('content')
        
        {{-- Scripts --}}
        @include('layouts.admin.partials.scripts')
    </body>

</html>