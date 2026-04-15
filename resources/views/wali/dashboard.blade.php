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
            <strong>Arif Nasution</strong>
        </div>
        <div class="row-item">
            <span>Kelas</span>
            <strong>4-B</strong>
        </div>
    </div>

    <!-- STATUS -->
    <div class="card-box title">Status hari ini</div>

    <div class="card-box table-box">
        <div class="row-item">
            <span>Kehadiran</span>
            <strong>07:12</strong>
        </div>
        <div class="row-item">
            <span>Penjemputan</span>
            <strong>11.23</strong>
        </div>
        <div class="row-item">
            <span>Jadwal pulang</span>
            <strong>13.00</strong>
        </div>
    </div>

</div>

@endsection
