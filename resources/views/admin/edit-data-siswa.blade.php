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

            <form action="#" method="POST">
                @csrf

                <!-- ROW 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>NIS Siswa</label>
                        <input type="text" name="nis" value="00987643">
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="kelas">
                            <option value="">-- Pilih Kelas --</option>
                            <option value="1-A">1-A</option>
                            <option value="1-B" selected>1-B</option>
                            <option value="1-C">1-C</option>
                            <option value="2-A">2-A</option>
                            <option value="2-B">2-B</option>
                        </select>
                    </div>
                </div>

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama lengkap siswa</label>
                    <input type="text" name="nama" value="Arif Nasution">
                    <small class="form-text">
                        *Pastikan penulisan nama siswa, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jk">
                        <option value="laki-laki" selected>Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>

                <!-- ROW 2 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat lahir</label>
                        <input type="text" name="tempat_lahir" value="Banjarmasin">
                    </div>

                    <div class="form-group">
                        <label>Tanggal lahir</label>
                        <input type="date" name="tanggal_lahir" value="2010-02-15">
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