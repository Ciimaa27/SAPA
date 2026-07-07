@extends('layouts.app')

@section('title', 'Guru dan Kelas')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/guru.css') }}">

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
            <h5 class="mb-0">Guru dan Kelas</h5>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3 toolbar-kelas">

                <a href="{{ route('guru') }}" class="btn btn-tab">
                    Guru
                </a>

                <a href="{{ route('kelas') }}" class="btn btn-tab active">
                    Kelas
                </a>
                <a href="{{ route('tambah-data-kelas') }}" class="btn-tambah-kelas">
                    Tambah
                    <span class="icon-plus">+</span>
                </a>

                <!-- 🔍 SEARCH (DITAMBAH ID) -->
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" name="search" id="searchInputKelas" class="form-control" placeholder="Pencarian"value="{{ request('search') }}"autocomplete="off">
                </div>

            </div>
        </div>

        <div class="card">


            <!-- TABLE -->
            <div class="table-container table-responsive">
                <table class="table table-hover align-middle mb-0" id="dataTableKelas">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Wali kelas</th>
                            <th>Jumlah siswa</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kelas as $row)
                        <tr>
                            <td>{{ ($kelas->currentPage() - 1) * $kelas->perPage() + $loop->iteration }}</td>
                            <td>{{ $row->nama_kelas }}</td>
                            <td>{{ $row->nama_guru ?? '-' }}</td>
                            <td>{{ $row->jumlah_siswa }}</td>
                            <td>
                                <a href="{{ route('siswa-kelas', $row->id_kelas) }}" class="btn btn-success btn-sm btn-action" title="Lihat siswa">
                                    Lihat siswa
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

            <div class="p-3 d-flex justify-content-end">
                {{ $kelas->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>
</div>

<!-- 🔥 SCRIPT SEARCH -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("searchInputKelas");

    let searchTimer;

    input.addEventListener("input", function () {

        clearTimeout(searchTimer);

        const keyword = this.value;

        searchTimer = setTimeout(async function () {

            try {

                const url = new URL(
                    "{{ route('kelas') }}",
                    window.location.origin
                );

                // Jika ada pencarian
                if (keyword.trim() !== "") {
                    url.searchParams.set("search", keyword);
                }

                const response = await fetch(url.toString());

                const html = await response.text();

                const parser = new DOMParser();

                const doc = parser.parseFromString(
                    html,
                    "text/html"
                );


                // =========================
                // UPDATE ISI TABEL
                // =========================

                const newBody =
                    doc.querySelector("#dataTableKelas tbody");

                const currentBody =
                    document.querySelector("#dataTableKelas tbody");

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

                } else if (!newPagination && currentPagination) {

                    currentPagination.innerHTML = "";

                }

            } catch (error) {

                console.error(
                    "Pencarian kelas gagal:",
                    error
                );

            }

        }, 500);

    });

});
</script>

@endsection
