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
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <div style="min-width:140px;">
                    Total akun : <strong>{{ $total }}</strong>
                </div>

                <div style="width:180px;">
                    <label class="form-label mb-0 small text-muted">Tampilkan</label>
                    <select class="form-select form-select-sm">
                        <option>Semua</option>
                        <option>Admin</option>
                        <option>Orangtua / Wali</option>
                        <option>Kepala Sekolah</option>
                    </select>
                </div>

                <a href="{{ route('kelola-akun.create') }}" class="btn btn-primary btn-sm btn-tambah">
                    <i class="fa fa-plus"></i> Tambah
                </a>

                <div class="input-group input-group-sm search-flex ms-auto" style="min-width:260px;">
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
                            <th>Nama pengguna</th>
                            <th>Nama lengkap</th>
                            <th>Peran</th>
                            <th>Email</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->name ?? $user->nama_lengkap ?? '-' }}</td>
                            <td>{{ ucfirst($user->nama_role ?? '-') }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </button>

                                <form action="{{ route('kelola-akun.destroy', $user->id_user) }}" method="POST" style="display:inline;">
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