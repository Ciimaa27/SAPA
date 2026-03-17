@extends('layouts.app')

@section('title', 'Data Wali')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/data-wali.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Data wali</h5>
            </div>

            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="min-width:140px;">
                        <strong>{{ $total }}</strong>
                    </div>

                    <div style="width:200px;">
                        <select class="form-select form-select-sm">
                            <option>Tampilkan</option>
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>

                    <a href="#" class="btn btn-primary btn-sm btn-tambah">
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

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama orangtua/wali</th>
                                <th>No. HP</th>
                                <th>Jenis Kelamin</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($wali as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nama_wali }}</td>
                                <td>{{ $row->no_hp }}</td>
                                <td>-</td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <form action="{{ route('hapus-wali', ['id' => $row->id_wali]) }}" method="POST" style="display:inline;">
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
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end p-3">
                    {{ $wali->links() }}
                </div>
            </div>

        </div>
    </div>

@endsection
