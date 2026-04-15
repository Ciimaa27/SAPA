@extends('layouts.app')

@section('title', 'Dashboard Admin')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

{{-- 🔥 CONTENT --}}
@section('content')
<div class="main-dashboard">

    <!-- ================= DATA AKUN ================= -->
    <div class="section">
        <h6 class="section-title">Data akun</h6>

        <div class="row g-3">

            <div class="col-md-3">
                <div class="card-dashboard">
                    <h3>{{ $totalSiswa }}</h3>
                    <p>Total Siswa</p>
                    <i class="fa fa-users icon-orange"></i>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <h3>{{ $totalWali }}</h3>
                    <p>Total akun wali</p>
                    <i class="fa fa-user-friends icon-orange"></i>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <h3>{{ $totalGuru }}</h3>
                    <p>Total akun guru</p>
                    <i class="fa fa-chalkboard-teacher icon-orange"></i>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <h3>{{ $totalKepsek }}</h3>
                    <p>Total akun kepsek</p>
                    <i class="fa fa-user icon-orange"></i>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= DATA HARI INI ================= -->
    <div class="section">
        <h6 class="section-title">Data hari ini</h6>

        <div class="row g-3">

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Kehadiran hari ini</p>
                    <h3>{{ $hadirHariIni }}</h3>
                    <div class="badge-icon up">
                        <i class="fa fa-arrow-up"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Tidak hadir</p>
                    <h3>{{ $tidakHadir }}</h3>
                    <div class="badge-icon down">
                        <i class="fa fa-arrow-down"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Sudah dijemput</p>
                    <h3>{{ $sudahJemput }}</h3>
                    <div class="badge-icon success">
                        <i class="fa fa-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-dashboard">
                    <p>Belum dijemput</p>
                    <h3>{{ $belumJemput }}</h3>
                    <div class="badge-icon danger">
                        <i class="fa fa-times"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= GRAFIK ================= -->
    <div class="row g-3 mt-2">

        <div class="col-md-8">
            <div class="card-dashboard">
                <h6>Grafik Kehadiran</h6>
                <canvas id="chartBar"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-dashboard">
                <h6>Grafik Penjemputan</h6>
                <canvas id="chartDonut"></canvas>
            </div>
        </div>

    </div>

    <!-- ================= LOG ================= -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card-dashboard">
                <h6 class="mb-3">Log aktivitas perangkat</h6>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>ID Scan</th>
                            <th>Nama</th>
                            <th>Jenis Perangkat</th>
                            <th>Peran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            {{-- Waktu --}}
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i d/m/Y') }}</td>

                            {{-- ID Scan --}}
                            <td>
                                @if($log->uid_rfid)
                                    {{ $log->uid_rfid }}
                                @elseif($log->fingerprint_id)
                                    {{ $log->fingerprint_id }}
                                @else
                                    -
                                @endif
                            <td>
                                @if($log->uid_rfid)
                                    {{-- Nama Siswa --}}
                                    @php
                                        $siswa = \App\Models\Siswa::whereRaw('LOWER(rfid_uid) = ?', [strtolower($log->uid_rfid)])->first();
                                    @endphp
                                    {{ $siswa->nama_siswa ?? '-' }}
                                @elseif($log->fingerprint_id)
                                    {{-- Nama Wali/Orang Tua --}}
                                    @php
                                        $wali = \App\Models\Wali::where('fingerprint_id', $log->fingerprint_id)->first();
                                    @endphp
                                    {{ $wali->nama_wali ?? 'Orang Tua' }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- Jenis Perangkat --}}
                            <td>{{ $log->device->nama_device ?? '-' }}</td> <!-- Jenis perangkat -->
                            <td>
                                @if($log->uid_rfid) Siswa
                                @elseif($log->fingerprint_id) Orang Tua
                                @else - @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($log->uid_rfid)
                                    <span class="badge bg-success">Berhasil</span>
                                @elseif($log->fingerprint_id)
                                    <span class="badge bg-primary">Sudah dijemput</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginasi --}}
                {{-- <div class="mt-2">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div> --}}
            </div>
        </div>
    </div>

</div>

<!-- ================= CHART ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // BAR CHART
    const ctx = document.getElementById('chartBar');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($dataHadir),
                    backgroundColor: '#8e6cc9'
                },
                {
                    label: 'Tidak hadir',
                    data: @json($dataTidakHadir),
                    backgroundColor: '#3ec7b7'
                }
            ]
        }
    });

    // DONUT CHART
    const donut = document.getElementById('chartDonut');
    new Chart(donut, {
        type: 'doughnut',
        data: {
            labels: ['Sudah dijemput', 'Belum dijemput'],
            datasets: [{
                data: [{{ $sudahJemput }}, {{ $belumJemput }}],
                backgroundColor: ['#34d1bf', '#ff7b7b']
            }]
        }
    });
</script>

@endsection
