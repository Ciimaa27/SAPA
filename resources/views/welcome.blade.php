<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAPA | Sistem Absensi dan Penjemputan Anak</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

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

    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center hero-row">
                <div class="col-lg-6">
                    <span class="hero-badge">
                        SDIT Nurul Fikri Banjarmasin
                    </span>

                    <h1 class="hero-title">
                        Sistem Absensi dan Penjemputan Anak Berbasis RFID & Fingerprint
                    </h1>

                    <p class="hero-desc">
                        Solusi modern untuk meningkatkan keamanan, akurasi, dan transparansi proses kehadiran
                        serta penjemputan siswa secara real-time.
                    </p>

                    <div class="hero-btn-group">
                        <a class="btn btn-hero-primary" href="/login">Mulai Sekarang</a>
                        <a class="btn btn-hero-secondary" href="#tentang">Pelajari Lebih Lanjut</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-image-wrapper">
                        <img class="hero-image" src="{{ asset('foto/nurulfikri.png') }}" alt="SDIT Nurul Fikri">

                        <div class="floating-card">
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

    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <h3>500+</h3>
                        <p>Siswa Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <h3>98%</h3>
                        <p>Akurasi Data</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <h3>24/7</h3>
                        <p>Monitoring</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <h3>RFID</h3>
                        <p>Fingerprint</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fitur-section" id="fitur">
        <div class="container">
            <div class="section-title">
                <span>FITUR UNGGULAN</span>
                <h2>Mengapa Memilih SAPA?</h2>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-lg-3 col-md-6">
                    <div class="fitur-card">
                        <div class="fitur-icon">📡</div>
                        <h5>RFID</h5>
                        <p>Pencatatan kehadiran siswa otomatis dan cepat.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="fitur-card">
                        <div class="fitur-icon">👆</div>
                        <h5>Fingerprint</h5>
                        <p>Validasi identitas secara aman dan akurat.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="fitur-card">
                        <div class="fitur-icon">🔔</div>
                        <h5>Notifikasi</h5>
                        <p>Informasi langsung kepada wali siswa.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="fitur-card">
                        <div class="fitur-icon">📊</div>
                        <h5>Laporan</h5>
                        <p>Riwayat kehadiran dan penjemputan lengkap.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tentang-section" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="tentang-image">
                        <img class="img-main" src="{{ asset('foto/tk1.png') }}" alt="SAPA">
                        <img class="img-back" src="{{ asset('foto/tk2.png') }}" alt="SAPA">
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="section-badge">TENTANG SAPA</span>
                    <h2 class="section-heading">
                        Sistem Absensi dan Penjemputan Anak yang Aman dan Modern
                    </h2>
                    <p>
                        SAPA merupakan sistem informasi berbasis web yang dirancang untuk meningkatkan keamanan,
                        efisiensi, dan transparansi dalam proses kehadiran serta penjemputan siswa.
                    </p>
                    <div class="check-list">
                        <div>✓ Absensi RFID Otomatis</div>
                        <div>✓ Verifikasi Fingerprint</div>
                        <div>✓ Monitoring Real-time</div>
                        <div>✓ Riwayat Kehadiran Lengkap</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="panduan-section" id="panduan">
        <div class="container">
            <div class="section-title text-center">
                <span>CARA KERJA</span>
                <h2>Panduan Penggunaan SAPA</h2>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-lg-3">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5>Scan RFID</h5>
                        <p>Siswa melakukan scan kartu RFID saat datang.</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5>Verifikasi</h5>
                        <p>Sistem memvalidasi identitas siswa.</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5>Data Tersimpan</h5>
                        <p>Kehadiran otomatis masuk ke database.</p>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5>Notifikasi</h5>
                        <p>Orang tua menerima informasi secara langsung.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="guru-section" id="guru">
        <div class="container">
            <div class="section-title text-center">
                <span>TENAGA PENDIDIK</span>
                <h2>Guru SDIT Nurul Fikri</h2>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-lg-3 col-md-6">
                    <div class="guru-card-simple">
                        <div class="guru-icon">👩‍🏫</div>
                        <h5>Guru Kelas</h5>
                        <p>Mendampingi proses belajar mengajar siswa setiap hari.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="guru-card-simple">
                        <div class="guru-icon">📚</div>
                        <h5>Guru Mata Pelajaran</h5>
                        <p>Mengelola pembelajaran sesuai bidang keahlian masing-masing.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="guru-card-simple">
                        <div class="guru-icon">📝</div>
                        <h5>Administrasi Kehadiran</h5>
                        <p>Mengelola data kehadiran dan aktivitas siswa.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="guru-card-simple">
                        <div class="guru-icon">🤝</div>
                        <h5>Kolaborasi Wali</h5>
                        <p>Menjalin komunikasi aktif dengan orang tua siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="tim-section" id="tim">
        <div class="container">
            <div class="section-title text-center">
                <span>PENGEMBANG</span>
                <h2>Tim SAPA</h2>
            </div>

            <div class="row g-4 mt-5">
                <div class="col-lg-4">
                    <div class="tim-card">
                        <img class="tim-img" src="{{ asset('foto/bila.png') }}" alt="Radita Nabila Shofa">
                        <h5>Radita Nabila Shofa</h5>
                        <span>UI/UX Designer & Frontend Developer</span>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="tim-card">
                        <img class="tim-img" src="{{ asset('foto/isma1.png') }}" alt="Ismatul Hawa">
                        <h5>Ismatul Hawa</h5>
                        <span>Backend Developer & Database Administrator</span>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="tim-card">
                        <img class="tim-img" src="{{ asset('foto/imau1.png') }}" alt="Noor Maulida">
                        <h5>Noor Maulida</h5>
                        <span>Tester & Frontend Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="kontak-section" id="kontak">
        <div class="container text-center">
            <span class="section-badge">HUBUNGI KAMI</span>
            <h2 class="mt-3">SDIT Nurul Fikri Banjarmasin</h2>
            <p class="mt-4">
                Email : sapa@gmail.com
                <br>
                Telepon : 08123456789
            </p>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <h4>SAPA</h4>
                    <p>
                        Sistem Absensi dan Penjemputan Anak berbasis RFID dan Fingerprint.
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
</body>

</html>