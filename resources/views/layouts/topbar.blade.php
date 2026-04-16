<div class="topbar-admin">

    <!-- kiri -->
    <div class="topbar-left">
        <span id="tanggal"></span>
    </div>

    <!-- kanan -->
    <div class="topbar-right">

        <span id="jam"></span>

        <i class="fa-solid fa-bell"></i>

        <!-- USER MENU -->
        <div class="user-menu">
            <i class="fa-solid fa-user-circle" id="userIcon"></i>

            <!-- DROPDOWN -->
            <div class="user-dropdown" id="userDropdown">
                <div class="user-card">

                    <div class="user-info">

                        {{-- 🔥 DEBUG (SEMENTARA, NANTI HAPUS) --}}
                        {{-- {{ dd(auth()->user()) }} --}}

                        @php
                            $role = '-';

                            if(auth()->check()){
                                switch(auth()->user()->id_role){
                                    case 1: $role = 'Admin'; break;
                                    case 2: $role = 'Guru'; break;
                                    case 3: $role = 'Kepala Sekolah'; break;
                                    case 4: $role = 'Orang Tua/Wali'; break;
                                }
                            }
                        @endphp

                        @if(auth()->check())
                            <h5>{{ auth()->user()->nama_lengkap }}</h5>
                            <span>{{ $role }}</span>
                        @else
                            <h5>Guest</h5>
                            <span>-</span>
                        @endif

                    </div>

                    <!-- LOGOUT -->
                    <a href="#" onclick="confirmLogout()">
                        <i class="fa fa-sign-out"></i>
                    </a>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- FORM LOGOUT -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // JAM & TANGGAL
    function updateDateTime(){
        const now = new Date();

        const hari = now.toLocaleDateString('id-ID',{ weekday:'long' });
        const tanggal = now.toLocaleDateString('id-ID',{
            day:'numeric',
            month:'long',
            year:'numeric'
        });

        const jam = now.toLocaleTimeString('id-ID',{
            hour:'2-digit',
            minute:'2-digit',
        });

        document.getElementById("tanggal").innerHTML = hari + ", " + tanggal;
        document.getElementById("jam").innerHTML = jam;
    }

    updateDateTime();
    setInterval(updateDateTime,1000);

    // DROPDOWN
    const userIcon = document.getElementById("userIcon");
    const dropdown = document.getElementById("userDropdown");

    userIcon.addEventListener("click", function(e) {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function() {
        dropdown.style.display = "none";
    });

});

// LOGOUT
function confirmLogout() {
    if (confirm("Yakin ingin keluar?")) {
        document.getElementById('logout-form').submit();
    }
}
</script>
