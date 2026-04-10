@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/edit-data-siswa.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Data siswa
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- BUTTON KEMBALI -->
            <a href="/admin/data-siswa" class="btn-kembali">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>

            <form action="{{ route('update-siswa', $siswa->id_siswa) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ROW 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>NIS Siswa</label>
                        <input type="text" name="nis" value="{{ $siswa->nis }}">
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="id_kelas">
                            <option value="1" {{ $siswa->id_kelas == 1 ? 'selected' : '' }}>Kelas 1</option>
                            <option value="2" {{ $siswa->id_kelas == 2 ? 'selected' : '' }}>Kelas 2</option>
                            <option value="3" {{ $siswa->id_kelas == 3 ? 'selected' : '' }}>Kelas 3</option>
                            <option value="4" {{ $siswa->id_kelas == 4 ? 'selected' : '' }}>Kelas 4</option>
                            <option value="5" {{ $siswa->id_kelas == 5 ? 'selected' : '' }}>Kelas 5</option>
                        </select>
                    </div>
                </div>

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama lengkap siswa</label>
                    <input type="text" name="nama_siswa" value="{{ $siswa->nama_siswa }}">
                    <small class="form-text">
                        *Pastikan penulisan nama siswa, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="Laki-laki" {{ $siswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $siswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- ROW 2 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ $siswa->tempat_lahir }}">
                    </div>

                    <div class="form-group">
                        <label>Tanggal lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ $siswa->tanggal_lahir }}">
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="form-action">
                    <a href="/admin/data-siswa" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection