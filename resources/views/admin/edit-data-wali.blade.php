@extends('layouts.app')

@section('title', 'Edit Data Wali')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/edit-data-wali.css') }}">

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

            <form action="#" method="POST">
                @csrf

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama orangtua/wali</label>
                    <input type="text" name="nama">
                    <small class="form-text">
                        *Perhatikan penulisan nama, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- NOMOR HP -->
                <div class="form-group full">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp">
                    <small class="form-text">
                        *Nomor yang dimasukkan wajib terdaftar di WhatsApp
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

                <!-- BUTTON -->
                <div class="form-action">
                    <a href="/admin/data-wali" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection