@extends('layouts.app')

@section('title','Tambah Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/tambah-siswa.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Tambah siswa</h5>
        </div>

        <div class="card p-4">

            <a href="{{ route('data-siswa') }}" class="btn btn-kembali mb-3">
                ← Kembali
            </a>

            {{-- ERROR --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('store-siswa') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>NIS Siswa</label>
                        <input type="text" name="nis" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Kelas</label>
                        <select name="id_kelas" class="form-select">
                            <option value="">Pilih kelas</option>
                            <option value="1">1-A</option>
                            <option value="2">1-B</option>
                            <option value="3">2-A</option>
                            <option value="4">2-B</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Nama lengkap siswa</label>
                    <input type="text" name="nama_siswa" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Jenis kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">Pilih</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label>Tempat lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tanggal lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
