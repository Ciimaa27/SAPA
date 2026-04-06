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

            <form>

                <!-- ROW 1 -->
                <div class="form-row">
                    <div class="form-group">
                <label>Nama Lengkap</label>
            <input type="text">
            </div>

                    <div class="form-group">
                <label>Nama Pengguna</label>
                <input type="text">
            </div>
            </div>

                <!-- PERAN -->
            <div class="form-group full">
            <label>Peran</label>
                <select>
                <option>Admin</option>
                <option>Guru</option>
                <option>Orangtua / Wali</option>
                </select>
                <small class="form-text">
                *Pilih peran sesuai kebutuhan user, pastikan dengan benar
            </small>
            </div>

                <!-- EMAIL -->
                <div class="form-group full">
                    <label>Email</label>
                    <input type="email" value="admin01@sekolah.id">
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
