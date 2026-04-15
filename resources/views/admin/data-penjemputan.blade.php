@extends('layouts.app')

@section('title', 'Data Penjemputan')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/data-penjemputan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data Penjemputan</h5>
        </div>

        <div class="card mb-3 p-3">
            <form action="{{ route('data-penjemputan') }}" method="GET" class="d-flex align-items-center gap-2">

                <input type="date" name="tanggal" value="{{ $tanggal ?? '' }}" class="form-control form-control-sm" style="width:160px">

                <select name="kelas" class="form-select form-select-sm" style="width:140px">
                    <option value="">Kelas</option>
                    <option value="1-A" {{ (isset($kelas) && $kelas=='1-A')?'selected':'' }}>1-A</option>
                    <option value="1-B" {{ (isset($kelas) && $kelas=='1-B')?'selected':'' }}>1-B</option>
                    <option value="2-A" {{ (isset($kelas) && $kelas=='2-A')?'selected':'' }}>2-A</option>
                    <option value="2-B" {{ (isset($kelas) && $kelas=='2-B')?'selected':'' }}>2-B</option>
                </select>

                <!-- 🔍 TAMBAH ID -->
                <input type="text" id="searchInputPenjemputan" name="cari" value="{{ $cari ?? '' }}" class="form-control form-control-sm" placeholder="cari..." style="width:150px">

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i>
                </button>

                <a href="{{ route('data-penjemputan') }}" class="btn btn-danger btn-sm">
                    <i class="fa fa-rotate-left"></i>
                </a>

            </form>
        </div>

        <div class="card">
            @if($data->count())

            <div class="table-container">
                <!-- 🔥 TAMBAH ID -->
                <table class="table table-hover align-middle mb-0" id="dataTablePenjemputan">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Nama Wali</th>
                            <th>Tanggal</th>
                            <th>Jam Jemput</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->kelas }}</td>
                            <td>{{ $item->nama_wali }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->jam_jemput }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

                <div class="p-3 d-flex justify-content-end">
                    {{ $data->links() }}
                </div>

            @else
            <div class="card p-5 text-center text-muted">
                Tidak ada data penjemputan
            </div>
            @endif
        </div>

    </div>
</div>

<!-- 🔥 SCRIPT SEARCH REALTIME -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputPenjemputan");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTablePenjemputan tbody tr");

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
