<div class="topbar-admin">

    <!-- kiri : tanggal -->
    <div class="topbar-left">
        <span id="tanggal"></span>
    </div>

    <!-- kanan : jam + icon -->
    <div class="topbar-right">

        <span id="jam"></span>

        <i class="fa-solid fa-bell"></i>
        <i class="fa-solid fa-user-circle"></i>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    function updateDateTime(){

        const now = new Date();

        const hari = now.toLocaleDateString('id-ID',{
            weekday:'long'
        });

        const tanggal = now.toLocaleDateString('id-ID',{
            day:'numeric',
            month:'long',
            year:'numeric'
        });

        const jam = now.toLocaleTimeString('id-ID',{
            hour:'2-digit',
            minute:'2-digit',
        });

        if(document.getElementById("tanggal") && document.getElementById("jam")){
            document.getElementById("tanggal").innerHTML = hari + ", " + tanggal;
            document.getElementById("jam").innerHTML = jam;
        }
    }

    updateDateTime();
    setInterval(updateDateTime,1000);

});
</script>
@endpush
