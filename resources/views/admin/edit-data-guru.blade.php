@extends('layouts.app')

@section('title', 'Edit Data Guru')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/edit-data-guru.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Edit data guru
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- ALERT ERROR -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- BUTTON KEMBALI -->
            <a href="{{ route('guru') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <!-- 🔥 FORM FIX -->
            <form action="{{ route('update-guru', $guru->id_guru) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ROW 1 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" placeholder="Masukkan NIP guru">
                        <small class="form-text">*Pastikan memasukkan NIP yang benar</small>
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" placeholder="Masukkan nomor HP">
                    </div>
                </div>

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama lengkap guru</label>
                    <input type="text" name="nama_guru" value="{{ old('nama_guru', $guru->nama_guru) }}" placeholder="Masukkan nama lengkap guru...">
                    <small class="form-text">
                        *Perhatikan penulisan nama, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- ROW 2 -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat lahir guru">
                    </div>

                    <div class="form-group">
                        <label>Tanggal lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
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