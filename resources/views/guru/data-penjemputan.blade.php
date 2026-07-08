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
            <div class="card mb-3 p-3">
                <h5 class="mb-0">Data Penjemputan</h5>
            </div>

            <!-- SEARCH -->
            <div class="card mb-3 p-3">
                <form id="searchFormPenjemputan" method="GET" action="{{ route('guru.data-penjemputan') }}">
                    <div class="input-group input-group-sm search-flex">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" id="searchInputPenjemputan" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Pencarian" autocomplete="off">
                    </div>
                </form>
            </div>

            <!-- TABLE -->
            <div class="card">
                <div class="table-container">
                    <table class="table table-hover align-middle mb-0" id="dataTablePenjemputan">
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
                            @forelse($data as $row)
                                <tr>
                                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                    <td>{{ $row['kelas'] }}</td>
                                    <td>{{ $row['wali'] }}</td>
                                    <td>{{ $row['jumlah'] }}</td>
                                    <td>
                                        <a href="{{ route('guru.penjemputan', $row['id_kelas']) }}" class="btn-detail" title="Lihat status">
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

                <!-- PAGINATION -->
                <div class="pagination-wrapper">
                    {{-- PREVIOUS --}}
                    @if ($data->onFirstPage())
                        <span class="page-item-custom disabled">‹</span>
                    @else
                        <a href="{{ $data->previousPageUrl() }}" class="page-item-custom">‹</a>
                    @endif

                    {{-- NOMOR HALAMAN --}}
                    @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                        @if ($page == $data->currentPage())
                            <span class="page-item-custom active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-item-custom">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- NEXT --}}
                    @if ($data->hasMorePages())
                        <a href="{{ $data->nextPageUrl() }}" class="page-item-custom">›</a>
                    @else
                        <span class="page-item-custom disabled">›</span>
                    @endif
                </div>
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
