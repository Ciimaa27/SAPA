@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/kelola-akun.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kelola akun pengguna</h5>
            </div>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <div style="min-width:140px;">
                    Total akun : <strong>{{ $total }}</strong>
                </div>

                <div style="width:200px;">
                    <select class="form-select form-select-sm">
                        <option>Semua</option>
                        <option>Admin</option>
                        <option>Orangtua / Wali</option>
                        <option>Kepala Sekolah</option>
                    </select>
                </div>

                <a href="{{ route('tambah-akun') }}" class="btn btn-primary btn-sm btn-tambah">
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
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucfirst($user->nama_role ?? '-') }}</td>
                            <td>{{ ucfirst($user->status) }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                <form action="{{ route('hapus-akun', $user->id_user) }}" method="POST" style="display:inline;">
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
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>

@endsection