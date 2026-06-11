@extends('layouts.app')

@section('title', 'Jadwal Pulang')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/jadwal-pulang.css') }}">
@endpush

@section('content')

<div class="main">

    <div class="card-box title">
        Jadwal Pulang Anak
    </div>

    <div class="summary-grid">

        <div class="summary-card">
            <h6>Nama Anak</h6>
            <p>{{ $siswa->nama_siswa }}</p>
        </div>

        <div class="summary-card">
            <h6>Kelas</h6>
            <p>{{ $siswa->kelas->nama_kelas }}</p>
        </div>

        <div class="summary-card">
            <h6>Hari Ini</h6>
            <p>{{ $jadwalHariIni }}</p>
        </div>

    </div>

    <div class="schedule-card">

        @foreach($jadwal as $item)

        <div class="jadwal-row">

            <div class="jadwal-hari">
                {{ $item['hari'] }}
            </div>

            <div class="jadwal-jam">
                {{ $item['libur'] ? 'Libur' : $item['jam'] }}
            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection
