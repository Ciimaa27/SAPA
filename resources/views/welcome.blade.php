<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>SAPA</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f5f5;
}

/* Navbar */
.navbar{
background:#ffffff;
}

/* Hero */
.hero{
background:#e8ddf5;
padding:100px 0;
}

.hero h1{
color:#ff8c00;
font-weight:700;
}

.hero p{
color:#6b4fa3;
}

/* Tentang */
.tentang{
background:#ffffff;
padding:80px 0;
}

.tentang h3{
color:#6b4fa3;
font-weight:700;
}

/* Guru */
.guru{
background:#e8ddf5;
padding:80px 0;
}

.footer{
background:#ffffff;
padding:40px 0;
}

</style>

</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm">
<div class="container">

<a class="navbar-brand fw-bold text-primary" href="#">SAPA</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="#">Beranda</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">Tentang SAPA</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">Guru</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">Panduan</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">Pengembang</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">Kontak</a>
</li>

<li class="nav-item">
<a class="btn btn-warning ms-3" href="/login">Masuk</a>
</li>

</ul>

</div>
</div>
</nav>


<!-- Hero -->
<section class="hero">
<div class="container">

<div class="row align-items-center">

<div class="col-md-6">--------

<p>TK Manhajul Husna</p>
                                
<h1>
Sistem Absensi dan Penjemputan Anak
Berbasis RFID dan Fingerprint
</h1>

</div>

<div class="col-md-6 text-center">
<!-- tempat gambar -->
</div>

</div>

</div>
</section>


<!-- Tentang SAPA -->
<section class="tentang">
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
<section class="guru">
<div class="container text-center">

<h3 class="text-primary">Guru</h3>

<p class="mt-3">
Informasi mengenai guru yang terdaftar dalam sistem SAPA.
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