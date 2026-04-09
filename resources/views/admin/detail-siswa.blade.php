@extends('layouts.app')

@section('title', 'Detail Data Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/lihat-data-siswa.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Data siswa
        </div>

        <!-- CARD -->
        <div class="card-detail">

            <!-- BUTTON KEMBALI -->
            <a href="{{ route('data-siswa') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <!-- DETAIL -->
            <div class="detail-wrapper">

                <div class="detail-row">
                    <div class="label">NIS</div>
                    <div class="separator">:</div>
                    <div class="value">{{ $siswa->nis }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Nama lengkap</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $siswa->nama_siswa }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Kelas</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $siswa->id_kelas }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Jenis kelamin</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $siswa->jenis_kelamin }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Tempat lahir</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $siswa->tempat_lahir }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Tanggal lahir</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $siswa->tanggal_lahir }}</div>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection