@extends('layouts.app')

@section('title', 'Dashboard Wali')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
@endpush

@section('content')
    <div class="main">

    <div class="card-box welcome">
        Selamat Datang, Orangtua Siswa ✦
    </div>

    <!-- DATA ANAK -->
    <div class="card-box title">Data anak</div>

    <div class="card-box table-box">
        <div class="row-item">
            <span>Anak</span>
            <strong>{{ $siswa ? $siswa->nama_siswa : 'Tidak ada data anak' }}</strong>
        </div>
        <div class="row-item">
            <span>Kelas</span>
            <strong>{{ $siswa && $siswa->kelas ? $siswa->kelas->nama_kelas : 'Tidak ada data kelas' }}</strong>
        </div>
    </div>

    <!-- STATUS -->
    <div class="card-box title">Status hari ini</div>

    <div class="card-box table-box">
        <div class="row-item">
            <span>Kehadiran</span>
            <strong>{{ $kehadiran ? \Carbon\Carbon::parse($kehadiran->jam_masuk)->format('H:i') : 'Belum hadir' }}</strong>
        </div>
        <div class="row-item">
            <span>Penjemputan</span>
            <strong>{{ $penjemputan ? \Carbon\Carbon::parse($penjemputan->jam_jemput)->format('H.i') : 'Belum dijemput' }}</strong>
        </div>
        <div class="row-item">
            <span>Jadwal pulang</span>
            <strong>{{ $jadwal_pulang ? $jadwal_pulang->jam : 'Tidak ada jadwal' }}</strong>
        </div>
    </div>

</div>

@endsection
