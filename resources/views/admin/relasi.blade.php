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

        <!-- TAMPILKAN -->
        <div style="width:170px;">
<select id="filterRelasiHubungan" class="form-select form-select-sm">
    <option value="">Tampilkan</option>

    <option value="ibu"
        {{ request('hubungan') == 'ibu' ? 'selected' : '' }}>
        Ibu
    </option>

    <option value="ayah"
        {{ request('hubungan') == 'ayah' ? 'selected' : '' }}>
        Ayah
    </option>

    <option value="wali"
        {{ request('hubungan') == 'wali' ? 'selected' : '' }}>
        Wali
    </option>
</select>
        </div>

        <!-- TAMBAH -->
        <a href="{{ route('relasi.tambah') }}" class="btn-tambah">
            <span>Tambah</span>
            <i class="fa fa-plus"></i>
        </a>

        <!-- PENCARIAN -->
        <div class="input-group input-group-sm search-flex">
            <span class="input-group-text bg-white">
                <i class="fa fa-search"></i>
            </span>
            <input type="text"
       id="searchInputRelasi"
       class="form-control"
       placeholder="Pencarian"
       value="{{ request('search') }}"
       autocomplete="off">
        </div>

    </div>
</div>


        <div class="card">
            <div class="table-container table-responsive">
                <!-- 🔥 TAMBAH ID DI TABEL -->
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
                        @forelse($relasi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $item->wali->nama_wali ?? '-' }}</td>
                            <td>{{ $item->wali->no_hp ?? '-' }}</td>
                            <td>{{ ucfirst($item->hubungan) }}</td>
                            <td class="text-center">
                                 <a href="{{ route('relasi.edit', ['id_siswa' => $item->id_siswa, 'id_wali' => $item->id_wali]) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <form action="{{ route('relasi.destroy', [$item->id_siswa, $item->id_wali]) }}"
                                    method="POST"
                                    class="delete-form"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete"
                                            title="Hapus">
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

<!-- MODAL DELETE -->
<div class="confirm-modal" id="confirmModal">
    <div class="confirm-modal-backdrop"></div>

    <div class="confirm-modal-dialog">
        <div class="confirm-modal-content">

            <div class="confirm-modal-header">
                <h5>Hapus</h5>
            </div>

            <div class="confirm-modal-body">
                <p>
                    Yakin ingin menghapus relasi siswa dan wali?
                    Data tidak dapat dikembalikan.
                </p>
            </div>

            <div class="confirm-modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm btn-cancel">
                    Batal
                </button>

                <button type="button"
                        class="btn btn-danger btn-sm btn-confirm">
                    Hapus
                </button>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT SEARCH, FILTER, PAGINATION, DELETE -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const input =
        document.getElementById("searchInputRelasi");

    const filter =
        document.getElementById("filterRelasiHubungan");

    const confirmModal =
        document.getElementById("confirmModal");

    const confirmBtn =
        document.querySelector(".btn-confirm");

    const cancelBtn =
        document.querySelector(".btn-cancel");

    const backdrop =
        document.querySelector(".confirm-modal-backdrop");

    let searchTimer;
    let activeForm = null;


    // =========================
    // LOAD DATA RELASI
    // =========================
    async function loadRelasi(url = null) {

        const keyword = input.value.trim();
        const hubungan = filter.value;

        try {

            let requestUrl;

            if (url) {

                requestUrl = new URL(
                    url,
                    window.location.origin
                );

            } else {

                requestUrl = new URL(
                    "{{ route('relasi.index') }}",
                    window.location.origin
                );

                if (keyword !== "") {
                    requestUrl.searchParams.set(
                        "search",
                        keyword
                    );
                }

                if (hubungan !== "") {
                    requestUrl.searchParams.set(
                        "hubungan",
                        hubungan
                    );
                }
            }


            const response =
                await fetch(requestUrl.toString());

            const html =
                await response.text();

            const parser =
                new DOMParser();

            const doc =
                parser.parseFromString(
                    html,
                    "text/html"
                );


            // =========================
            // UPDATE TABEL
            // =========================
            const newBody =
                doc.querySelector(
                    "#dataTableRelasi tbody"
                );

            const currentBody =
                document.querySelector(
                    "#dataTableRelasi tbody"
                );

            if (newBody && currentBody) {

                currentBody.innerHTML =
                    newBody.innerHTML;
            }


            // =========================
            // UPDATE PAGINATION
            // =========================
            const newPagination =
                doc.querySelector(".pagination");

            const currentPagination =
                document.querySelector(".pagination");

            if (newPagination && currentPagination) {

                currentPagination.innerHTML =
                    newPagination.innerHTML;

            } else if (
                !newPagination &&
                currentPagination
            ) {

                currentPagination.innerHTML = "";
            }

        } catch (error) {

            console.error(
                "Pencarian relasi gagal:",
                error
            );
        }
    }


    // =========================
    // SEARCH OTOMATIS
    // =========================
    input.addEventListener("input", function () {

        clearTimeout(searchTimer);

        searchTimer = setTimeout(function () {

            loadRelasi();

        }, 700);

    });


    // =========================
    // FILTER HUBUNGAN
    // =========================
    filter.addEventListener("change", function () {

        clearTimeout(searchTimer);

        loadRelasi();

    });


    // =========================
    // PAGINATION TANPA RELOAD
    // =========================
    document.addEventListener("click", function (event) {

        const paginationLink =
            event.target.closest(
                ".pagination a.page-link"
            );

        if (!paginationLink) {
            return;
        }

        event.preventDefault();

        loadRelasi(
            paginationLink.href
        );

    });


    // =========================
    // BUKA POPUP DELETE
    // =========================
    document.addEventListener("click", function (event) {

        const deleteButton =
            event.target.closest(".btn-delete");

        if (!deleteButton) {
            return;
        }

        activeForm =
            deleteButton.closest(".delete-form");

        confirmModal.classList.add("show");

    });


    // =========================
    // KONFIRMASI DELETE
    // =========================
    confirmBtn.addEventListener("click", function () {

        if (activeForm) {

            confirmBtn.disabled = true;

            confirmBtn.textContent =
                "Menghapus...";

            activeForm.submit();
        }

    });


    // =========================
    // TUTUP POPUP
    // =========================
    function closeModal() {

        confirmModal.classList.remove("show");

        activeForm = null;
    }


    cancelBtn.addEventListener(
        "click",
        closeModal
    );


    backdrop.addEventListener(
        "click",
        closeModal
    );

});
</script>

@endsection
