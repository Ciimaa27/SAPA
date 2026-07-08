@extends('layouts.app')

@section('title', 'Data Wali')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/data-wali.css') }}">

<style>
    .table-container {
        max-height: 400px;
        overflow-y: auto;
    }

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

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data wali</h5>
        </div>

        <!-- INFO + ACTION -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

             <div class="box-total">
                Total Wali : <strong>{{ $total }}</strong>
            </div>

                <!-- FILTER DROPDOWN -->
                <div style="width:180px;">
                    <select class="form-select form-select-sm">
                        <option>Tampilkan</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>

                <!-- BUTTON TAMBAH -->
                    <a href="{{ route('wali.create') }}" class="btn-tambah">
                        Tambah
                        <i class="fa fa-plus"></i>
                    </a>

                <!-- SEARCH BACKEND -->
                <div style="flex: 1;">
                    <form action="{{ route('data-wali') }}" method="GET" id="searchFormWali">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border">
                                <i class="fa fa-search"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                id="searchInputWali"
                                class="form-control"
                                placeholder="Pencarian"
                                value="{{ request('search') }}"
                                autocomplete="off"
                            >
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTableWali">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID Fingerprint</th>
                            <th>Nama orangtua/wali</th>
                            <th>No. HP</th>
                            <th>Jenis Kelamin</th>
                            <th class="col-aksi text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($wali as $row)
                        <tr>
                            <!-- 🔥 NOMOR TIDAK RESET -->
                            <td>{{ ($wali->currentPage() - 1) * $wali->perPage() + $loop->iteration }}</td>
                            <td>{{ $row->fingerprint_id ?? '-' }}</td>
                            <td>{{ $row->nama_wali }}</td>
                            <td>{{ $row->no_hp ?? '-' }}</td>
                            <td class="text-capitalize">{{ $row->jenis_kelamin ?? '-' }}</td>

                            <td class="text-center">
                                <a href="{{ route('edit-data-wali', ['id' => $row->id_wali]) }}"
                                class="btn btn-warning btn-sm"
                                title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
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
                        @if ($wali->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $wali->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        {{-- Numbers --}}
                        @php
                            $current = $wali->currentPage();
                            $last = $wali->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $wali->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $wali->url($i) }}">{{ $i }}</a>
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
                                <a class="page-link" href="{{ $wali->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($wali->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $wali->nextPageUrl() }}">›</a>
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

<!-- SEARCH SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("searchInputWali");

    let timer;

    input.addEventListener("input", function () {
        clearTimeout(timer);

        const keyword = this.value;

        timer = setTimeout(async function () {
            try {
                const url = new URL("{{ route('data-wali') }}", window.location.origin);

                if (keyword.trim() !== "") {
                    url.searchParams.set("search", keyword);
                }

                const response = await fetch(url.toString());
                const html = await response.text();

                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");

                // Ambil tabel baru dari hasil backend
                const newTable = doc.querySelector("#dataTableWali tbody");
                const currentTable = document.querySelector("#dataTableWali tbody");

                // Ambil pagination baru
                const newPagination = doc.querySelector(".pagination");
                const currentPagination = document.querySelector(".pagination");

                if (newTable && currentTable) {
                    currentTable.innerHTML = newTable.innerHTML;
                }

                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                }

            } catch (error) {
                console.error("Pencarian gagal:", error);
            }

        }, 500);
    });
});
</script>

@endsection
