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
                <a href="{{ route('guru') }}" class="btn btn-tab">
                    Guru
                </a>

                <a href="{{ route('kelas') }}" class="btn btn-tab active">
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
                    <i class="fa fa-plus"></i> Tambah kelas
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Wali kelas</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kelas as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama_kelas }}</td>
                            <td>{{ $row->nama_guru ?? '-' }}</td>
                            <td>
                                <a href="{{ route('siswa-kelas') }}" class="btn btn-success btn-sm">
                                    Lihat siswa
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination Laravel --}}
            <div class="p-3">
                {{ $kelas->links() }}
            </div>

        </div>

    </div>
</div>

@endsection