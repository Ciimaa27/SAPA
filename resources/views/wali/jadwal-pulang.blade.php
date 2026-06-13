@extends('layouts.app')

@section('title','Jadwal Pulang')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/jadwal-pulang.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="page-title-card">
            <h5>Jadwal Pulang</h5>
        </div>

        <div class="class-card">
            <div class="class-tabs">
                <span class="btn-kelas active">
                    Kelas 3
                </span>
            </div>
        </div>

        <div class="schedule-card">

            <div class="schedule-card-top">
                <span class="text-muted">
                    Jadwal pulang siswa berdasarkan kelas
                </span>
            </div>

            <div class="jadwal-list">

                <div class="jadwal-row">
                    <div class="jadwal-hari">Senin</div>
                    <div class="jadwal-jam">11:30 WIB</div>
                </div>

                <div class="jadwal-row">
                    <div class="jadwal-hari">Selasa</div>
                    <div class="jadwal-jam">11:30 WIB</div>
                </div>

                <div class="jadwal-row">
                    <div class="jadwal-hari">Rabu</div>
                    <div class="jadwal-jam">13:00 WIB</div>
                </div>

                <div class="jadwal-row">
                    <div class="jadwal-hari">Kamis</div>
                    <div class="jadwal-jam">11:30 WIB</div>
                </div>

                <div class="jadwal-row">
                    <div class="jadwal-hari">Jumat</div>
                    <div class="jadwal-jam">10:30 WIB</div>
                </div>

                <div class="jadwal-row">
                    <div class="jadwal-hari">Sabtu</div>
                    <div class="jadwal-jam">Libur</div>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
