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
<form method="GET" action="{{ route('wali.laporan') }}">


    <input
        type="date"
        name="tanggal"
        value="{{ request('tanggal') }}"
        class="filter-input">

    <select
        name="jenis"
        class="filter-select">

        <option value="">Semua</option>

        <option value="Kehadiran"
            {{ request('jenis') == 'Kehadiran' ? 'selected' : '' }}>
            Kehadiran
        </option>

        <option value="Penjemputan"
            {{ request('jenis') == 'Penjemputan' ? 'selected' : '' }}>
            Penjemputan
        </option>

    </select>

    <button type="submit" class="btn-search">
    Cari
</button>

<button
    type="button"
    class="btn-refresh"
    onclick="window.location='{{ route('wali.laporan') }}'">

    <i class="fa-solid fa-rotate-right"></i>

</button>

</div>

</form>

                

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

    <td>
        Kehadiran
        {{ \Carbon\Carbon::create($report->tahun, $report->bulan, 1)->translatedFormat('F Y') }}.pdf
    </td>

    <td>
        {{ \Carbon\Carbon::create($report->tahun, $report->bulan, 1)->translatedFormat('F Y') }}
    </td>

    <td>
        Kehadiran
    </td>

    <td>

        <div class="d-flex gap-2">

            <a href="{{ route('wali.export.pdf',[
                'bulan'=>$report->bulan,
                'tahun'=>$report->tahun
            ]) }}"
            class="btn btn-danger"
            target="_blank">

                <i class="fas fa-file-pdf"></i>

            </a>

            <a href="{{ route('wali.export.excel',[
                'bulan'=>$report->bulan,
                'tahun'=>$report->tahun
            ]) }}"
            class="btn btn-success">

                <i class="fas fa-file-excel"></i>

            </a>

        </div>

    </td>

</tr>
@empty
<tr>
    <td colspan="4">Tidak ada laporan kehadiran.</td>
</tr>
@endforelse

@forelse($penjemputanReports as $report)

<tr>

    <td>
        Penjemputan
        {{ \Carbon\Carbon::create($report->tahun, $report->bulan, 1)->translatedFormat('F Y') }}.pdf
    </td>

    <td>
        {{ \Carbon\Carbon::create($report->tahun, $report->bulan, 1)->translatedFormat('F Y') }}
    </td>

    <td>
        Penjemputan
    </td>

    <td>
        <div class="d-flex gap-2">
            <a href="{{ route('wali.export.pdf.penjemputan', [
                'bulan' => $report->bulan,
                'tahun' => $report->tahun,
            ]) }}"
            class="btn btn-danger"
            target="_blank">
                <i class="fas fa-file-pdf"></i>
            </a>

            <a href="{{ route('wali.export.excel.penjemputan', [
                'bulan' => $report->bulan,
                'tahun' => $report->tahun,
            ]) }}"
            class="btn btn-success">
                <i class="fas fa-file-excel"></i>
            </a>
        </div>
    </td>

</tr>

@empty

<tr>
    <td colspan="4">
        Tidak ada laporan penjemputan.
    </td>
</tr>

@endforelse
            </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
