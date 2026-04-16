@extends('layouts.app')

@section('title','Statistik Kehadiran')

@section('sidebar')
    @include('layouts.sidebar-kepsek')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kepsek/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/kepsek/statistik.css') }}">
@endpush

@section('content')

<div class="main-kepsek">

    <!-- TITLE -->
    <div class="section-title">Statistik Kehadiran</div>

    <!-- FILTER -->
    <div class="info-box">
    <i class="fa-solid fa-circle-info"></i>
        <span>Gunakan filter untuk melihat data berdasarkan tanggal atau kelas tertentu!</span>
    </div>

        <div class="filter-box">
            <input type="date" class="form-control form-control-sm">

            <select class="form-select form-select-sm">
                <option>Semua kelas</option>
            </select>

            <select class="form-select form-select-sm">
                <option>Semua status</option>
            </select>
        </div>
    </div>

    <!-- CARD -->
    <div class="cards mb-3">
        <div class="card-dashboard">
            <p>Total siswa</p>
            <h3>450</h3>
        </div>

        <div class="card-dashboard">
            <p>Hadir</p>
            <h3>443</h3>
        </div>

        <div class="card-dashboard">
            <p>Izin</p>
            <h3>1</h3>
        </div>

        <div class="card-dashboard">
            <p>Sakit</p>
            <h3>5</h3>
        </div>

        <div class="card-dashboard">
            <p>Alpha</p>
            <h3>1</h3>
        </div>
    </div>

    <!-- PROGRESS -->
    <div class="card-dashboard">
        <p class="chart-title">Grafik Kehadiran Per-kelas</p>

        <div class="progress-item">
            <span>Kelas 1-A (90%)</span>
            <div class="progress-bar">
                <div style="width:90%"></div>
            </div>
        </div>

        <div class="progress-item">
            <span>Kelas 1-B (80%)</span>
            <div class="progress-bar">
                <div style="width:80%"></div>
            </div>
        </div>

        <div class="progress-item">
            <span>Kelas 2-A (85%)</span>
            <div class="progress-bar">
                <div style="width:85%"></div>
            </div>
        </div>

    </div>

</div>

@endsection
