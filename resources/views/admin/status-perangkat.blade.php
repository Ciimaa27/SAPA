@extends('layouts.app')

@section('title','Status perangkat dan log aktivitas')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/status-perangkat.css') }}">
@endpush

@section('content')

@php use Carbon\Carbon; @endphp

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- TITLE -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Status perangkat dan log aktivitas</h5>
        </div>

        <!-- ================= STATUS PERANGKAT ================= -->
        <div class="row mb-3">
            @foreach($perangkat as $p)
            <div class="col-md-4">
                <div class="card p-3 status-card">

                    <!-- NAMA DEVICE -->
                    <h6 class="mb-2">{{ $p->nama_device ?? 'RFID Reader' }}</h6>

                    <!-- STATUS DEVICE -->
                    <div class="d-flex justify-content-between">
                        <span>Status</span>

                        @php
                            $status = strtolower(trim($p->status_koneksi ?? 'offline'));
                        @endphp

                        <span class="{{ $status === 'online' ? 'status-online' : 'status-offline' }}">
                            ● {{ ucfirst($status) }}
                        </span>
                    </div>

                    <!-- IP -->
                    <div class="d-flex justify-content-between">
                        <span>IP Address</span>
                        <span>{{ $p->ip ?? '-' }}</span>
                    </div>

                    <!-- LAST ACTIVE -->
                    <div class="text-danger small mt-2">
                        Terakhir aktif : {{ $p->last_active ?? '-' }}
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        <!-- ================= LOG AKTIVITAS ================= -->
        <div class="card">
            <div class="p-3">
                <h6 class="mb-0">Log aktivitas perangkat</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>ID Scan</th>
                            <th>Nama</th>
                            <th>Jenis perangkat</th>
                            <th>Peran</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($logs as $log)
                        <tr>

                            <!-- WAKTU -->
                            <td>
                                {{ Carbon::parse($log->created_at)->format('H:i:s') }}
                            </td>

                            <!-- ID -->
                            <td>
                                {{ $log->uid_rfid ?? $log->fingerprint_id }}
                            </td>

                            <!-- NAMA -->
                            <td>
                                @if($log->uid_rfid)
                                    {{ $log->nama_siswa ?? '-' }}
                                @else
                                    {{ $log->nama_wali ?? '-' }}
                                @endif
                            </td>

                            <!-- JENIS -->
                            <td>
                                {{ $log->uid_rfid ? 'RFID' : 'Fingerprint' }}
                            </td>

                            <!-- PERAN -->
                            <td>
                                {{ $log->peran }}
                            </td>

                            <!-- STATUS -->
                            <td>
                                <span class="badge
                                    {{ $log->status == 'gagal' ? 'bg-danger' : 'bg-success' }}">

                                    {{ ucfirst($log->status ?? 'berhasil') }}
                                </span>
                            </td>

                        </tr>
                        @endforeach

                        @if($logs->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endif
                    </tbody>

                </table>
            </div>

            <div class="p-3 d-flex justify-content-end">
                <nav>
                    <ul class="pagination pagination-sm mb-0">

                        {{-- Previous --}}
                        @if ($logs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        {{-- Numbers --}}
                        @php
                            $current = $logs->currentPage();
                            $last = $logs->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->url(1) }}">1</a>
                            </li>

                            @if ($current > 4)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        {{-- Middle pages --}}
                        @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Last page --}}
                        @if ($current < $last - 2)
                            @if ($current < $last - 3)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif

                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($logs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->nextPageUrl() }}">›</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">›</span>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>
        </div>

    </div>
</div>

@endsection
