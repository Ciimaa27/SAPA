@extends('layouts.app')

@section('title', 'Guru dan Kelas')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/guru.css') }}">

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
@endpush

{{-- 🔥 CONTENT --}}
@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- 🔥 ALERT SUCCESS -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Guru dan Kelas</h5>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('guru') }}" class="btn btn-tab active">
                    Guru
                </a>

                <a href="{{ route('kelas') }}" class="btn btn-tab">
                    Kelas
                </a>

                <!-- SEARCH -->
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputGuru" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <div class="card">
            <div class="d-flex justify-content-end p-3">

                <a href="{{ route('tambah-data-guru') }}" class="btn-tambah-guru">
                    Tambah
                    <span class="icon-plus">+</span>
                </a>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTableGuru">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama guru</th>
                            <th>No. HP</th>
                            <th>Tempat/Tanggal lahir</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($guru as $row)
                        <tr>
                            <td>
                                {{ ($guru->currentPage() - 1) * $guru->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $row->nip ?? '-' }}</td>
                            <td>{{ $row->nama_guru }}</td>
                            <td>{{ $row->no_hp ?? '-' }}</td>
                            <td>
                                {{ $row->tempat_lahir ?? '-' }},
                                {{ $row->tanggal_lahir
                                    ? \Carbon\Carbon::parse($row->tanggal_lahir)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td class="text-center">
                                <!-- DETAIL -->
                                <a href="{{ route('detail-guru', $row->id_guru) }}"
                                   class="btn btn-info btn-sm" title="Lihat">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <!-- EDIT -->
                                <a href="{{ route('edit-data-guru', $row->id_guru) }}"
                                   class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <!-- DELETE -->
                                <form action="{{ route('hapus-guru', $row->id_guru) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data guru ini?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-3 d-flex justify-content-end">
                @if ($guru->hasPages())
                    {{ $guru->links() }}
                @else
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <li class="page-item active"><span class="page-link">1</span></li>
                        </ul>
                    </nav>
                @endif
            </div>

        </div>

    </div>
</div>

<!-- 🔥 SCRIPT SEARCH -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputGuru");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableGuru tbody tr");

        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
                row.style.backgroundColor = "#fff3cd"; // highlight
            } else {
                row.style.display = "none";
            }
        });
    });
});
</script>

@endsection
