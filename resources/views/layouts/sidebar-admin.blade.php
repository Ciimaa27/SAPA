<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">

<div class="sidebar-admin">

    <!-- Logo -->
    <div class="logo-area">
        <h2>SAPA</h2>
        <p>Absensi & Penjemputan</p>
    </div>

    <!-- Menu -->
    <ul class="menu-list">

        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-th-large"></i>
                Dashboard
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('kelola-akun.index') ? 'active' : '' }}">
            <a href="{{ route('kelola-akun.index') }}">
                <i class="fas fa-user"></i>
                Kelola akun
            </a>
        </li>

        <p class="menu-title">Data Master</p>

        <li class="menu-item {{ request()->routeIs('data-siswa') ? 'active' : '' }}">
            <a href="{{ route('data-siswa') }}">
                <i class="fas fa-users"></i>
                Data siswa
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('data-wali') ? 'active' : '' }}">
            <a href="{{ route('data-wali') }}">
                <i class="fas fa-user-friends"></i>
                Data wali
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('guru') ? 'active' : '' }}">
            <a href="{{ route('guru') }}">
                <i class="fas fa-chalkboard-teacher"></i>
                Guru & kelas
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('relasi.*') ? 'active' : '' }}">
            <a href="{{ route('relasi.index') }}">
                <i class="fas fa-link"></i>
                Relasi siswa dan wali
            </a>
        </li>

        <p class="menu-title">Pendaftaran IoT</p>

         <li class="menu-item {{ request()->routeIs('iot.index') ? 'active' : '' }}">
            <a href="{{ route('iot.index', ['tab'=>'rfid']) }}">
                <i class="fas fa-wifi"></i>
                RFID dan Sidik Jari
            </a>
        </li>

        <p class="menu-title">Operasional</p>

        <li class="menu-item {{ request()->routeIs('data-penjemputan') ? 'active' : '' }}">
            <a href="{{ route('data-penjemputan') }}">
                <i class="fas fa-download"></i>
                Data penjemputan
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.jadwal-pulang') ? 'active' : '' }}">
        <a href="{{ route('admin.jadwal-pulang') }}">
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

         <li class="menu-item {{ request()->routeIs('status-perangkat') ? 'active' : '' }}">
            <a href="{{ route('status-perangkat') }}">
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
