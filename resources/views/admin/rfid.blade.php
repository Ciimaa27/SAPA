@extends('layouts.app')

@section('title', 'RFID')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">

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
            <h5 class="mb-0">RFID dan Sidik jari</h5>
        </div>

        <!-- TAB -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('iot.index', ['tab'=>'rfid']) }}"
                   class="btn btn-tab {{ ($tab ?? 'rfid') === 'rfid' ? 'active' : '' }}">
                   RFID
                </a>

                <a href="{{ route('iot.index', ['tab'=>'sidik-jari']) }}"
                   class="btn btn-tab {{ ($tab ?? 'rfid') === 'sidik-jari' ? 'active' : '' }}">
                   Sidik jari
                </a>

                <!-- 🔍 SEARCH (DITAMBAH ID) -->
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputRFID" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="d-flex justify-content-end p-3">
                <a href="{{ route('iot.store', ['tab'=>'rfid']) }}" class="btn btn-primary btn-sm btn-tambah">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </div>

            <div class="table-container">
                <!-- 🔥 TAMBAH ID -->
                <table class="table table-hover align-middle mb-0" id="dataTableRFID">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>UID</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->rfid_uid ?? '-' }}</td>
                            <td>
                                <form action="{{ route('iot.destroy', ['tab'=>'rfid','id'=>$item->id_siswa]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if(count($data) === 0)
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data RFID</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

<!-- 🔥 SCRIPT SEARCH -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputRFID");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableRFID tbody tr");

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
