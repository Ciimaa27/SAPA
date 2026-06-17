@extends('layouts.app')

@section('title', 'Detail Arsip Siswa')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/arsip-siswa-detail.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="page-title-box">
            Arsip Siswa Tahun {{ $tahun }}
            ({{ ucfirst(str_replace('_', ' ', $status)) }})
        </div>

        <div class="card-form">
            <div class="mb-3">
                <a href="{{ route('arsip-siswa') }}" class="btn btn-kembali mb-3">
                    ← Kembali
                </a>

                 <a href="{{ route('arsip-siswa.export', $tahun) }}"
                    class="btn btn-success">
                        <i class="fa fa-file-excel"></i>
                        Export Excel
                    </a>
                </div>

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas Terakhir</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                            <th>Tahun Lulus</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($arsip as $item)

                        <tr>
                            <td>{{ $item->nis }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->kelas_terakhir }}</td>
                            <td>{{ $item->jenis_kelamin }}</td>

                            <td>
                                @if($item->status == 'lulus')
                                    <span class="badge bg-success">Lulus</span>
                                @elseif($item->status == 'pindah')
                                    <span class="badge bg-warning text-dark">Pindah</span>
                                @elseif($item->status == 'mengundurkan_diri')
                                    <span class="badge bg-danger">Mengundurkan Diri</span>
                                @endif
                            </td>

                            <td>{{ $item->tahun_lulus }}</td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data arsip.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-3 d-flex justify-content-end">
                <nav>
                    <ul class="pagination pagination-sm mb-0">

                        {{-- Previous --}}
                        @if ($arsip->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $arsip->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        @php
                            $current = $arsip->currentPage();
                            $last = $arsip->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $arsip->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $arsip->url($i) }}">{{ $i }}</a>
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
                                <a class="page-link" href="{{ $arsip->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($arsip->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $arsip->nextPageUrl() }}">›</a>
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