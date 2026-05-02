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
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Kelola akun pengguna</h5>
        </div>

        <!-- TOOLBAR -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <div>
                    Total akun : <strong>{{ $total }}</strong>
                </div>

                <!-- FILTER -->
                <div style="width:180px;">
                    <select class="form-select form-select-sm">
                        <option>Tampilan</option>
                        <option>Semua</option>
                        <option>Admin</option>
                        <option>Orangtua / Wali</option>
                        <option>Kepala Sekolah</option>
                    </select>
                </div>

                <!-- TAMBAH -->
                <a href="{{ route('kelola-akun.create') }}"
                   class="btn btn-sm"
                   style="border: 1px solid #dee2e6;">
                    <i class="fa fa-plus"></i> Tambah
                </a>

                <!-- SEARCH -->
                <form method="GET"
                      action="{{ route('kelola-akun.index') }}"
                      class="ms-auto"
                      style="max-width:400px; width:100%;">

                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border">
                            <i class="fa fa-search"></i>
                        </span>

                        <input type="text"
                               id="searchInput"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Pencarian">
                    </div>
                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="table-container">

                <table id="dataTable" class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Nama pengguna</th>
                            <th>Nama lengkap</th>
                            <th>Peran</th>
                            <th>Email</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->nama_lengkap ?? '-' }}</td>
                                <td>{{ ucfirst($user->nama_role ?? '-') }}</td>
                                <td>{{ $user->email ?? '-' }}</td>

                                <td class="d-flex gap-2">

                                    <!-- EDIT -->
                                    <a href="{{ route('kelola-akun.edit', $user->id) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <!-- DELETE -->
                                    <form action="{{ route('kelola-akun.destroy', $user->id) }}"
                                          method="POST"
                                          class="delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
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
                <button class="btn btn-secondary btn-sm btn-cancel">Batal</button>
                <button class="btn btn-danger btn-sm btn-confirm">Hapus</button>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    const confirmModal = document.getElementById('confirmModal');
    const confirmBtn   = document.querySelector('.btn-confirm');
    const cancelBtn    = document.querySelector('.btn-cancel');

    let activeForm = null;

    // DELETE
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', () => {
            activeForm = btn.closest('.delete-form');
            confirmModal.classList.add('show');
        });
    });

    confirmBtn.addEventListener('click', () => {
        if (activeForm) activeForm.submit();
    });

    cancelBtn.addEventListener('click', () => {
        confirmModal.classList.remove('show');
    });

    // SEARCH
    const input = document.getElementById("searchInput");

    if (input) {
        input.addEventListener("keyup", () => {
            const keyword = input.value.toLowerCase();
            const rows = document.querySelectorAll("#dataTable tbody tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        });
    }

});
</script>

@endsection
