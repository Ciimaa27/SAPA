<div class="sidebar">

    <div>
        <div class="logo">SAPA</div>
        <div class="sub-logo">Absensi & Penjemputan</div>

        <div class="menu">

            <a href="{{ route('wali.dashboard') }}" class="{{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-th-large"></i>
                Dashboard
            </a>

            <a href="#">
                <i class="fa-solid fa-calendar-check"></i>
                Kehadiran anak
            </a>

            <a href="#">
                <i class="fa-solid fa-truck"></i>
                Status penjemputan
            </a>

            <a href="#">
                <i class="fa-solid fa-clock"></i>
                Jadwal pulang
            </a>

            <a href="#">
                <i class="fa-solid fa-bell"></i>
                Notifikasi
            </a>

            <a href="#">
                <i class="fa-solid fa-box-archive"></i>
                Laporan
            </a>

        </div>
    </div>

    <div class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i>
        Keluar
    </div>

</div>
