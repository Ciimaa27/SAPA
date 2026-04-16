<div class="sidebar-guru">

    <div>
        <div class="logo">SAPA</div>
        <div class="sub-logo">Absensi & Penjemputan</div>

        <div class="menu">

            <a href="#" class="active">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="#">
                <i class="fa-solid fa-clipboard-check"></i>
                Input Kehadiran
            </a>

            <a href="#">
                <i class="fa-solid fa-user-check"></i>
                Riwayat Penjemputan
            </a>

            <a href="#">
                <i class="fa-solid fa-database"></i>
                Data Penjemputan
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
