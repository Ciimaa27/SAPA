@extends('layouts.app')

@section('title', 'Relasi siswa dan wali')

@section('content')

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

@include('layouts.sidebar-admin')
@include('layouts.topbar')

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
