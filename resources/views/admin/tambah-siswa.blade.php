@extends('layouts.app')

@section('title','Data siswa')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/tambah-siswa.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Data siswa</h5>
            </div>

            <div class="card p-4">

                <a href="{{ route('data-siswa') }}" class="btn btn-kembali mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">NIS Siswa</label>
                            <input type="text" class="form-control" placeholder="Masukkan NIS siswa">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <select class="form-select">
                                <option>Pilih kelas siswa</option>
                                <option>1-A</option>
                                <option>1-B</option>
                                <option>2-A</option>
                                <option>2-B</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama lengkap siswa</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama lengkap siswa">
                        <small class="text-muted">
                            *Perhatikan penulisan nama siswa, agar tidak ada kesalahan pendataan
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis kelamin</label>
                        <select class="form-select">
                            <option>Jenis kelamin siswa...</option>
                            <option>Laki-laki</option>
                            <option>Perempuan</option>
                        </select>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Tempat lahir</label>
                            <input type="text" class="form-control" placeholder="Tempat lahir siswa">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal lahir</label>
                            <input type="date" class="form-control">
                        </div>
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
