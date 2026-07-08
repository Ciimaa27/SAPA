@extends('layouts.app')

@section('title', 'Data Siswa')

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
<link rel="stylesheet" href="{{ asset('css/admin/data-siswa.css') }}">

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
    .col-aksi {
        width: 150px;
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
            <h5 class="mb-0">Data siswa</h5>
        </div>

        {{-- ===========================
            FILTER DAN ACTION
        =========================== --}}
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                {{-- TOTAL SISWA --}}
                <div class="total-data">
                    Total Siswa : <strong>{{ $total }}</strong>
                </div>

                {{-- ===========================
                    FILTER KELAS
                =========================== --}}
                <form method="GET" action="{{ route('data-siswa') }}" id="filterForm">
                    <select name="kelas" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelas as $itemKelas)
                            <option value="{{ $itemKelas->id_kelas }}" {{ request('kelas') == $itemKelas->id_kelas ? 'selected' : '' }}>
                                {{ $itemKelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    {{-- PERTAHANKAN PENCARIAN --}}
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>

                {{-- ===========================
                    BUTTON TAMBAH
                =========================== --}}
                <a href="{{ route('tambah-siswa') }}" class="btn-tambah">
                    Tambah <i class="fa fa-plus"></i>
                </a>

                {{-- ===========================
                    BUTTON KENAIKAN KELAS
                =========================== --}}
                <button type="button" class="btn-kenaikan" data-bs-toggle="modal" data-bs-target="#modalKenaikan">
                    Kenaikan Kelas <i class="fa fa-arrow-up"></i>
                </button>

                {{-- ===========================
                    SEARCH
                =========================== --}}
                <form id="searchFormSiswa" method="GET" action="{{ route('data-siswa') }}" style="flex: 1; max-width: 500px;">
                    {{-- PERTAHANKAN FILTER KELAS --}}
                    @if (request('kelas'))
                        <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                    @endif

                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" id="searchInputSiswa" name="search" value="{{ request('search') }}" class="form-control" placeholder="Pencarian" autocomplete="off">
                    </div>
                </form>

            </div>
        </div>

        {{-- ===========================
            TABLE
        =========================== --}}
        <div class="card">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>UID</th>
                            <th>Nama lengkap</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat/Tanggal lahir</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswa as $item)
                            <tr>
                                {{-- NOMOR --}}
                                <td>
                                    {{ ($siswa->currentPage() - 1) * $siswa->perPage() + $loop->iteration }}
                                </td>

                                {{-- NIS --}}
                                <td>{{ $item->nis }}</td>

                                {{-- UID --}}
                                <td>{{ $item->rfid_uid ?? '-' }}</td>

                                {{-- NAMA --}}
                                <td>{{ $item->nama_siswa }}</td>

                                {{-- KELAS --}}
                                <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>

                                {{-- JENIS KELAMIN --}}
                                <td>{{ $item->jenis_kelamin }}</td>

                                {{-- TEMPAT TANGGAL LAHIR --}}
                                <td>{{ $item->tempat_lahir }}, {{ $item->tanggal_lahir }}</td>

                                {{-- AKSI --}}
                                <td>
                                    {{-- LIHAT --}}
                                    <a href="{{ route('data-siswa.show', $item->id_siswa) }}" class="btn btn-info btn-sm" title="Lihat">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('edit-siswa', $item->id_siswa) }}" class="btn btn-warning btn-sm mx-1" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    {{-- ARSIP --}}
                                    <button type="button" class="btn btn-secondary btn-sm btn-arsip" data-id="{{ $item->id_siswa }}" data-nama="{{ $item->nama_siswa }}" data-bs-toggle="modal" data-bs-target="#modalArsip" title="Arsipkan">
                                        <i class="fa fa-box-archive"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===========================
                PAGINATION
            =========================== --}}
            @if ($siswa->lastPage() > 1)
                <div class="p-3 d-flex justify-content-end">
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- PREVIOUS --}}
                            @if ($siswa->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">‹</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $siswa->previousPageUrl() }}">‹</a></li>
                            @endif

                            @php
                                $current = $siswa->currentPage();
                                $last = $siswa->lastPage();
                            @endphp

                            {{-- FIRST PAGE --}}
                            @if ($current > 3)
                                <li class="page-item"><a class="page-link" href="{{ $siswa->url(1) }}">1</a></li>
                                @if ($current > 4)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- MIDDLE PAGES --}}
                            @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $siswa->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- LAST PAGE --}}
                            @if ($current < $last - 2)
                                @if ($current < $last - 3)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><a class="page-link" href="{{ $siswa->url($last) }}">{{ $last }}</a></li>
                            @endif

                            {{-- NEXT --}}
                            @if ($siswa->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $siswa->nextPageUrl() }}">›</a></li>
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

{{-- ===========================
    MODAL KENAIKAN KELAS
=========================== --}}
<div class="modal fade" id="modalKenaikan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('kenaikan-kelas') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Proses Kenaikan Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Sistem akan melakukan:</p>
                    <ul class="mb-3">
                        <li>Kelas 1 → Kelas 2</li>
                        <li>Kelas 2 → Kelas 3</li>
                        <li>Kelas 3 → Kelas 4</li>
                        <li>Kelas 4 → Kelas 5</li>
                        <li>Kelas 5 → Kelas 6</li>
                        <li>Kelas 6 → Diarsipkan (Lulus)</li>
                    </ul>
                    <p class="mb-1">Pastikan seluruh data siswa sudah benar!</p>
                    <p class="text-danger fw-semibold mb-0">⚠️ Proses ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===========================
    MODAL ARSIP
=========================== --}}
<div class="modal fade" id="modalArsip" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formArsip" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Arsipkan Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Nama siswa: <strong id="namaSiswa"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Status Arsip</label>
                        <select name="status" class="form-select" required>
                            <option value="lulus">Lulus</option>
                            <option value="pindah">Pindah</option>
                            <option value="mengundurkan_diri">Mengundurkan Diri</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Arsipkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===========================
    SWEETALERT
=========================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: @json(session('success')),
        confirmButtonText: 'OK',
        confirmButtonColor: '#198754',
        allowOutsideClick: false
    });
});
</script>
@endif

@if (session('error'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: @json(session('error')),
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
    });
});
</script>
@endif

{{-- ===========================
    JAVASCRIPT MODAL ARSIP
=========================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const formArsip = document.getElementById('formArsip');
    const namaSiswa = document.getElementById('namaSiswa');

    document.querySelectorAll('.btn-arsip').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;

            namaSiswa.textContent = nama;
            formArsip.action = "{{ url('/admin/arsip-siswa') }}/" + id;
        });
    });
});
</script>

{{-- ===========================
    SEARCH OTOMATIS
=========================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInputSiswa");
    const searchForm = document.getElementById("searchFormSiswa");
    let typingTimer;

    if (searchInput && searchForm) {
        searchInput.addEventListener("input", function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {
                searchForm.submit();
            }, 700);
        });
    }
});
</script>
@endsection
