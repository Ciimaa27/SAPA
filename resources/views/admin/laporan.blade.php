@extends('layouts.app')

@section('title', 'Laporan')

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

        {{-- ================= JUDUL ================= --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Laporan</h5>
        </div>

        {{-- ================= FILTER ================= --}}
        <div class="card p-3 mb-3">
            <form method="GET" class="filter-laporan" id="formFilter">
                {{-- FILTER KELAS --}}
                <select name="kelas" class="form-select filter-kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasOptions as $option)
                        <option value="{{ $option->id_kelas }}" {{ isset($kelasFilter) && $kelasFilter == $option->id_kelas ? 'selected' : '' }}>
                            {{ $option->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                {{-- PILIH TANGGAL --}}
                <div class="month-picker">
                    <input type="date" name="tanggal" class="month-picker-button" value="{{ $tanggal }}">
                </div>

                {{-- BUTTON TERAPKAN --}}
                <button type="submit" class="btn btn-primary btn-terapkan">Terapkan</button>
            </form>
        </div>

        {{-- ================= TABEL ================= --}}
        <div class="card">
            <div class="table-responsive table-container">
                <table class="table align-middle mb-0">
                    {{-- HEADER --}}
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Total Kehadiran</th>
                            <th>Total Penjemputan</th>
                            <th>Aksi Kehadiran</th>
                            <th>Aksi Penjemputan</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody>
                        @forelse ($kelas as $kls)
                            <tr>
                                {{-- KELAS --}}
                                <td>{{ $kls->nama_kelas }}</td>

                                {{-- TANGGAL --}}
                                <td>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') }}</td>

                                {{-- TOTAL KEHADIRAN --}}
                                <td>{{ $kehadiranCounts->get($kls->id_kelas, 0) }}</td>

                                {{-- TOTAL PENJEMPUTAN --}}
                                <td>{{ $penjemputanCounts->get($kls->id_kelas, 0) }}</td>

                                {{-- EXPORT KEHADIRAN --}}
                                <td>
                                    <a href="{{ route('laporan.kehadiran.export', ['id_kelas' => $kls->id_kelas, 'tanggal' => $tanggal]) }}" class="btn-excel" title="Export Kehadiran">
                                        <i class="fa-solid fa-file-excel"></i>
                                    </a>
                                </td>

                                {{-- EXPORT PENJEMPUTAN --}}
                                <td>
                                    <a href="{{ route('laporan.penjemputan.export', ['id_kelas' => $kls->id_kelas, 'tanggal' => $tanggal]) }}" class="btn-excel" title="Export Penjemputan">
                                        <i class="fa-solid fa-file-excel"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ================= PAGINATION ================= --}}
            @if ($kelas->hasPages())
                <div class="p-3 d-flex justify-content-end">
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- PREVIOUS --}}
                            @if ($kelas->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">‹</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $kelas->previousPageUrl() }}">‹</a></li>
                            @endif

                            @php
                                $current = $kelas->currentPage();
                                $last = $kelas->lastPage();
                            @endphp

                            {{-- FIRST PAGE --}}
                            @if ($current > 3)
                                <li class="page-item"><a class="page-link" href="{{ $kelas->url(1) }}">1</a></li>
                                @if ($current > 4)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- MIDDLE PAGE --}}
                            @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $kelas->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- LAST PAGE --}}
                            @if ($current < $last - 2)
                                @if ($current < $last - 3)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><a class="page-link" href="{{ $kelas->url($last) }}">{{ $last }}</a></li>
                            @endif

                            {{-- NEXT --}}
                            @if ($kelas->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $kelas->nextPageUrl() }}">›</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">›</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
