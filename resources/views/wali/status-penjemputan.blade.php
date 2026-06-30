@extends('layouts.app')

@section('title', 'Status Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/kehadiran.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/status-penjemputan.css') }}">
@endpush

@section('content')

<div class="main">

    <div class="card-box title">Status Penjemputan Anak</div>

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
            <h6>Total Penjemputan</h6>
            <p>{{ $stats['jemput'] }}</p>
        </div>
    </div>

    @if(isset($penjemputanToday) && $penjemputanToday && $penjemputanToday->jam_jemput)
    <div class="status-alert success">
        <span>!</span>
        Sudah dijemput hari ini pukul {{ \Carbon\Carbon::parse($penjemputanToday->jam_jemput)->format('H:i') }}
    </div>
    @else
    <div class="status-alert warning">
        <span>!</span>
        Belum dijemput hari ini
    </div>
    @endif

    <div class="attendance-wrapper">

        <div class="legend-box">
            <h6>Keterangan</h6>

            <div class="legend-item">
                <span class="legend-marker jemput"></span>
                Dijemput
            </div>

        </div>

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
                        $statusClass = $day['status'] ? 'status-jemput' : '';
                        $mutedClass = $day['currentMonth'] ? '' : 'calendar-cell--muted';
                    @endphp

                    <div class="calendar-cell {{ $statusClass }} {{ $mutedClass }}">

                        <div class="calendar-day">
                            {{ $day['date']->format('j') }}
                        </div>

                        @if($day['status'])
                        <div class="calendar-label">Jemput</div>
                        <div class="calendar-time">{{ $day['jam_jemput'] }}</div>
                        @endif

                    </div>

                @endforeach

            </div>

        </div>

        <div class="report-box">

            <h6>Laporan</h6>

            <div class="report-item">
                <span class="legend-marker jemput"></span>
                <span>{{ $stats['jemput'] }}</span>
            </div>

        </div>

    </div>

</div>

@endsection