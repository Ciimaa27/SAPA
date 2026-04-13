<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

   <!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">SAPA</a>

        <!-- Toggle  -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="#guru">Guru</a></li>
                <li class="nav-item"><a class="nav-link" href="#panduan">Panduan</a></li>
                <li class="nav-item"><a class="nav-link" href="#tim">Pengembang</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                <li class="nav-item ms-3">
                    <a class="btn btn-warning btn-masuk" href="/login">Masuk</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- HERO -->
<section class="hero" id="hero" style="background: url('{{ asset('foto/tk.jpg') }}') center/cover no-repeat;">
    <div class="container position-relative text-white">
        <div class="row align-items-center" style="min-height: 100vh;">
            <div class="col-md-6">
                <p>Politeknik Negeri Banjarmasin</p>
                <h1>Sistem Absensi dan Penjemputan Anak Berbasis RFID dan Fingerprint</h1>

                <a href="/login" class="btn btn-warning mt-3">Mulai Sekarang</a>
            </div>
        </div>
    </div>
</section>


<!-- TENTANG -->
<section class="tentang-modern" id="tentang">
    <div class="container">
 <h3 class="text-center mb-5 judul-tentang">Tentang SAPA</h3>
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="tentang-text">
                    <p class="tentang-text">
                        SAPA (Sistem Absensi dan Penjemputan Anak) merupakan sistem informasi berbasis web yang dirancang
                        untuk meningkatkan keamanan dan efisiensi dalam proses absensi serta penjemputan siswa di lingkungan sekolah.
                    </p>

                    <p class="tentang-text">
                        Sistem ini memanfaatkan teknologi RFID dan fingerprint untuk melakukan identifikasi
                        secara otomatis dan akurat. Melalui sistem SAPA, data kehadiran siswa dapat tercatat secara real-time dan terintegrasi.
                    </p>

                    <p class="tentang-text">
                        Hal ini memudahkan pihak sekolah dalam melakukan monitoring serta memberikan informasi yang transparan
                         kepada orang tua. Selain itu, fitur penjemputan memastikan bahwa siswa dijemput oleh pihak yang berwenang.
                    </p>

                    <p class="tentang-text">
                        Dengan adanya SAPA, diharapkan proses administrasi menjadi lebih efektif,
                        mengurangi kesalahan pencatatan manual, serta mendukung penerapan teknologi dalam lingkungan pendidikan.
                    </p>
            </div>

            <div class="col-md-6 posisi-gambar">
                <img src="{{ asset('foto/tk1.png') }}" class="img-main">
                <img src="{{ asset('foto/tk2.png') }}" class="img-back">
            </div>
        </div>
    </div>
</section>


<!-- GURU -->
<section class="guru" id="guru">
    <div class="container text-center">
        <h3 class="judul-tentang mb-3">Guru</h3>
        <p class="mb-5">
            Informasi mengenai guru yang terdaftar dalam sistem SAPA.
        </p>

        <div class="row justify-content-center g-4">

        <!-- WRAPPER SCROLL -->
        <div class="guru-scroll">

            <!-- CARD -->
            <div class="guru-card">
                <img src="{{ asset('foto/guru1.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Wali Kelas A</span>
                </div>
            </div>

            <div class="guru-card">
                <img src="{{ asset('foto/guru2.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Wali Kelas B</span>
                </div>
            </div>

            <div class="guru-card">
                <img src="{{ asset('foto/guru3.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Guru Pendamping</span>
                </div>
            </div>

            <div class="guru-card">
                <img src="{{ asset('foto/guru4.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Guru Seni</span>
                </div>
            </div>

            <div class="guru-card">
                <img src="{{ asset('foto/guru5.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Guru Olahraga</span>
                </div>
            </div>
            <div class="guru-card">
                <img src="{{ asset('foto/guru6.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Guru Pendamping</span>
                </div>
            </div>

            <div class="guru-card">
                <img src="{{ asset('foto/guru7.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Guru Seni</span>
                </div>
            </div>

            <div class="guru-card">
                <img src="{{ asset('foto/guru8.jpg') }}" class="guru-img">
                <div class="card-body">
                    <h5>Nama Guru</h5>
                    <span class="badge bg-warning text-dark">Guru Olahraga</span>
                </div>
            </div>

        </div>
    </div>
</section>

        </div>
    </div>
</section>


<!-- PANDUAN -->
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


<!-- TIM -->
<section class="guru tim-section" id="tim">
    <div class="container text-center">
        <h3 class="text-primary mb-5">Tim Pengembang</h3>

        <div class="row justify-content-center g-4">

            <!--CARD-->

            <div class="col-md-4">
                <div class="card tim-card">
                    <img src="{{ asset('foto/bila.png') }}" class="tim-img">
                    <div class="card-body">
                        <h5>Radita Nabila Shofa</h5>
                        <p>
                            <span class="role">UI/UX Designer</span><br>
                            <span class="role">Frontend Developer</span><br>

                            <span class="text-muted">
                                Bertanggung jawab dalam desain UI/UX dan pengembangan tampilan sistem.
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card tim-card">
                    <img src="{{ asset('foto/isma.png') }}" class="tim-img">
                    <div class="card-body">
                        <h5>Ismatul Hawa</h5>
                        <p>
                            <span class="role">Backend Developer</span><br>
                            <span class="role">Database Administrator</span><br>

                            <span class="text-muted">
                                 Bertanggung jawab mengembangkan logika sistem serta mengelola database.
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card tim-card">
                    <img src="{{ asset('foto/imau.png') }}" class="tim-img">
                    <div class="card-body">
                        <h5>Noor Maulida</h5>
                        <p>
                            <span class="role">Tester</span><br>
                            <span class="role">Frontend Support</span><br>

                            <span class="text-muted">
                                 Melakukan pengujian sistem dan membantu pengembangan antarmuka.
                            </span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- KONTAK -->
<section class="tentang" id="kontak">
    <div class="container text-center">
        <h3>Kontak</h3>
        <p class="mt-3">
            Email: sapa@gmail.com <br>
            Telepon: 08123456789
        </p>
    </div>
</section>


<!-- FOOTER -->
<footer class="footer text-center">
    <div class="container">
        <p>© 2026 SAPA - Sistem Absensi dan Penjemputan Anak</p>
    </div>
</footer>


<script>
    const cards = document.querySelectorAll('.tim-card');

    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {

            // ✅ Efek interaktif bagus banget
            // ❗ Saran: kasih transition biar lebih smooth

            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = -(y - centerY) / 15;
            const rotateY = (x - centerX) / 15;

            card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = `rotateX(0deg) rotateY(0deg)`;
        });
    });
</script>
