<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SAPA</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS SENDIRI -->
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">

</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm">
<div class="container">
<a class="navbar-brand" href="#">SAPA</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">
<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
<li class="nav-item"><a class="nav-link" href="#tentang">Tentang SAPA</a></li>
<li class="nav-item"><a class="nav-link" href="#guru">Guru</a></li>
<li class="nav-item"><a class="nav-link" href="#panduan">Panduan</a></li>
<li class="nav-item"><a class="nav-link" href="#tim">Pengembang</a></li>
<li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>

<li class="nav-item">
<a class="btn btn-warning ms-3" href="/login">Masuk</a>
</li>
</ul>
</div>
</div>
</nav>

<!-- Hero -->
<section class="hero" id="hero">
<div class="container">
<div class="row align-items-center">

<div class="col-md-6">
<p>TK Manhajul Husna</p>

<h1>
Sistem Absensi dan Penjemputan Anak
Berbasis RFID dan Fingerprint
</h1>

<a href="/login" class="btn btn-warning mt-3">
Mulai Sekarang
</a>
</div>

<div class="col-md-6 text-center">
<img src="https://cdn-icons-png.flaticon.com/512/201/201818.png" class="img-fluid" width="350">
</div>

</div>
</div>
</section>

<!-- Tentang -->
<section class="tentang" id="tentang">
<div class="container text-center">

<h3>Tentang SAPA</h3>

<p class="mt-4">
SAPA merupakan sistem absensi dan penjemputan anak berbasis teknologi RFID dan fingerprint
yang bertujuan meningkatkan keamanan serta memudahkan monitoring kehadiran siswa
oleh sekolah maupun orang tua secara real-time.
</p>

</div>
</section>

<!-- Guru -->
<section class="guru" id="guru">
<div class="container text-center">

<h3 class="text-primary">Guru</h3>

<p class="mt-3">
Informasi mengenai guru yang terdaftar dalam sistem SAPA.
</p>

</div>
</section>

<!-- Panduan -->
<section class="tentang" id="panduan">
<div class="container text-center">

<h3>Panduan</h3>

<p class="mt-3 text-start d-inline-block">
1. Siswa melakukan scan RFID atau fingerprint saat datang.<br>
2. Data kehadiran otomatis tersimpan dalam sistem.<br>
3. Orang tua dapat memantau melalui dashboard.<br>
4. Penjemput melakukan scan saat menjemput siswa.
</p>

</div>
</section>

<!-- Tim -->
<section class="guru tim" id="tim">
<div class="container text-center">

<h3 class="text-primary">Tim Pengembang</h3>

<div class="row mt-4">

<div class="col-md-4">
<img src="{{ asset('foto/bila.png') }}" class="img-fluid">
<p class="mt-2">Nabila</p>
</div>

<div class="col-md-4">
<img src="{{ asset('foto/isma.png') }}" class="img-fluid">
<p class="mt-2">Isma</p>
</div>

<div class="col-md-4">
<img src="{{ asset('foto/imau.png') }}" class="img-fluid">
<p class="mt-2">Imau</p>
</div>
</div>

</div>
</section>

<!-- Kontak -->
<section class="tentang" id="kontak">
<div class="container text-center">

<h3>Kontak</h3>

<p class="mt-3">
Email: sapa@gmail.com <br>
Telepon: 08123456789
</p>

</div>
</section>

<!-- Footer -->
<footer class="footer text-center">
<div class="container">
<p>© 2026 SAPA - Sistem Absensi dan Penjemputan Anak</p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
