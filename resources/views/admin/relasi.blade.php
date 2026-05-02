@extends('layouts.app')

@section('title', 'Relasi siswa dan wali')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/relasi.css') }}">

<style>
    /* Scroll hanya tabel */
    .table-container {
        max-height: 400px;
        overflow-y: auto;
    }

    /* Header sticky */
    .table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }
</style>
@endpush

{{-- 🔥 CONTENT --}}
@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Relasi siswa dan wali</h5>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <!-- 🔍 SEARCH (DITAMBAH ID) -->
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputRelasi" class="form-control" placeholder="Pencarian">
                </div>

                <div style="width:170px;">
                    <select class="form-select form-select-sm">
                        <option>Tampilkan</option>
                        <option>Ibu</option>
                        <option>Ayah</option>
                        <option>Wali</option>
                    </select>
                </div>

                <a href="{{ route('relasi.tambah') }}" class="btn btn-primary btn-sm btn-tambah">
                    <i class="fa fa-plus"></i> Tambah
                </a>

            </div>
        </div>

        <div class="card">
            <div class="table-container">
                <!-- 🔥 TAMBAH ID DI TABEL -->
                <table class="table table-hover align-middle mb-0" id="dataTableRelasi">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama siswa</th>
                            <th>Nama orangtua/wali</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($relasi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $item->wali->nama_wali ?? '-' }}</td>
                            <td>{{ $item->wali->no_hp ?? '-' }}</td>
                            <td>{{ ucfirst($item->hubungan) }}</td>
                            <td>
                                 <a href="{{ route('edit-relasi') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <form action="{{ route('relasi.destroy', [$item->id_siswa, $item->id_wali]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data tidak ada</td>
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
                        @if ($relasi->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $relasi->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        {{-- Numbers --}}
                        @php
                            $current = $relasi->currentPage();
                            $last = $relasi->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $relasi->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $relasi->url($i) }}">{{ $i }}</a>
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
                                <a class="page-link" href="{{ $relasi->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($relasi->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $relasi->nextPageUrl() }}">›</a>
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

<!-- 🔥 SCRIPT SEARCH -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputRelasi");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableRelasi tbody tr");

        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});
</script>

@endsection
