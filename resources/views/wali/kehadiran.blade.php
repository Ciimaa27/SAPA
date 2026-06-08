@extends('layouts.app')

@section('title', 'Kehadiran Anak')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/kehadiran.css') }}">
@endpush

@section('content')
<div class="main">

    <div class="card-box title">Kehadiran Anak</div>

    <div class="summary-grid">
        <div class="summary-card">
            <h6>Nama Anak</h6>
            <p>{{ $siswa->nama_siswa ?? '-' }}</p>
        </div>

        <div class="summary-card">
            <h6>Kelas</h6>
            <p>{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        </div>

        <div class="summary-card">
            <h6>Bulan</h6>
            <p>{{ $today->translatedFormat('F Y') }}</p>
        </div>

        <div class="summary-card">
            <h6>Total Hadir</h6>
            <p>{{ $stats['hadir'] }}</p>
        </div>
    </div>

    <div class="attendance-wrapper">

    <!-- KETERANGAN -->
    <div class="legend-box">

        <h6>Keterangan</h6>

        <div class="legend-item">
            <span class="legend-marker hadir"></span>
            Hadir
        </div>

        <div class="legend-item">
            <span class="legend-marker sakit"></span>
            Sakit
        </div>

        <div class="legend-item">
            <span class="legend-marker izin"></span>
            Izin
        </div>

        <div class="legend-item">
            <span class="legend-marker alpa"></span>
            Alpa
        </div>

    </div>

    <!-- KALENDER -->
    <div class="calendar-panel">

        <div class="calendar-title">
            <h5>{{ $today->translatedFormat('F Y') }}</h5>
        </div>

        <div class="calendar-weekdays">
            <div>Senin</div>
            <div>Selasa</div>
            <div>Rabu</div>
            <div>Kamis</div>
            <div>Jumat</div>
            <div>Sabtu</div>
            <div>Minggu</div>
        </div>

        <div class="calendar-grid">

            @foreach($calendarDays as $day)

                @php
                    $statusClass = $day['status']
                        ? 'status-' . $day['status']
                        : '';
                @endphp

                <div class="calendar-cell {{ $statusClass }}">

                    <div class="calendar-day">
                        {{ $day['date']->format('j') }}
                    </div>

                </div>

            @endforeach

        </div>

    </div>

    <!-- LAPORAN -->
    <div class="report-box">

        <h6>Laporan</h6>

        <div class="report-item">
            <span class="legend-marker hadir"></span>
            <span>{{ $stats['hadir'] }}</span>
        </div>

        <div class="report-item">
            <span class="legend-marker sakit"></span>
            <span>{{ $stats['sakit'] }}</span>
        </div>

        <div class="report-item">
            <span class="legend-marker izin"></span>
            <span>{{ $stats['izin'] }}</span>
        </div>

        <div class="report-item">
            <span class="legend-marker alpa"></span>
            <span>{{ $stats['alpa'] }}</span>
        </div>

    </div>

</div>

</div>
@endsection