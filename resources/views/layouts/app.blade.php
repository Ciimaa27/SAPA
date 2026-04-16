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

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🔥 SWEET ALERT (LOGOUT POPUP) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Yakin mau keluar?',
        text: "Kamu akan logout dari sistem",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6a4bc4',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, keluar!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    })
}
</script>

{{-- SCRIPT PER HALAMAN --}}
@stack('scripts')

</body>
</html>
