@extends('layouts.app')

@section('title', 'Tambah Data Guru')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/tambah-data-guru.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Tambah data guru
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- BUTTON KEMBALI -->
            <a href="/admin/guru" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <form action="#" method="POST">
                @csrf

                <!-- ROW 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip" placeholder="Masukkan NIP guru">
                        <small class="form-text">*Pastikan memasukkan NIP yang benar</small>
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="no_hp" placeholder="Masukkan nomor HP">
                    </div>
                </div>

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama lengkap guru</label>
                    <input type="text" name="nama" placeholder="Masukkan nama lengkap guru...">
                    <small class="form-text">
                        *Perhatikan penulisan nama, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jk">
                        <option value="">-- Pilih jenis kelamin --</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>

                <!-- ROW 2 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat lahir</label>
                        <input type="text" name="tempat_lahir" placeholder="Tempat lahir siswa">
                    </div>

                    <div class="form-group">
                        <label>Tanggal lahir</label>
                        <input type="date" name="tanggal_lahir">
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="form-action">
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection