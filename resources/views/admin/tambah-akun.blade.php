@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/tambah-akun.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-fluid">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Kelola akun pengguna</h5>
            </div>

            <div class="card p-4">

                <a href="{{ route('kelola-akun') }}" class="btn btn-kembali mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" placeholder="Masukkan nama lengkap...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control" placeholder="Buatlah nama pengguna...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select class="form-select">
                            <option>Pilih peran</option>
                            <option>Admin</option>
                            <option>Orangtua / Wali</option>
                            <option>Kepala Sekolah</option>
                        </select>
                        <small class="text-muted">
                            *Pilih peran sesuai kebutuhan user, pastikan dengan benar
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Masukkan email ...">
                        <small class="text-muted">
                            *Pastikan memasukkan nama email dengan benar
                        </small>
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
