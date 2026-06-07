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

    <div class="calendar-layout">

        <!-- CALENDAR -->
        <div class="calendar-panel">

            <div class="calendar-title">
                <h5>Kehadiran Bulan {{ $today->translatedFormat('F Y') }}</h5>
            </div>

            <div class="calendar-weekdays">
                <div>Sen</div>
                <div>Sel</div>
                <div>Rab</div>
                <div>Kam</div>
                <div>Jum</div>
                <div>Sab</div>
                <div>Min</div>
            </div>

            <div class="calendar-grid">
                @foreach($calendarDays as $day)

                    @php
                        $statusClass = $day['status'] ? 'status-' . $day['status'] : '';
                    @endphp

                    <div class="calendar-cell {{ $day['currentMonth'] ? '' : 'calendar-cell--muted' }} {{ $statusClass }}">
                        
                        <div class="calendar-day">
                            {{ $day['date']->format('j') }}
                        </div>

                        @if($day['status'])
                            <div class="calendar-dot"></div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

        <!-- SIDEBAR -->
        <div class="calendar-panel">

            <div class="status-summary">
                <div class="status-item">
                    <span>Hadir</span>
                    <strong>{{ $stats['hadir'] }}</strong>
                </div>

                <div class="status-item">
                    <span>Sakit</span>
                    <strong>{{ $stats['sakit'] }}</strong>
                </div>

                <div class="status-item">
                    <span>Izin</span>
                    <strong>{{ $stats['izin'] }}</strong>
                </div>

                <div class="status-item">
                    <span>Alpa</span>
                    <strong>{{ $stats['alpa'] }}</strong>
                </div>
            </div>

            <div class="legend">
                <div class="legend-item">
                    <span class="legend-marker" style="background:#8fd4b7"></span>Hadir
                </div>

                <div class="legend-item">
                    <span class="legend-marker" style="background:#f0bc5d"></span>Sakit
                </div>

                <div class="legend-item">
                    <span class="legend-marker" style="background:#92b7ff"></span>Izin
                </div>

                <div class="legend-item">
                    <span class="legend-marker" style="background:#ff9e9e"></span>Alpa
                </div>
            </div>

        </div>

    </div>

</div>
@endsection