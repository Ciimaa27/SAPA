@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
@endpush

@section('content')

<div class="main-guru">

    <!-- 🔥 WELCOME -->
    <div class="welcome-box">
        Selamat Datang, Guru Andini
    </div>

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
                <h3>21</h3>
                <i class="fa-solid fa-chart-line icon-orange"></i>
            </div>

            <div class="card-dashboard">
                <p>Siswa tidak hadir</p>
                <h3>2</h3>
                <i class="fa-solid fa-user icon-orange"></i>
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

</div>

@endsection
