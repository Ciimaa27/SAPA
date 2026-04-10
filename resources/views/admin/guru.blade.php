@extends('layouts.app')

@section('title', 'Guru dan Kelas')

@section('content')

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

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Guru dan Kelas</h5>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('guru') }}" class="btn btn-tab active">
                    Guru
                </a>

                <a href="{{ route('kelas') }}" class="btn btn-tab">
                    Kelas
                </a>

                <!-- 🔍 SEARCH (DITAMBAH ID) -->
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputGuru" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <div class="card">
            <div class="d-flex justify-content-end p-3">

                <a href="{{ route('tambah-data-guru') }}" class="btn-tambah-guru">
                    Tambah
                    <span class="icon-plus">+</span>
                </a>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <!-- 🔥 TAMBAH ID DI TABEL -->
                <table class="table table-hover align-middle mb-0" id="dataTableGuru">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama guru</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($guru as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama_guru }}</td>
                            <td>
                                <a href="{{ route('edit-data-guru') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>
</div>

<!-- 🔥 SCRIPT SEARCH -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputGuru");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableGuru tbody tr");

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
