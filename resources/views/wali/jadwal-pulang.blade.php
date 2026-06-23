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
                    Kelas {{ $siswa?->id_kelas ?? '-' }}
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
                @forelse($jadwalList as $item)
                    <div class="jadwal-row">
                        <div class="jadwal-hari">{{ $item['hari'] }}</div>
                        <div class="jadwal-jam">{{ $item['jam'] }}</div>
                    </div>
                @empty
                    <div class="jadwal-row">
                        <p class="text-muted text-center py-4">Jadwal pulang belum diatur oleh admin</p>
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</div>

@endsection
