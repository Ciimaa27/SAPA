@extends('layouts.app')

@section('title', 'Detail Guru')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/lihat-data-guru.css') }}">
@endpush

{{-- 🔥 CONTENT --}}
@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Detail Guru
        </div>

        <!-- CARD -->
        <div class="card-detail">

            <!-- BUTTON KEMBALI -->
            <a href="{{ route('guru') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>

            <!-- DETAIL -->
            <div class="detail-wrapper">

                <div class="detail-row">
                    <div class="label">NIP</div>
                    <div class="separator">:</div>
                    <div class="value">{{ $guru->nip ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Nama lengkap</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $guru->nama_guru ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">No. HP</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $guru->no_hp ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Tempat lahir</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $guru->tempat_lahir ?? '-' }}</div>
                </div>

                <div class="detail-row">
                    <div class="label">Tanggal lahir</div>
                    <div class="separator">:</div>
                    <div class="value highlight">
                        {{ $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->format('d-m-Y') : '-' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div class="label">ID User</div>
                    <div class="separator">:</div>
                    <div class="value highlight">{{ $guru->id_user ?? '-' }}</div>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
