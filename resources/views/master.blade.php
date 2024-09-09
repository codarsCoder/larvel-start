<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo-ct.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-ct.png') }}">
        <title>
            Diyetisyen Fatıma Fırat
        </title>
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <!-- Nucleo Icons -->
        <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
        @yield('styles')
</head>
<body>
    {{-- @include('navbar') --}}
    <div class="container mt-5">
        @yield('main')
    </div>
    {{-- @include('footer') --}}

    <script src="{{ asset('assets/js/alpine.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
