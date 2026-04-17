@extends('layouts.app')

@section('title', 'Data Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/guru/data-penjemputan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- TITLE -->
        <div class="card-box">
            <h5 class="page-title">Data penjemputan</h5>
        </div>

        <!-- SEARCH + TABLE -->
        <div class="card-box">

            <!-- SEARCH -->
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text bg-white">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" id="searchData" class="form-control" placeholder="Pencarian">
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <table class="table-custom" id="tableData">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Wali kelas</th>
                            <th>Jumlah siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $row['kelas'] }}</td>
                            <td>{{ $row['wali'] }}</td>
                            <td>{{ $row['jumlah'] }}</td>
                            <td>
                                <button class="btn-lihat">
                                    Lihat status
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data kelas</td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
</div>

<script>
document.getElementById("searchData").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableData tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? "" : "none";
    });
});
</script>

@endsection
