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

    <!-- RINGKASAN -->
    <div class="title-box">Ringkasan Hari Ini</div>

    <div class="cards">
        <div class="card">
            <div>
                <h4>Total Siswa</h4>
                <h2>30</h2>
            </div>
            <i class="fa-solid fa-users"></i>
        </div>

        <div class="card">
            <div>
                <h4>Hadir</h4>
                <h2>25</h2>
            </div>
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="card">
            <div>
                <h4>Tidak Hadir</h4>
                <h2>5</h2>
            </div>
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
    </div>

    <!-- PENJEMPUTAN -->
    <div class="title-box">Data Penjemputan Hari Ini</div>

    <div class="pickup">
        <div class="pickup-card">
            <div>
                <h4>Sudah dijemput</h4>
                <h2>20</h2>
            </div>
            <i class="fa-solid fa-circle-check check"></i>
        </div>

        <div class="pickup-card">
            <div>
                <h4>Belum dijemput</h4>
                <h2>10</h2>
            </div>
            <i class="fa-solid fa-circle-xmark cross"></i>
        </div>
    </div>

    <!-- TABEL -->
    <div class="table-box">
        <h4>Data Kehadiran Siswa</h4>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ahmad</td>
                    <td>1A</td>
                    <td>Hadir</td>
                    <td>07:30</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection
