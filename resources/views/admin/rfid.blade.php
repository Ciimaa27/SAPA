@extends('layouts.app')

@section('title','RFID & Sidik Jari')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">

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

        <!-- TITLE -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">RFID dan Sidik jari</h5>
        </div>

        <!-- TAB -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('iot.index',['tab'=>'rfid']) }}"
                   class="btn btn-tab {{ ($tab ?? 'rfid')=='rfid' ? 'active' : '' }}">
                   RFID
                </a>

                <a href="{{ route('iot.index',['tab'=>'sidik-jari']) }}"
                   class="btn btn-tab {{ ($tab ?? '')=='sidik-jari' ? 'active' : '' }}">
                   Sidik jari
                </a>

                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">

            <div class="d-flex justify-content-end p-3">
                <a href="{{ route('tambah-data-rfid') }}" class="btn-tambah-rfid">
                    Tambah
                    <span class="icon-plus">+</span>
                </a>
            </div>
            </div>

            <div class="table-container">

                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>

                            @if($tab == 'rfid')
                                <th>Nama Siswa</th>
                                <th>UID</th>
                            @else
                                <th>Nama Wali</th>
                                <th>ID Fingerprint</th>
                            @endif

                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            @if($tab == 'rfid')
                                <td>{{ $item->nama_siswa }}</td>
                                <td>{{ $item->rfid_uid ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('iot.destroy',['tab'=>'rfid','id'=>$item->id_siswa]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            @else
                                <td>{{ $item->nama_wali }}</td>
                                <td>{{ $item->fingerprint_id ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('iot.destroy',['tab'=>'sidik-jari','id'=>$item->id_wali]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            @endif

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<!-- SEARCH -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
    });
});
</script>

@endsection