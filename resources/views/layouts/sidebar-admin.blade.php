<div class="sidebar-admin">

    <div class="logo-area">
        <h2>SAPA</h2>
        <p>Absensi & Penjemputan</p>
    </div>

    <ul class="menu-list">

        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-th-large"></i>
                Dashboard
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('kelola-akun.*') ? 'active' : '' }}">
            <a href="{{ route('kelola-akun.index') }}">
                <i class="fa-solid fa-user"></i>
                Kelola akun
            </a>
        </li>

        <p class="menu-title">Data Master</p>

        <li class="menu-item {{ request()->routeIs('data-siswa') ? 'active' : '' }}">
            <a href="{{ route('data-siswa') }}">
                <i class="fa-solid fa-users"></i>
                Data siswa
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('data-wali') ? 'active' : '' }}">
            <a href="{{ route('data-wali') }}">
                <i class="fa-solid fa-user-group"></i>
                Data wali
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('guru') ? 'active' : '' }}">
            <a href="{{ route('guru') }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                Guru & kelas
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('relasi.*') ? 'active' : '' }}">
            <a href="{{ route('relasi.index') }}">
                <i class="fa-solid fa-link"></i>
                Relasi siswa dan wali
            </a>
        </li>

        <p class="menu-title">Pendaftaran IoT</p>

        <li class="menu-item {{ request()->routeIs('iot.*') ? 'active' : '' }}">
            <a href="{{ route('iot.index', ['tab'=>'rfid']) }}">
                <i class="fa-solid fa-wifi"></i>
                RFID dan Sidik Jari
            </a>
        </li>

        <p class="menu-title">Operasional</p>

        <li class="menu-item {{ request()->routeIs('data-penjemputan') ? 'active' : '' }}">
            <a href="{{ route('data-penjemputan') }}">
                <i class="fa-solid fa-download"></i>
                Data penjemputan
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('jadwal-pulang') ? 'active' : '' }}">
            <a href="{{ route('jadwal-pulang') }}">
                <i class="fa-solid fa-clock"></i>
                Jadwal pulang
            </a>
        </li>

        <li class="menu-item">
            <a href="#">
                <i class="fa-solid fa-graduation-cap"></i>
                Kenaikan kelas
            </a>
        </li>

        <p class="menu-title">Monitoring IoT</p>

        <li class="menu-item {{ request()->routeIs('status-perangkat') ? 'active' : '' }}">
            <a href="{{ route('status-perangkat') }}">
                <i class="fa-solid fa-circle-exclamation"></i>
                Status perangkat
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('laporan') ? 'active' : '' }}">
            <a href="{{ route('laporan') }}">
                <i class="fa-solid fa-box-archive"></i>
                Laporan
            </a>
        </li>

    </ul>

    <div class="logout">
        <a href="#">
            <i class="fa-solid fa-right-from-bracket"></i>
            Keluar
        </a>
    </div>

</div>
