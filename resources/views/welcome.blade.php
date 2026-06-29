<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPA | Sistem Absensi dan Penjemputan Anak</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    <!-- ===========================
            NAVBAR
    ============================ -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <div>
                    <h5 class="m-0 fw-bold">SAPA</h5>
                    <small>Sistem Absensi & Penjemputan Anak</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#hero">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#panduan">Panduan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#guru">Guru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tim">Pengembang</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-login" href="/login">Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ===========================
            HERO
    ============================ -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center hero-row">

                <!-- TEXT -->
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1200">
                    <span class="hero-badge" data-aos="zoom-in" data-aos-delay="150">
                        SDIT Nurul Fikri Banjarmasin
                    </span>

                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="350">
                        Sistem Absensi dan Penjemputan Anak Berbasis RFID & Fingerprint
                    </h1>

                    <p class="hero-desc" data-aos="fade-up" data-aos-delay="550">
                        Solusi modern untuk meningkatkan keamanan, efisiensi, transparansi
                        dan monitoring kehadiran siswa secara real-time.
                    </p>

                    <div class="hero-btn-group" data-aos="fade-up" data-aos-delay="750">
                        <a class="btn btn-hero-primary" href="/login">Mulai Sekarang</a>
                        <a class="btn btn-hero-secondary" href="#tentang">Pelajari Lebih Lanjut</a>
                    </div>
                </div>

                <!-- IMAGE -->
                <div class="col-lg-6" data-aos="zoom-in-left" data-aos-duration="1500">
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('foto/nurulfikri.png') }}" class="hero-image floating-image" alt="SDIT Nurul Fikri">

                        <div class="floating-card" data-aos="fade-up" data-aos-delay="1000">
                            <div class="icon-box">✓</div>
                            <div>
                                <h6>Aman • Cepat • Akurat</h6>
                                <p>Monitoring siswa secara real-time.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="hero-dots"></div>
    </section>

    <!-- ===========================
            STATS
    ============================ -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-3 col-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="100">
                    <h3 class="counter" data-target="500">0</h3>
                    <p>Siswa Aktif</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="250">
                    <h3 class="counter" data-target="98">0</h3>
                    <p>Akurasi Data (%)</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="400">
                    <h3>24/7</h3>
                    <p>Monitoring</p>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="stats-card" data-aos="zoom-in" data-aos-delay="550">
                    <h3>RFID</h3>
                    <p>Fingerprint</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===========================
        FITUR
============================ -->
<section class="fitur-section" id="fitur">
    <div class="container">

        <div class="section-title" data-aos="fade-up">
            <span>FITUR UNGGULAN</span>
            <h2>Mengapa Memilih SAPA?</h2>
        </div>

        <div class="row g-4 mt-4">

            <div class="col-lg-3 col-md-6">
                <div class="fitur-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="fitur-icon">📡</div>
                    <h5>RFID</h5>
                    <p>
                        Pencatatan kehadiran siswa dilakukan secara otomatis
                        hanya dalam hitungan detik.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="fitur-card" data-aos="fade-up" data-aos-delay="250">
                    <div class="fitur-icon">👆</div>
                    <h5>Fingerprint</h5>
                    <p>
                        Verifikasi identitas dilakukan lebih aman
                        menggunakan sidik jari.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="fitur-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="fitur-icon">🔔</div>
                    <h5>Notifikasi</h5>
                    <p>
                        Orang tua memperoleh informasi kehadiran
                        secara real-time.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="fitur-card" data-aos="fade-up" data-aos-delay="550">
                    <div class="fitur-icon">📊</div>
                    <h5>Laporan</h5>
                    <p>
                        Riwayat absensi dan penjemputan dapat
                        diakses kapan saja.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===========================
        TENTANG
============================ -->
<section class="tentang-section" id="tentang">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- GAMBAR -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1200">
                <div class="tentang-image">
                    <img class="img-main" src="{{ asset('foto/tk1.png') }}" alt="SAPA">
                    <img class="img-back" src="{{ asset('foto/tk2.png') }}" alt="SAPA">
                </div>
            </div>

            <!-- TEKS -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1200">
                <span class="section-badge">TENTANG SAPA</span>

                <h2 class="section-heading">
                    Sistem Absensi dan Penjemputan Anak yang Aman dan Modern
                </h2>

                <p>
                    SAPA merupakan sistem informasi berbasis web yang dirancang untuk membantu sekolah
                    dalam mengelola kehadiran serta penjemputan siswa secara lebih aman, cepat,
                    transparan, dan real-time menggunakan teknologi RFID serta Fingerprint.
                </p>

                <div class="check-list">
                    <div data-aos="fade-left" data-aos-delay="200">✓ Absensi RFID Otomatis</div>
                    <div data-aos="fade-left" data-aos-delay="350">✓ Verifikasi Fingerprint</div>
                    <div data-aos="fade-left" data-aos-delay="500">✓ Monitoring Real-time</div>
                    <div data-aos="fade-left" data-aos-delay="650">✓ Riwayat Kehadiran Lengkap</div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===========================
        PANDUAN
