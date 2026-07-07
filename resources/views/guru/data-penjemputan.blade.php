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
<form id="searchFormPenjemputan"
      method="GET"
      action="{{ route('guru.data-penjemputan') }}">

    <div class="input-group input-group-sm mb-3">
        <span class="input-group-text bg-white">
            <i class="fa fa-search"></i>
        </span>

        <input type="text"
               id="searchInputPenjemputan"
               name="cari"
               value="{{ request('cari') }}"
               class="form-control"
               placeholder="Pencarian"
               autocomplete="off">
    </div>

</form>

            <!-- TABLE -->
            <div class="table-container">
                <table class="table-custom" id="tableData">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Wali kelas</th>
                            <th>Jumlah siswa</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $row)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td>{{ $row['kelas'] }}</td>
                            <td>{{ $row['wali'] }}</td>
                            <td>{{ $row['jumlah'] }}</td>
<td>
    <a href="{{ route('guru.penjemputan', $row['id_kelas']) }}"
       class="btn btn-success btn-sm btn-aksi"
       title="Lihat status">
        <i class="fa fa-eye"></i>
    </a>
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

            <div class="pagination-wrapper">
    <nav>
        <ul class="pagination mb-0">

            {{-- PREVIOUS --}}
            @if ($data->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link"
                       href="{{ $data->previousPageUrl() }}">
                        ‹
                    </a>
                </li>
            @endif


            {{-- NOMOR HALAMAN --}}
            @php
                $current = $data->currentPage();
                $last = $data->lastPage();
            @endphp

            @for ($i = 1; $i <= $last; $i++)
                <li class="page-item {{ $i == $current ? 'active' : '' }}">
                    <a class="page-link"
                       href="{{ $data->url($i) }}">
                        {{ $i }}
                    </a>
                </li>
            @endfor


            {{-- NEXT --}}
            @if ($data->hasMorePages())
                <li class="page-item">
                    <a class="page-link"
                       href="{{ $data->nextPageUrl() }}">
                        ›
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">›</span>
                </li>
            @endif

        </ul>
    </nav>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput =
        document.getElementById("searchInputPenjemputan");

    const searchForm =
        document.getElementById("searchFormPenjemputan");

    let typingTimer;

    searchInput.addEventListener("input", function () {

        clearTimeout(typingTimer);

        typingTimer = setTimeout(function () {
            searchForm.submit();
        }, 700);

    });

});
</script>

@endsection
