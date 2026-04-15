<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPA - @yield('title')</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- GLOBAL CSS -->
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">

    {{-- CSS PER HALAMAN --}}
    @stack('styles')
</head>

<body>

<div class="layout">

    {{-- SIDEBAR --}}
    @yield('sidebar')

    <div class="content">

        {{-- TOPBAR --}}
        @include('layouts.topbar')

        {{-- CONTENT --}}
        @yield('content')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
