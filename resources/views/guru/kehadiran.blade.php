@extends('layouts.app')

@section('title', 'Kehadiran Kelas')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/guru.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- TITLE -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Daftar Kehadiran Kelas</h5>
        </div>

        <!-- SEARCH -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputKehadiran" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">

            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTableKehadiran">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Wali kelas</th>
                            <th>Jumlah siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        {{-- 🔥 DATA DUMMY (SUDAH ADA ID) --}}
                        @php
                            $data = [
                                ['id_kelas'=>1,'kelas'=>'1-A','wali'=>'Arif Nasution','jumlah'=>25],
                                ['id_kelas'=>2,'kelas'=>'1-B','wali'=>'Radita Nabila','jumlah'=>21],
                                ['id_kelas'=>3,'kelas'=>'1-C','wali'=>'Arif Rahman','jumlah'=>21],
                                ['id_kelas'=>4,'kelas'=>'1-D','wali'=>'Ismatul Hawa','jumlah'=>22],
                            ];
                        @endphp

                        @foreach($data as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $row['kelas'] }}</td>
                            <td>{{ $row['wali'] }}</td>
                            <td>{{ $row['jumlah'] }}</td>
                            <td>
                                <a href="{{ route('guru.detail-kehadiran') }}" class="btn btn-success btn-sm">
                                    Lihat siswa
                                </a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>

    </div>
</div>

{{-- SEARCH SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputKehadiran");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableKehadiran tbody tr");

        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
});
</script>

@endsection
