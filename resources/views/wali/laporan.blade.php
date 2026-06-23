@extends('layouts.app')

@section('title','Laporan')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/laporan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">

    <div class="container-dashboard">

        <!-- Judul -->
        <div class="page-title-card">
            <h5>Laporan</h5>
        </div>

        <!-- Informasi Anak -->
        <div class="student-card">

            <div class="student-avatar">
                <i class="fa-solid fa-user-graduate"></i>
            </div>

            <div class="student-info">
                <h6>{{ $siswa->nama_siswa ?? 'Data anak tidak ditemukan' }}</h6>
                <span>{{ $siswa && $siswa->kelas ? $siswa->kelas->nama_kelas : 'Kelas tidak tersedia' }}</span>
            </div>

        </div>

        <!-- Card Laporan -->
        <div class="report-card">

            <!-- Filter -->
            <div class="filter-section">

                <input type="date" class="filter-input">

                <select class="filter-select">
                    <option>Semua</option>
                    <option>Kehadiran</option>
                    <option>Penjemputan</option>
                </select>

                <button class="btn-search">
                    Cari
                </button>

                <button class="btn-refresh">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>

            </div>

            <!-- Table -->
            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Jenis Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                @forelse($kehadiranReports as $report)
                    <tr>
                        <td>Kehadiran {{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('F Y') }}.pdf</td>
                        <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}</td>
                        <td>Kehadiran</td>
                        <td>
                            <button class="btn-download" type="button">
                                Unduh
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada laporan kehadiran.</td>
                    </tr>
                @endforelse

                @forelse($penjemputanReports as $report)
                    <tr>
                        <td>Penjemputan {{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('F Y') }}.pdf</td>
                        <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}</td>
                        <td>Penjemputan</td>
                        <td>
                            <button class="btn-download" type="button">
                                Unduh
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada laporan penjemputan.</td>
                    </tr>
                @endforelse
            </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
