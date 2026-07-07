@extends('layouts.app')

@section('title', 'Data Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/data-penjemputan.css') }}">
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">
        {{-- Judul --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data Penjemputan</h5>
        </div>

        {{-- Card Table --}}
        <div class="card p-4">
                    {{-- Search --}}
<form id="searchFormPenjemputan"
      method="GET"
      action="{{ route('data-penjemputan') }}">

    <div class="input-group mb-3">
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

            <div class="table-container">
                <table class="table align-middle mb-0" id="kelasTable">
                    <thead>
                        <tr>
                            <th width="70" class="text-center">No</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-start">Wali kelas</th>
                            <th class="text-center">Jumlah siswa</th>
                            <th width="170" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $item)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-center">
                                    {{ $item->nama_kelas }}
                                </td>
                                <td class="text-start ps-4">
                                    {{ $item->nama_guru }}
                                </td>
                                <td class="text-center">
                                    {{ $item->jumlah_siswa }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('data-penjemputan.status', $item->id_kelas) }}" class="btn-status">
                                        Lihat Status
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    Tidak ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

           {{-- Pagination --}}
@if ($kelas->hasPages())
    <div class="pagination-wrapper">

        {{-- Previous --}}
        @if ($kelas->onFirstPage())
            <span class="page-item-custom disabled">
                ‹
            </span>
        @else
            <a href="{{ $kelas->previousPageUrl() }}"
               class="page-item-custom">
                ‹
            </a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($kelas->getUrlRange(1, $kelas->lastPage()) as $page => $url)
            @if ($page == $kelas->currentPage())
                <span class="page-item-custom active">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}"
                   class="page-item-custom">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($kelas->hasMorePages())
            <a href="{{ $kelas->nextPageUrl() }}"
               class="page-item-custom">
                ›
            </a>
        @else
            <span class="page-item-custom disabled">
                ›
            </span>
        @endif

    </div>
@endif

        </div>
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
