@extends('layouts.app')

@section('title', 'Data Wali')

@section('content')

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

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data wali</h5>
        </div>

        <!-- INFO + ACTION -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <div style="min-width:140px;">
                    Total : <strong>{{ $total }}</strong>
                </div>

                <a href="{{ route('wali.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah
                </a>

                <!-- SEARCH -->
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputWali" class="form-control" placeholder="Pencarian">
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
                            <th>Nama orangtua/wali</th>
                            <th>No. HP</th>
                            <th>Jenis Kelamin</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($wali as $row)
                        <tr>
                            <!-- 🔥 NOMOR TIDAK RESET -->
                            <td>
                                {{ ($wali->currentPage() - 1) * $wali->perPage() + $loop->iteration }}
                            </td>

                            <td>{{ $row->nama_wali }}</td>
                            <td>{{ $row->no_hp ?? '-' }}</td>
                            <td class="text-capitalize">
                                {{ $row->jenis_kelamin ?? '-' }}
                            </td>

                            <td>
                                <a href="{{ route('edit-data-wali', $row->id_wali) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <form action="{{ route('hapus-wali', ['id' => $row->id_wali]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
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

            <!-- 🔥 PAGINATION (INI YANG PENTING BANGET) -->
            <div class="p-3">
                {{ $wali->links() }}
            </div>

        </div>

    </div>
</div>

<!-- SEARCH SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputWali");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableWali tbody tr");

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