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
            <form method="GET" action="{{ route('kepsek.statistik') }}" class="d-flex gap-3">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}">

                <select name="kelas" class="form-select form-select-sm">
                    <option value="">Semua kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ $selectedKelas == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="form-select form-select-sm">
                    <option value="semua" {{ $selectedStatus == 'semua' ? 'selected' : '' }}>Semua status</option>
                    <option value="hadir" {{ $selectedStatus == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ $selectedStatus == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ $selectedStatus == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ $selectedStatus == 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </form>
        </div>
    </div>

    <!-- CARD -->
    <div class="cards mb-3">
        <div class="card-dashboard">
            <p>Total siswa</p>
            <h3>{{ $totalSiswa }}</h3>
        </div>

        <div class="card-dashboard">
            <p>Hadir</p>
            <h3>{{ $hadir }}</h3>
        </div>

        <div class="card-dashboard">
            <p>Izin</p>
            <h3>{{ $izin }}</h3>
        </div>

        <div class="card-dashboard">
            <p>Sakit</p>
            <h3>{{ $sakit }}</h3>
        </div>

        <div class="card-dashboard">
            <p>Alpha</p>
            <h3>{{ $alpa }}</h3>
        </div>
    </div>

    <!-- PROGRESS -->
    <div class="card-dashboard">
        <p class="chart-title">Grafik Kehadiran Per-kelas</p>

        @foreach($kelasProgress as $progress)
        <div class="progress-item">
            <span>{{ $progress['nama_kelas'] }} ({{ $progress['persentase'] }}%)</span>
            <div class="progress-bar">
                <div style="width:{{ $progress['persentase'] }}%"></div>
            </div>
        </div>
        @endforeach

    </div>

</div>

@endsection
