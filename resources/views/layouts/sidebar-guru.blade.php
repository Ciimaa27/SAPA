<div class="sidebar-guru">

    <div class="logo-area">
        <h2>SAPA</h2>
        <p>Absensi & Penjemputan</p>
    </div>

    <ul class="menu-list">

        <!-- DASHBOARD -->
        <li class="menu-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <a href="{{ route('guru.dashboard') }}">
                <i class="fa-solid fa-th-large"></i>
                Dashboard
            </a>
        </li>

        <!-- KEHADIRAN -->
        <li class="menu-item {{ request()->routeIs('guru.kehadiran') ? 'active' : '' }}">
            <a href="{{ route('guru.kehadiran') }}">
                <i class="fa-solid fa-clipboard-check"></i>
                Input Kehadiran
            </a>
        </li>

        <!-- RIWAYAT -->
        <li class="menu-item {{ request()->routeIs('guru.riwayat') ? 'active' : '' }}">
            <a href="{{ route('guru.riwayat') }}">
                <i class="fa-solid fa-user-check"></i>
                Riwayat Penjemputan
            </a>
        </li>

        <!-- DATA -->
        <li class="menu-item {{ request()->routeIs('guru.data-penjemputan') ? 'active' : '' }}">
            <a href="{{ route('guru.data-penjemputan') }}">
                <i class="fa-solid fa-database"></i>
                Data Penjemputan
            </a>
        </li>

    </ul>

    <!-- LOGOUT -->
    <div class="logout">
        <a href="#" onclick="confirmLogout()">
            <i class="fa-solid fa-right-from-bracket"></i>
            Keluar
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>

</div>
