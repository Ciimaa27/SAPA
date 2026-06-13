@extends('layouts.app')

@section('title','Notifikasi')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/notifikasi.css') }}">
@endpush

@section('content')

<div class="main-dashboard">

    <div class="container-dashboard">

        <div class="page-title-card">
            <h5>Notifikasi</h5>
        </div>

        <div class="notif-card">

            <div class="notif-item">
                <div class="notif-title">
                    Anak Anda telah dijemput oleh Ibu
                </div>

                <div class="notif-time">
                    Hari ini
                </div>
            </div>

            <div class="notif-item">
                <div class="notif-title">
                    Jadwal pulang hari ini pukul 13:00
                </div>

                <div class="notif-time">
                    Hari ini
                </div>
            </div>

            <div class="notif-item">
                <div class="notif-title">
                    Anak Anda berhasil absensi masuk pukul 07:32
                </div>

                <div class="notif-time">
                    Hari ini
                </div>
            </div>

            <div class="notif-item">
                <div class="notif-title">
                    Anak Anda berhasil absensi pulang pukul 13:00
                </div>

                <div class="notif-time">
                    Kemarin
                </div>
            </div>

            <div class="notif-item">
                <div class="notif-title">
                    Tanggal 18 Mei merupakan hari libur sekolah
                </div>

                <div class="notif-time">
                    17 Mei
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
