@extends('layouts.app')

@section('title','Laporan')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/laporan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Laporan</h5>
        </div>

        <div class="card p-3 mb-3">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="kelas" class="form-select w-auto">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasOptions as $option)
                        <option value="{{ $option->id_kelas }}" {{ isset($kelasFilter) && $kelasFilter == $option->id_kelas ? 'selected' : '' }}>
                            {{ $option->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                <input
                    type="month"
                    name="bulan"
                    class="form-control w-auto"
                    value="{{ $bulan }}">

                <button type="submit" class="btn btn-primary">
                    Terapkan
                </button>
            </form>
        </div>

        <div class="card">
            <div class="table-responsive table-container">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Bulan</th>
                            <th>Total Kehadiran</th>
                            <th>Total Penjemputan</th>
                            <th>Aksi Kehadiran</th>
                            <th>Aksi Penjemputan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kelas as $kls)
                       <tr>
                    <td>{{ $kls->nama_kelas }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}
                    </td>

                    <td>
                        {{ $kehadiranCounts->get($kls->id_kelas, 0) }}
                    </td>

                    <td>
                        {{ $penjemputanCounts->get($kls->id_kelas, 0) }}
                    </td>

                    <td>
                        <a href="{{ route('laporan.kehadiran.export', [
                            'id_kelas' => $kls->id_kelas,
                            'bulan' => $bulan
                        ]) }}"
                        class="btn-excel"
                        title="Export Kehadiran">
                            <i class="fa-solid fa-file-excel"></i>
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('laporan.penjemputan.export', [
                            'id_kelas' => $kls->id_kelas,
                            'bulan' => $bulan
                        ]) }}"
                        class="btn-excel"
                        title="Export Penjemputan">
                            <i class="fa-solid fa-file-excel"></i>
                        </a>
                    </td>
                </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data kelas.
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
