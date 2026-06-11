@extends('layouts.app')

@section('title', 'Daftar Penjemputan Siswa')

@section('sidebar')
@include('layouts.sidebar-guru')
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('css/guru/daftar.css') }}">
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
<!-- JUDUL -->
<div class="card-box">
    <h5 class="page-title">
        Daftar Penjemputan siswa
    </h5>
</div>

<!-- INFORMASI -->
<div class="card-box mt-3">

    <a href="{{ route('guru.data-penjemputan') }}" class="btn-kembali">
        <i class="fa fa-angle-left"></i>
        Kembali
    </a>

    <div class="info-wrapper">

        <div class="info-row">
            <label>Kelas</label>
            <span>:</span>
            <input type="text" value="{{ $kelas->nama_kelas }}" readonly>
        </div>

        <div class="info-row">
            <label>Wali kelas</label>
            <span>:</span>
            <input type="text" value="{{ $kelas->guru ? $kelas->guru->nama_guru : 'N/A' }}" readonly>
        </div>

        <div class="info-row">
            <label>Tanggal</label>
            <span>:</span>
            <input type="text" value="{{ $today->format('d-m-Y') }}" readonly>
        </div>

    </div>

</div>

<!-- TABEL -->
<div class="card-box mt-3">

    <div class="table-header">
        <a href="#" class="btn-laporan">
            <i class="fa fa-download"></i>
            Laporan
        </a>
    </div>

    <div class="table-container">

        <table class="table-custom">

            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama lengkap</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($siswas as $row)
                <tr>
                    <td>{{ $row->nis }}</td>
                    <td>{{ $row->nama_siswa }}</td>
                    <td>
                        <span class="badge-{{ $row->status == 'Dijemput' ? 'purple' : 'orange' }}">
                            {{ $row->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Tidak ada siswa dalam kelas ini</td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="p-3 d-flex justify-content-end">
        <nav>
            <ul class="pagination pagination-sm mb-0">

                {{-- Previous --}}
                @if ($siswas->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">‹</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $siswas->previousPageUrl() }}">‹</a>
                    </li>
                @endif

                {{-- Numbers --}}
                @php
                    $current = $siswas->currentPage();
                    $last = $siswas->lastPage();
                @endphp

                {{-- First page --}}
                @if ($current > 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $siswas->url(1) }}">1</a>
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
                        <a class="page-link" href="{{ $siswas->url($i) }}">{{ $i }}</a>
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
                        <a class="page-link" href="{{ $siswas->url($last) }}">{{ $last }}</a>
                    </li>
                @endif

                {{-- Next --}}
                @if ($siswas->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $siswas->nextPageUrl() }}">›</a>
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
@endsection
