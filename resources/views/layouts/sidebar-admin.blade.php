<div class="sidebar-admin">

    <div class="logo-area">
        <h2>SAPA</h2>
        <p>Absensi & Penjemputan</p>
    </div>

    <ul class="menu-list">
        {{-- DASHBOARD --}}
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-th-large"></i> Dashboard
            </a>
        </li>

        {{-- KELOLA AKUN --}}
        <li class="menu-item {{ request()->routeIs('kelola-akun.*') ? 'active' : '' }}">
            <a href="{{ route('kelola-akun.index') }}">
                <i class="fa-solid fa-user"></i> Kelola akun
            </a>
        </li>

        <p class="menu-title">Data Master</p>

       {{-- DATA SISWA --}}
        <li class="menu-item {{
            request()->routeIs(
                'data-siswa',
                'data-siswa.show',
                'tambah-siswa',
                'edit-siswa',
                'detail-siswa'
            ) ? 'active' : ''
        }}">
            <a href="{{ route('data-siswa') }}">
                <i class="fa-solid fa-users"></i> Data siswa
            </a>
        </li>

        {{-- DATA WALI --}}
        <li class="menu-item {{ request()->routeIs('data-wali', 'wali.create', 'edit-data-wali') ? 'active' : '' }}">
            <a href="{{ route('data-wali') }}">
                <i class="fa-solid fa-user-group"></i> Data wali
            </a>
        </li>

        {{-- GURU DAN KELAS --}}
        <li class="menu-item {{ request()->routeIs('guru', 'kelas', 'detail-guru', 'edit-data-guru', 'tambah-data-guru', 'tambah-data-kelas', 'siswa-kelas') ? 'active' : '' }}">
            <a href="{{ route('guru') }}">
                <i class="fa-solid fa-chalkboard-user"></i> Guru & kelas
            </a>
        </li>

        {{-- RELASI SISWA DAN WALI --}}
        <li class="menu-item {{ request()->routeIs('relasi.*') ? 'active' : '' }}">
            <a href="{{ route('relasi.index') }}">
                <i class="fa-solid fa-link"></i> Relasi siswa dan wali
            </a>
        </li>

        <p class="menu-title">Pendaftaran IoT</p>

        {{-- RFID DAN SIDIK JARI --}}
        <li class="menu-item {{ request()->routeIs('iot.*') ? 'active' : '' }}">
            <a href="{{ route('iot.index', ['tab' => 'rfid']) }}">
                <i class="fa-solid fa-wifi"></i> RFID dan Sidik Jari
            </a>
        </li>

        <p class="menu-title">Operasional</p>

        {{-- DATA PENJEMPUTAN --}}
        <li class="menu-item {{ request()->routeIs('data-penjemputan', 'data-penjemputan.*', 'status-penjemputan') ? 'active' : '' }}">
            <a href="{{ route('data-penjemputan') }}">
                <i class="fa-solid fa-download"></i> Data penjemputan
            </a>
        </li>

       {{-- JADWAL PULANG --}}
        <li class="menu-item {{ request()->routeIs('jadwal-pulang*') ? 'active' : '' }}">
            <a href="{{ route('jadwal-pulang') }}">
                <i class="fa-solid fa-clock"></i> Jadwal pulang
            </a>
        </li>
        <p class="menu-title">Monitoring IoT</p>

        {{-- STATUS PERANGKAT --}}
        <li class="menu-item {{ request()->routeIs('status-perangkat') ? 'active' : '' }}">
            <a href="{{ route('status-perangkat') }}">
                <i class="fa-solid fa-circle-exclamation"></i> Status perangkat
            </a>
        </li>

        {{-- LAPORAN --}}
        <li class="menu-item {{ request()->routeIs('laporan') ? 'active' : '' }}">
            <a href="{{ route('laporan') }}">
                <i class="fa-solid fa-file-lines"></i> Laporan
            </a>
        </li>

        {{-- ARSIP SISWA --}}
        <li class="menu-item {{ request()->routeIs('arsip-siswa*') ? 'active' : '' }}">
            <a href="{{ route('arsip-siswa') }}">
                <i class="fa-solid fa-box-archive"></i>
                Arsip siswa
            </a>
        </li>
    </ul>

    {{-- LOGOUT --}}
    <div class="logout">
        <a href="#" onclick="confirmLogout()" style="text-decoration: none; color: inherit;">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</div>
