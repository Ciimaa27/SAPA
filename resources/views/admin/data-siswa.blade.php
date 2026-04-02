@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/data-siswa.css') }}">

<style>
    /* Scroll hanya di tabel */
    .table-container {
        max-height: 400px;
        overflow-y: auto;
    }

    /* Header tetap di atas */
    .table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }

    .col-aksi {
        width: 150px;
    }
</style>

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data siswa</h5>
        </div>

        <!-- FILTER & ACTION -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <div style="min-width:140px;">
                    Total Siswa : <strong>{{ $total }}</strong>
                </div>

                <div style="width:200px;">
                    <select class="form-select form-select-sm">
                        <option>Tampilkan</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>

                <a href="{{ route('tambah-siswa') }}" class="btn btn-primary btn-sm btn-tambah">
                    <i class="fa fa-plus"></i> Tambah
                </a>

                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>Nama lengkap</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat/Tanggal lahir</th>
                            <th >Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($siswa as $row)
                        <tr>
                            <td>{{ $row->nis }}</td>
                            <td>{{ $row->nama_siswa }}</td>
                            <td>{{ $row->id_kelas }}</td>

                            <td class="text-capitalize">
                                {{ $row->jenis_kelamin ?? '-' }}
                            </td>

                            <td>
                                {{ $row->tempat_lahir ?? '-' }},
                                {{ $row->tanggal_lahir
                                    ? \Carbon\Carbon::parse($row->tanggal_lahir)->format('d-m-Y')
                                    : '-'
                                }}
                            </td>

                            <td>
                                <button class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                <form action="{{ route('hapus-siswa', ['id' => $row->id_siswa]) }}" method="POST" style="display:inline;">
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
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
