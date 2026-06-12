<div class="sidebar">

    <div>
        <div class="logo">SAPA</div>
        <div class="sub-logo">Absensi & Penjemputan</div>

        <div class="menu">

            <a href="{{ route('wali.dashboard') }}" class="{{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-th-large"></i>
                Dashboard
            </a>

            <a href="{{ route('wali.kehadiran') }}" class="{{ request()->routeIs('wali.kehadiran') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i>
                Kehadiran anak
            </a>

            <a href="{{ route('wali.status-penjemputan') }}"
                class="{{ request()->routeIs('wali.status-penjemputan') ? 'active' : '' }}">
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

   <!-- 🔥 LOGOUT -->
    <div class="logout-btn">
        <a href="#" onclick="confirmLogout()" style="text-decoration:none; color:inherit;">
            <i class="fa-solid fa-right-from-bracket"></i>
            Keluar
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

</div>
