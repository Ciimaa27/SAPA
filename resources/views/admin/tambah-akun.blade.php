@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-akun.css') }}">
@endpush

@section('content')

    <div class="main-dashboard">
        <div class="container-fluid">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Kelola akun pengguna</h5>
            </div>

            <div class="card p-4">

               <a href="{{ route('kelola-akun.index') }}" class="btn btn-kembali mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <form action="{{ route('kelola-akun.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <h5>Terjadi Kesalahan!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap..." required>
                            @error('nama_lengkap')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Pengguna</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Buatlah nama pengguna..." required>
                            @error('username')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select name="peran" class="form-select @error('peran') is-invalid @enderror" required>
                            <option value="">Pilih peran</option>
                            <option value="Admin" {{ old('peran') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Guru" {{ old('peran') == 'Guru' ? 'selected' : '' }}>Guru</option>
                            <option value="Orangtua/Wali" {{ old('peran') == 'Orangtua/Wali' ? 'selected' : '' }}>Orangtua / Wali</option>
                            <option value="Kepala Sekolah" {{ old('peran') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        </select>
                        <small class="text-muted d-block mt-1">
                            *Pilih peran sesuai kebutuhan user, pastikan dengan benar
                        </small>
                        @error('peran')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masukkan email ..." required>
                        <small class="text-muted d-block mt-1">
                            *Pastikan memasukkan nama email dengan benar
                        </small>
                        @error('email')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-warning btn-sm">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-success btn-sm">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

@endsection
