@extends('layouts.app')
@section('title', 'Edit Kelola Akun')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/edit-kelola-akun.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Kelola akun pengguna
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- BUTTON KEMBALI -->
            <a href="/admin/kelola-akun" class="btn-kembali">
            <i class="fas fa-arrow-left"></i>
                Kembali
                </a>

            <form action="{{ route('kelola-akun.update', $user->id_user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ROW 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label>Nama Pengguna</label>
                        <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}">
                    </div>
                </div>

                <!-- PERAN -->
                <div class="form-group full">
                    <label>Peran</label>
                    <select name="peran">
                        <option value="Admin" {{ old('peran', $user->role->nama_role ?? '') === 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Guru" {{ old('peran', $user->role->nama_role ?? '') === 'Guru' ? 'selected' : '' }}>Guru</option>
                        <option value="Orangtua/Wali" {{ old('peran', $user->role->nama_role ?? '') === 'Orangtua/Wali' ? 'selected' : '' }}>Orangtua / Wali</option>
                        <option value="Kepala Sekolah" {{ old('peran', $user->role->nama_role ?? '') === 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    </select>
                    <small class="form-text">
                        *Pilih peran sesuai kebutuhan user, pastikan dengan benar
                    </small>
                </div>

                <!-- EMAIL -->
                <div class="form-group full">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}">
                    <small class="form-text">
                        *Pastikan memasukkan nama email dengan benar
                    </small>
                </div>

                <!-- BUTTON -->
                <div class="form-action">
                    <a href="/admin/kelola-akun" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
