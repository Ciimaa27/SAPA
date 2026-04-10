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

            <form action="{{ route('update-wali', $wali->id_wali) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama orangtua/wali</label>
                    <input type="text" name="nama_wali" value="{{ $wali->nama_wali }}">
                    <small class="form-text">
                        *Perhatikan penulisan nama, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- NOMOR HP -->
                <div class="form-group full">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp" value="{{ $wali->no_hp }}">
                    <small class="form-text">
                        *Nomor yang dimasukkan wajib terdaftar di WhatsApp
                    </small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih jenis kelamin --</option>
                        <option value="Laki-laki" {{ $wali->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $wali->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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