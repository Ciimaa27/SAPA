<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">

<div class="sidebar-admin">

    <!-- Logo -->
    <div class="logo-area">
        <h2>SAPA</h2>
        <p>Absensi & Penjemputan</p>
    </div>

    <!-- Menu -->
    <ul class="menu-list">

        <li class="menu-item active">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-th-large"></i>
                Dashboard
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-user"></i>
                Kelola akun
            </a>
        </li>

        <p class="menu-title">Data Master</p>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-users"></i>
                Data siswa
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-user-friends"></i>
                Data wali
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-chalkboard-teacher"></i>
                Guru & kelas
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-link"></i>
                Relasi siswa dan wali
            </a>
        </li>

        <p class="menu-title">Pendaftaran IoT</p>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-wifi"></i>
                RFID dan sidik jari
            </a>
        </li>

        <p class="menu-title">Operasional</p>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-download"></i>
                Data penjemputan
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-clock"></i>
                Jadwal pulang
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-graduation-cap"></i>
                Kenaikan kelas
            </a>
        </li>

        <p class="menu-title">Monitoring IoT</p>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-exclamation-circle"></i>
                Status perangkat dan log aktivitas
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fas fa-archive"></i>
                Arsip
            </a>
        </li>

    </ul>

    <!-- Logout -->
    <div class="logout">
        <a href="#">
            <i class="fas fa-sign-out-alt"></i>
            Keluar
        </a>
    </div>

</div>