============================ -->
<section class="panduan-section" id="panduan">
    <div class="container">

        <div class="section-title text-center" data-aos="fade-up">
            <span>CARA KERJA</span>
            <h2>Panduan Penggunaan SAPA</h2>
        </div>

        <div class="row g-4 mt-5">

            <div class="col-lg-3 col-md-6">
                <div class="step-card" data-aos="flip-left" data-aos-delay="100">
                    <div class="step-number">1</div>
                    <h5>Scan RFID</h5>
                    <p>Siswa melakukan scan kartu RFID saat tiba di sekolah.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card" data-aos="flip-left" data-aos-delay="250">
                    <div class="step-number">2</div>
                    <h5>Verifikasi</h5>
                    <p>Sistem melakukan verifikasi identitas menggunakan fingerprint.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card" data-aos="flip-left" data-aos-delay="400">
                    <div class="step-number">3</div>
                    <h5>Data Tersimpan</h5>
                    <p>Data kehadiran langsung tersimpan secara otomatis pada database.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="step-card" data-aos="flip-left" data-aos-delay="550">
                    <div class="step-number">4</div>
                    <h5>Notifikasi</h5>
                    <p>Orang tua menerima informasi kehadiran secara real-time.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===========================
        GURU
============================ -->
<section class="guru-section" id="guru">
    <div class="container">

        <div class="section-title text-center" data-aos="fade-up">
            <span>TENAGA PENDIDIK</span>
            <h2>Guru SDIT Nurul Fikri</h2>
        </div>

        <div class="row g-4 mt-4">

            <div class="col-lg-3 col-md-6">
                <div class="guru-card-simple" data-aos="zoom-in" data-aos-delay="100">
                    <div class="guru-icon">👩‍🏫</div>
                    <h5>Guru Kelas</h5>
                    <p>Mendampingi siswa selama proses pembelajaran setiap hari.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="guru-card-simple" data-aos="zoom-in" data-aos-delay="250">
                    <div class="guru-icon">📚</div>
                    <h5>Guru Mata Pelajaran</h5>
                    <p>Mengajar sesuai bidang keahlian masing-masing.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="guru-card-simple" data-aos="zoom-in" data-aos-delay="400">
                    <div class="guru-icon">📝</div>
                    <h5>Administrasi Kehadiran</h5>
                    <p>Mengelola seluruh data absensi siswa.</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="guru-card-simple" data-aos="zoom-in" data-aos-delay="550">
                    <div class="guru-icon">🤝</div>
                    <h5>Kolaborasi Wali</h5>
                    <p>Menjalin komunikasi aktif dengan orang tua.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- =======================
        TIM
======================= -->
<section class="tim-section" id="tim">
    <div class="container">

        <div class="section-title text-center" data-aos="fade-up">
            <span>PENGEMBANG</span>
            <h2>Tim SAPA</h2>
        </div>

        <div class="row g-4 mt-5">

            <div class="col-lg-4">
                <div class="tim-card" data-aos="fade-up" data-aos-delay="100">
                    <img class="tim-img" src="{{ asset('foto/bila.png') }}">
                    <h5>Radita Nabila Shofa</h5>
                    <span>UI/UX Designer & Frontend Developer</span>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="tim-card" data-aos="fade-up" data-aos-delay="300">
                    <img class="tim-img" src="{{ asset('foto/isma1.png') }}">
                    <h5>Ismatul Hawa</h5>
                    <span>Backend Developer & Database Administrator</span>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="tim-card" data-aos="fade-up" data-aos-delay="500">
                    <img class="tim-img" src="{{ asset('foto/imau1.png') }}">
                    <h5>Noor Maulida</h5>
                    <span>Tester & Frontend Support</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- =======================
        KONTAK
======================= -->
<section class="kontak-section" id="kontak">
    <div class="container text-center" data-aos="fade-up">
        <span class="section-badge">HUBUNGI KAMI</span>
        <h2 class="mt-3">SDIT Nurul Fikri Banjarmasin</h2>
        <p class="mt-4">
            Email : sapa@gmail.com <br>
            Telepon : 08123456789
        </p>
    </div>
</section>

<!-- =======================
        FOOTER
======================= -->
<footer class="footer">
    <div class="container">

        <div class="row">

            <div class="col-lg-4">
                <h4>SAPA</h4>
                <p>
                    Sistem Absensi dan Penjemputan Anak berbasis RFID & Fingerprint.
                </p>
            </div>

            <div class="col-lg-4">
                <h5>Menu</h5>
                <ul class="footer-links">
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h5>Kontak</h5>
                <p>Banjarmasin, Kalimantan Selatan</p>
                <a class="instagram-link" href="https://instagram.com/sdit_nurul_fikri_banjarmasin" target="_blank">
                    <i class="bi bi-instagram"></i> Instagram Sekolah
                </a>
            </div>

        </div>

        <hr>

        <div class="text-center">
            © 2026 SAPA - Sistem Absensi dan Penjemputan Anak
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
    AOS.init({

        duration:500,
        once:true,
        offset:80,
        easing:"ease-out-cubic"

    });

    /* Counter */
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const update = () => {
            const target = +counter.dataset.target;
            const count = +counter.innerText;
            const speed = 20;
            const inc = Math.ceil(target / speed);

            if (count < target) {
                counter.innerText = count + inc;
                setTimeout(update, 40);
            } else {
                counter.innerText = target;
                if (target == 500) counter.innerText = "500+";
                if (target == 98) counter.innerText = "98%";
            }
        }
        update();
    });

    /* Navbar */
    window.addEventListener("scroll", () => {
        const navbar = document.querySelector(".navbar-custom");
        if (window.scrollY > 80) {
            navbar.classList.add("navbar-scroll");
        } else {
            navbar.classList.remove("navbar-scroll");
        }
    });
</script>
</body>
</html>

