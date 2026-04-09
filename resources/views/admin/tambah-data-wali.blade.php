@extends('layouts.app')

@section('title', 'Data Wali')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/tambah-data-wali.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Data wali
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- BUTTON KEMBALI -->
            <a href="/admin/data-wali" class="btn-kembali">
                <i class="fas fa-chevron-left"></i> Kembali
            </a>

            <form action="{{ route('wali.store') }}" method="POST">
                @csrf

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama orangtua/wali</label>
                    <input type="text" name="nama_wali" placeholder="Masukkan nama lengkap orang tua">
                    <small class="form-text">
                        *Perhatikan penulisan nama, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- NOMOR HP -->
                <div class="form-group full">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp" placeholder="Masukkan nomor HP">
                    <small class="form-text">
                        *Nomor yang dimasukkan wajib terdaftar di WhatsApp
                    </small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih jenis kelamin --</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
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