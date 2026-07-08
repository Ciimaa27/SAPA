@extends('layouts.app')

@section('title', 'Relasi siswa dan wali')

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
<link rel="stylesheet" href="{{ asset('css/admin/relasi.css') }}">

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
            <h5 class="mb-0">Relasi siswa dan wali</h5>
        </div>

        {{-- ===========================
            FILTER DAN ACTION
        =========================== --}}
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                {{-- FILTER HUBUNGAN --}}
                <div style="width: 170px;">
                    <select id="filterRelasiHubungan" class="form-select form-select-sm">
                        <option value="">Tampilkan</option>
                        <option value="ibu" {{ request('hubungan') == 'ibu' ? 'selected' : '' }}>Ibu</option>
                        <option value="ayah" {{ request('hubungan') == 'ayah' ? 'selected' : '' }}>Ayah</option>
                        <option value="wali" {{ request('hubungan') == 'wali' ? 'selected' : '' }}>Wali</option>
                    </select>
                </div>

                {{-- BUTTON TAMBAH --}}
                <a href="{{ route('relasi.tambah') }}" class="btn-tambah">
                    <span>Tambah</span> <i class="fa fa-plus"></i>
                </a>

                {{-- PENCARIAN --}}
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputRelasi" class="form-control" placeholder="Pencarian" value="{{ request('search') }}" autocomplete="off">
                </div>

            </div>
        </div>

        {{-- ===========================
            NOTIFIKASI SUKSES
        =========================== --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===========================
            NOTIFIKASI ERROR SESSION
        =========================== --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===========================
            VALIDATION ERROR
        =========================== --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ===========================
            TABLE CARD
        =========================== --}}
        <div class="card">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTableRelasi">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama siswa</th>
                            <th>Nama orangtua/wali</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($relasi as $item)
                            <tr>
                                {{-- NOMOR --}}
                                <td>
                                    {{ ($relasi->currentPage() - 1) * $relasi->perPage() + $loop->iteration }}
                                </td>

                                {{-- NAMA SISWA --}}
                                <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>

                                {{-- NAMA WALI --}}
                                <td>{{ $item->wali->nama_wali ?? '-' }}</td>

                                {{-- NOMOR HP --}}
                                <td>{{ $item->wali->no_hp ?? '-' }}</td>

                                {{-- STATUS HUBUNGAN --}}
                                <td>{{ ucfirst($item->hubungan) }}</td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    {{-- EDIT --}}
                                    <a href="{{ route('relasi.edit', ['id_siswa' => $item->id_siswa, 'id_wali' => $item->id_wali]) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('relasi.destroy', [$item->id_siswa, $item->id_wali]) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Data tidak ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===========================
                PAGINATION
            =========================== --}}
            <div class="p-3 d-flex justify-content-end" id="paginationRelasi">
                @if ($relasi->lastPage() > 1)
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- PREVIOUS --}}
                            @if ($relasi->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">‹</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $relasi->previousPageUrl() }}">‹</a></li>
                            @endif

                            @php
                                $current = $relasi->currentPage();
                                $last = $relasi->lastPage();
                            @endphp

                            {{-- FIRST PAGE --}}
                            @if ($current > 3)
                                <li class="page-item"><a class="page-link" href="{{ $relasi->url(1) }}">1</a></li>
                                @if ($current > 4)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- MIDDLE PAGE --}}
                            @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $relasi->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- LAST PAGE --}}
                            @if ($current < $last - 2)
                                @if ($current < $last - 3)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><a class="page-link" href="{{ $relasi->url($last) }}">{{ $last }}</a></li>
                            @endif

                            {{-- NEXT --}}
                            @if ($relasi->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $relasi->nextPageUrl() }}">›</a></li>
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
    MODAL DELETE
