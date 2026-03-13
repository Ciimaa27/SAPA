@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">

    <!-- DATA AKUN -->
    <div class="section">
        <h6 class="section-title">Data akun</h6>

        <div class="row g-3">

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Total siswa</p>
                    <h3>450</h3>
                    <i class="fa fa-users icon-orange"></i>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Total akun wali</p>
                    <h3>478</h3>
                    <i class="fa fa-user-friends icon-orange"></i>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Total akun guru</p>
                    <h3>21</h3>
                    <i class="fa fa-chalkboard-teacher icon-orange"></i>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Total akun kepsek</p>
                    <h3>2</h3>
                    <i class="fa fa-user icon-orange"></i>
                </div>
            </div>

        </div>
    </div>


<!-- DATA HARI INI -->
<div class="section">
    <h6 class="section-title">Data hari ini</h6>

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card-dashboard">
                <p>Kehadiran hari ini</p>
                <h3>439</h3>

                <div class="badge-icon up">
                    <i class="fa fa-arrow-up"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-dashboard">
                <p>Tidak hadir</p>
                <h3>11</h3>

                <div class="badge-icon down">
                    <i class="fa fa-arrow-down"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-dashboard">
                <p>Sudah dijemput</p>
                <h3>206</h3>

                <div class="badge-icon success">
                    <i class="fa fa-check"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-dashboard">
                <p>Belum dijemput</p>
                <h3>244</h3>

                <div class="badge-icon danger">
                    <i class="fa fa-times"></i>
                </div>
            </div>
        </div>

    </div>
</div>


    <!-- GRAFIK -->
    <div class="row g-3 mt-2">

        <div class="col-md-8">
            <div class="card-dashboard">
                <h6>Grafik Kehadiran</h6>
                <canvas id="chartBar"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-dashboard">
                <h6>Grafik Penjemputan</h6>
                <canvas id="chartDonut"></canvas>
            </div>
        </div>

    </div>

    <!-- LOG AKTIVITAS -->
<div class="row mt-3">

    <div class="col-md-12">
        <div class="card-dashboard">

            <h6 class="mb-3">Log aktivitas perangkat</h6>

            <table class="table">

                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>ID Scan</th>
                        <th>Nama</th>
                        <th>Jenis perangkat</th>
                        <th>Peran</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>07:01:22</td>
                        <td>03HUS98</td>
                        <td>Arif Nasution</td>
                        <td>RFID</td>
                        <td>Siswa</td>
                        <td><span class="badge bg-success">Berhasil</span></td>
                    </tr>

                    <tr>
                        <td>07:15:08</td>
                        <td>09HH78E</td>
                        <td>Radita Nabila</td>
                        <td>RFID</td>
                        <td>Siswa</td>
                        <td><span class="badge bg-success">Berhasil</span></td>
                    </tr>

                    <tr>
                        <td>10:35:44</td>
                        <td>04KMNL</td>
                        <td>Indah Permatasari</td>
                        <td>Fingerprint</td>
                        <td>Orangtua/Wali</td>
                        <td><span class="badge bg-danger">Gagal</span></td>
                    </tr>

                </tbody>

            </table>

        </div>
    </div>

</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('chartBar');

new Chart(ctx,{
type:'bar',
data:{
labels:['Sen','Sel','Rab','Kam','Jum','Sab'],
datasets:[
{
label:'Hadir',
data:[120,130,200,150,170,180],
backgroundColor:'#8e6cc9'
},
{
label:'Tidak hadir',
data:[20,25,40,30,20,25],
backgroundColor:'#3ec7b7'
}
]
}
});

const donut = document.getElementById('chartDonut');

new Chart(donut,{
type:'doughnut',
data:{
labels:['Sudah dijemput','Belum dijemput'],
datasets:[{
data:[300,200],
backgroundColor:['#34d1bf','#ff7b7b']
}]
}
});

</script>

@endsection
