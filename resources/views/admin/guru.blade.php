@extends('layouts.app')

@section('title', 'Guru dan Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/guru.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

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

                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Pencarian">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="d-flex justify-content-end p-3">
                <a href="#" class="btn btn-primary btn-sm btn-tambah">
                    <i class="fa fa-plus"></i> Tambah guru
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama guru</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($guru as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama_guru }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination Laravel --}}
            <div class="p-3">
                {{ $guru->links() }}
            </div>

        </div>

    </div>
</div>

@endsection