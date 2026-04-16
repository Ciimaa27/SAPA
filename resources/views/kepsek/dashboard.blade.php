@extends('layouts.app')

@section('title', 'Dashboard Kepsek')

@section('sidebar')
    @include('layouts.sidebar-kepsek')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kepsek/dashboard.css') }}">

<style>
/* 🔥 FIX CHART SIZE */
.chart-container {
    max-width: 500px;
    height: 250px;
}
</style>
@endpush

@section('content')

<div class="main-kepsek">

    <!-- 🔥 STATISTIK -->
    <div class="section">
        <div class="section-title">Statistik ringkas</div>

        <div class="cards">
            <div class="card-dashboard">
                <p>Total siswa</p>
                <h3>450</h3>
                <i class="fa-solid fa-users icon-orange"></i>
            </div>

            <div class="card-dashboard">
                <p>Total akun wali</p>
                <h3>478</h3>
                <i class="fa-solid fa-user-group icon-orange"></i>
            </div>

            <div class="card-dashboard">
                <p>Total kehadiran siswa</p>
                <h3>443</h3>
                <i class="fa-solid fa-chart-line icon-orange"></i>
            </div>

            <div class="card-dashboard">
                <p>Siswa tidak hadir</p>
                <h3>7</h3>
                <i class="fa-solid fa-user-xmark icon-orange"></i>
            </div>
        </div>
    </div>

    <!-- 🔥 PENJEMPUTAN -->
    <div class="section">
        <div class="section-title">Data penjemputan hari ini</div>

        <div class="cards">
            <div class="card-dashboard">
                <p>Sudah dijemput</p>
                <h3>206</h3>
                <div class="badge-icon success">
                    <i class="fa fa-check"></i>
                </div>
            </div>

            <div class="card-dashboard">
                <p>Belum dijemput</p>
                <h3>244</h3>
                <div class="badge-icon danger">
                    <i class="fa fa-times"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 GRAFIK -->
    <div class="section">
        <div class="section-title">Grafik Kehadiran mingguan</div>

        <div class="card-dashboard">
            <div class="chart-container">
                <canvas id="chartKehadiran"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('chartKehadiran');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
        datasets: [
            {
                label: 'Hadir',
                data: [120, 150, 200, 180, 170],
                backgroundColor: '#6a4bc4'
            },
            {
                label: 'Tidak hadir',
                data: [20, 30, 40, 35, 25],
                backgroundColor: '#2ec4b6'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // 🔥 penting biar ikut height CSS
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>
@endpush
