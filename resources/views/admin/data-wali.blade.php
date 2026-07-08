@extends('layouts.app')

@section('title', 'Data Wali')

{{-- ===========================
    SIDEBAR
=========================== --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- ===========================
    CSS
=========================== --}}
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
    .alert {
        margin-bottom: 15px;
    }
</style>
@endpush

{{-- ===========================
    CONTENT
=========================== --}}
@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- ===========================
            HEADER
        =========================== --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data wali</h5>
        </div>

        {{-- ===========================
            INFO DAN ACTION
        =========================== --}}
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                {{-- TOTAL WALI --}}
                <div class="box-total">
                    Total Wali : <strong>{{ $total }}</strong>
                </div>

                {{-- ===========================
                    FILTER JENIS KELAMIN
                =========================== --}}
                <div style="width: 180px;">
                    <select class="form-select form-select-sm" id="filterJenisKelamin">
                        <option value="">Jenis Kelamin</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>

                {{-- ===========================
                    BUTTON TAMBAH
                =========================== --}}
                <a href="{{ route('wali.create') }}" class="btn-tambah">
                    Tambah <i class="fa fa-plus"></i>
                </a>

                {{-- ===========================
                    SEARCH
                =========================== --}}
                <div style="flex: 1;">
                    <form action="{{ route('data-wali') }}" method="GET" id="searchFormWali">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" name="search" id="searchInputWali" class="form-control" placeholder="Pencarian" value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </form>
                </div>

            </div>
        </div>

        {{-- ===========================
            ALERT SUCCESS
        =========================== --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===========================
            ALERT ERROR
        =========================== --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===========================
            TABLE
        =========================== --}}
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
                        @forelse ($wali as $row)
                            <tr>
                                {{-- NOMOR TIDAK RESET --}}
                                <td>
                                    {{ ($wali->currentPage() - 1) * $wali->perPage() + $loop->iteration }}
                                </td>

                                {{-- ID FINGERPRINT --}}
                                <td>{{ $row->fingerprint_id ?? '-' }}</td>

                                {{-- NAMA WALI --}}
                                <td>{{ $row->nama_wali }}</td>

                                {{-- NOMOR HP --}}
                                <td>{{ $row->no_hp ?? '-' }}</td>

                                {{-- JENIS KELAMIN --}}
                                <td class="text-capitalize">{{ $row->jenis_kelamin ?? '-' }}</td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <a href="{{ route('edit-data-wali', ['id' => $row->id_wali]) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="6" class="text-center py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===========================
                PAGINATION
            =========================== --}}
            <div class="p-3 d-flex justify-content-end" id="paginationContainer">
                @if ($wali->lastPage() > 1)
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- PREVIOUS --}}
                            @if ($wali->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">‹</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $wali->previousPageUrl() }}">‹</a></li>
                            @endif

                            @php
                                $current = $wali->currentPage();
                                $last = $wali->lastPage();
                            @endphp

                            {{-- FIRST PAGE --}}
                            @if ($current > 3)
                                <li class="page-item"><a class="page-link" href="{{ $wali->url(1) }}">1</a></li>
                                @if ($current > 4)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- MIDDLE PAGES --}}
                            @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $wali->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- LAST PAGE --}}
                            @if ($current < $last - 2)
                                @if ($current < $last - 3)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><a class="page-link" href="{{ $wali->url($last) }}">{{ $last }}</a></li>
                            @endif

                            {{-- NEXT --}}
                            @if ($wali->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $wali->nextPageUrl() }}">›</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">›</span></li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ===========================
    JAVASCRIPT
=========================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInputWali");
    const filterJenisKelamin = document.getElementById("filterJenisKelamin");
    const tableBody = document.querySelector("#dataTableWali tbody");
    const paginationContainer = document.getElementById("paginationContainer");
    let searchTimer;

    // ===========================
    // FILTER JENIS KELAMIN
    // ===========================
    function filterJenisKelaminData() {
        const jenisKelamin = filterJenisKelamin.value.trim().toLowerCase();
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach(function (row) {
            if (row.classList.contains("empty-row")) {
                return;
            }

            const genderCell = row.cells[4];
            if (!genderCell) {
                return;
            }

            const gender = genderCell.textContent.trim().toLowerCase();
            const cocok = jenisKelamin === "" || gender === jenisKelamin;
            row.style.display = cocok ? "" : "none";
        });
    }

    // ===========================
    // AMBIL DATA DARI BACKEND
    // ===========================
    async function loadData(url) {
        try {
            const response = await fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            if (!response.ok) {
                throw new Error("Gagal mengambil data");
            }

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            const newTableBody = doc.querySelector("#dataTableWali tbody");
            const newPagination = doc.querySelector("#paginationContainer");

            if (newTableBody && tableBody) {
                tableBody.innerHTML = newTableBody.innerHTML;
            }

            if (newPagination && paginationContainer) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }

            filterJenisKelaminData();
            window.history.replaceState({}, "", url);
        } catch (error) {
            console.error("Pencarian gagal:", error);
        }
    }

    // ===========================
    // SEARCH BACKEND
    // ===========================
    searchInput.addEventListener("input", function () {
        clearTimeout(searchTimer);
        const keyword = this.value.trim();

        searchTimer = setTimeout(function () {
            const url = new URL("{{ route('data-wali') }}", window.location.origin);
            if (keyword !== "") {
                url.searchParams.set("search", keyword);
            }
            loadData(url.toString());
        }, 500);
    });

    // ===========================
    // FILTER JENIS KELAMIN EVENT
    // ===========================
    filterJenisKelamin.addEventListener("change", function () {
        filterJenisKelaminData();
    });

    // ===========================
    // PAGINATION AJAX
    // ===========================
    paginationContainer.addEventListener("click", function (event) {
        const link = event.target.closest("a.page-link");
        if (!link) {
            return;
        }
        event.preventDefault();
        loadData(link.href);
    });
});
</script>
@endsection
