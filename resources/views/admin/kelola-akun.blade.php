@extends('layouts.app')

@section('title', 'Kelola Akun')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/kelola-akun.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3 sticky-header">
            <h5 class="mb-0">Kelola akun pengguna</h5>
        </div>

        <!-- TOOLBAR -->
        <div class="card mb-3 p-3 sticky-toolbar">
            <div class="d-flex align-items-center gap-3 flex-wrap">

        <div class="total-data">
            Total Akun : <strong>{{ $total }}</strong>
        </div>

                <!-- FILTER -->
<div style="width:180px;">
    <form method="GET"
          action="{{ route('kelola-akun.index') }}"
          id="filterForm">

        <input type="hidden"
               name="search"
               value="{{ request('search') }}">

        <select name="role"
                class="form-select form-select-sm"
                onchange="this.form.submit()">

            <option value="">Semua Peran</option>

            <option value="Admin"
                {{ request('role') == 'Admin' ? 'selected' : '' }}>
                Admin
            </option>

            <option value="Guru"
                {{ request('role') == 'Guru' ? 'selected' : '' }}>
                Guru
            </option>

            <option value="Kepala Sekolah"
                {{ request('role') == 'Kepala Sekolah' ? 'selected' : '' }}>
                Kepala Sekolah
            </option>

            <option value="Orangtua/Wali"
                {{ request('role') == 'Orangtua/Wali' ? 'selected' : '' }}>
                Wali
            </option>

        </select>
    </form>
</div>


<!-- TAMBAH -->
<a href="{{ route('kelola-akun.create') }}"
   class="btn-tambah">
    Tambah
    <i class="fa fa-plus"></i>
</a>


<!-- SEARCH -->
<form method="GET"
      action="{{ route('kelola-akun.index') }}"
      id="searchFormAkun"
      style="flex: 1;">

    <input type="hidden"
           name="role"
           value="{{ request('role') }}">

    <div class="input-group input-group-sm">

        <span class="input-group-text bg-white border">
            <i class="fa fa-search"></i>
        </span>

        <input type="text"
               name="search"
               id="searchInputAkun"
               value="{{ request('search') }}"
               class="form-control"
               placeholder="Pencarian"
               autocomplete="off">

    </div>
</form>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="table-container table-responsive">

                <table id="dataTable" class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama pengguna</th>
                            <th>Nama lengkap</th>
                            <th>Peran</th>
                            <th>Email</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                       @forelse($users as $user)
    <tr>
        <td>
            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
        </td>

        <td>{{ $user->username }}</td>
        <td>{{ $user->nama_lengkap ?? '-' }}</td>
        <td>{{ ucfirst($user->nama_role ?? '-') }}</td>
        <td>{{ $user->email ?? '-' }}</td>

                                <td class="d-flex gap-2 justify-content-center">

                                    <!-- EDIT -->
                                    <a href="{{ route('kelola-akun.edit', $user->id) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <!-- DELETE -->
                                    <form action="{{ route('kelola-akun.destroy', $user->id) }}"
                                        method="POST"
                                        class="delete-form">
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
                                <td colspan="6" class="text-center">
                                Tidak ada data
                            </td>
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
                        @if ($users->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        {{-- Numbers --}}
                        @php
                            $current = $users->currentPage();
                            $last = $users->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
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
                                <a class="page-link" href="{{ $users->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($users->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}">›</a>
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
                <p>Yakin ingin menghapus data? Data tidak dapat dikembalikan.</p>
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

<!-- SCRIPT SEARCH + DELETE -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ==========================
    // SEARCH OTOMATIS
    // ==========================
    const searchInput = document.getElementById("searchInputAkun");
    const searchForm = document.getElementById("searchFormAkun");

    let searchTimer;

    if (searchInput && searchForm) {

        searchInput.addEventListener("input", function () {

            clearTimeout(searchTimer);

            searchTimer = setTimeout(function () {
                searchForm.submit();
            }, 1000);

        });
    }


    // ==========================
    // MODAL DELETE
    // ==========================
    const confirmModal = document.getElementById("confirmModal");
    const confirmBtn = document.querySelector(".btn-confirm");
    const cancelBtn = document.querySelector(".btn-cancel");
    const backdrop = document.querySelector(".confirm-modal-backdrop");

    let activeForm = null;


    document.querySelectorAll(".btn-delete").forEach(function (btn) {

        btn.addEventListener("click", function () {

            activeForm = btn.closest(".delete-form");

            if (confirmModal) {
                confirmModal.classList.add("show");
            }

        });

    });


    if (confirmBtn) {

        confirmBtn.addEventListener("click", function () {

            if (activeForm) {

                confirmBtn.disabled = true;
                confirmBtn.textContent = "Menghapus...";

                activeForm.submit();
            }

        });

    }


    function closeModal() {

        if (confirmModal) {
            confirmModal.classList.remove("show");
        }

        activeForm = null;
    }


    if (cancelBtn) {
        cancelBtn.addEventListener("click", closeModal);
    }


    if (backdrop) {
        backdrop.addEventListener("click", closeModal);
    }

});
</script>


<!-- SWEETALERT SUCCESS -->
@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({
        title: "Berhasil!",
        text: @json(session('success')),
        icon: "success",
        confirmButtonText: "OK",
        confirmButtonColor: "#198754"
    });

});
</script>
@endif

@endsection