=========================== --}}
<div class="confirm-modal" id="confirmModal">
    <div class="confirm-modal-backdrop"></div>
    <div class="confirm-modal-dialog">
        <div class="confirm-modal-content">
            <div class="confirm-modal-header">
                <h5>Hapus</h5>
            </div>
            <div class="confirm-modal-body">
                <p>Yakin ingin menghapus relasi siswa dan wali? Data tidak dapat dikembalikan.</p>
            </div>
            <div class="confirm-modal-footer">
                <button type="button" class="btn btn-secondary btn-sm btn-cancel">Batal</button>
                <button type="button" class="btn btn-danger btn-sm btn-confirm">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- ===========================
    JAVASCRIPT
=========================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("searchInputRelasi");
    const filter = document.getElementById("filterRelasiHubungan");
    const confirmModal = document.getElementById("confirmModal");
    const confirmBtn = document.querySelector(".btn-confirm");
    const cancelBtn = document.querySelector(".btn-cancel");
    const backdrop = document.querySelector(".confirm-modal-backdrop");
    const paginationContainer = document.getElementById("paginationRelasi");

    let searchTimer;
    let activeForm = null;

    // ===========================
    // LOAD DATA RELASI (AJAX)
    // ===========================
    async function loadRelasi(pageUrl = null) {
        const keyword = input.value.trim();
        const hubungan = filter.value;
        let requestUrl;

        if (pageUrl) {
            requestUrl = new URL(pageUrl, window.location.origin);
        } else {
            requestUrl = new URL("{{ route('relasi.index') }}", window.location.origin);
        }

        // Reset parameter lama
        requestUrl.searchParams.delete("search");
        requestUrl.searchParams.delete("hubungan");

        if (keyword !== "") requestUrl.searchParams.set("search", keyword);
        if (hubungan !== "") requestUrl.searchParams.set("hubungan", hubungan);
        if (!pageUrl) requestUrl.searchParams.delete("page");

        try {
            const response = await fetch(requestUrl.toString(), {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });
            if (!response.ok) throw new Error("Gagal mengambil data relasi");

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            // Update Table
            const newBody = doc.querySelector("#dataTableRelasi tbody");
            const currentBody = document.querySelector("#dataTableRelasi tbody");
            if (newBody && currentBody) {
                currentBody.innerHTML = newBody.innerHTML;
            }

            // Update Pagination
            const newPaginationContainer = doc.getElementById("paginationRelasi");
            if (newPaginationContainer && paginationContainer) {
                paginationContainer.innerHTML = newPaginationContainer.innerHTML;
            }

            // Update URL Browser
            window.history.replaceState({}, "", requestUrl.toString());
        } catch (error) {
            console.error("Pencarian relasi gagal:", error);
        }
    }

    // SEARCH OTOMATIS
    input.addEventListener("input", function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            loadRelasi();
        }, 700);
    });

    // FILTER HUBUNGAN
    filter.addEventListener("change", function () {
        clearTimeout(searchTimer);
        loadRelasi();
    });

    // PAGINATION AJAX
    paginationContainer.addEventListener("click", function (event) {
        const paginationLink = event.target.closest("a.page-link");
        if (!paginationLink) return;
        event.preventDefault();
        loadRelasi(paginationLink.href);
    });

    // BUKA MODAL DELETE
    document.addEventListener("click", function (event) {
        const deleteButton = event.target.closest(".btn-delete");
        if (!deleteButton) return;
        activeForm = deleteButton.closest(".delete-form");
        confirmModal.classList.add("show");
    });

    // KONFIRMASI DELETE
    confirmBtn.addEventListener("click", function () {
        if (!activeForm) return;
        confirmBtn.disabled = true;
        confirmBtn.textContent = "Menghapus...";
        activeForm.submit();
    });

    // TUTUP MODAL
    function closeModal() {
        confirmModal.classList.remove("show");
        activeForm = null;
        confirmBtn.disabled = false;
        confirmBtn.textContent = "Hapus";
    }

    cancelBtn.addEventListener("click", closeModal);
    backdrop.addEventListener("click", closeModal);
});
</script>
@endsection
