<div class="sidebar-kepsek">

    <div>
        <div class="logo">SAPA</div>
        <div class="sub-logo">Absensi & Penjemputan</div>

        <div class="menu">

            <a href="{{ route('kepsek.dashboard') }}"
               class="{{ request()->routeIs('kepsek.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-th-large"></i>
                Dashboard
            </a>

            <a href="{{ route('kepsek.statistik') }}"
               class="{{ request()->routeIs('kepsek.statistik') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                Statistik Kehadiran
            </a>

            <a href="{{ route('kepsek.laporan') }}"
               class="{{ request()->routeIs('kepsek.laporan') ? 'active' : '' }}">
                <i class="fa-solid fa-box-archive"></i>
                Laporan
            </a>

        </div>
    </div>

    <!-- 🔥 LOGOUT -->
    <div class="logout">
        <a href="#" onclick="confirmLogout()" style="text-decoration:none; color:inherit;">
            <i class="fa-solid fa-right-from-bracket"></i>
            Keluar
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

</div>
