@extends('layouts.app')

@section('title', 'Relasi Siswa dan Wali')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/tambah-relasi.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Relasi siswa dan wali
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- BUTTON KEMBALI -->
            <a href="{{ route('relasi.index') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>

            <form action="#" method="POST">
                @csrf

                <!-- NAMA SISWA -->
                <div class="form-group">
                    <label>Nama siswa</label>
                    <input type="text" name="siswa" placeholder="Ketik nama siswa">
                    <small class="form-text">
                        *Ketik dan pilih nama siswa yang akan dihubungkan dengan wali
                    </small>
                </div>

                <!-- NAMA WALI -->
                <div class="form-group">
                    <label>Nama wali siswa</label>
                    <input type="text" name="wali" placeholder="Ketik nama wali">
                    <small class="form-text">
                        *Ketik dan pilih nama wali siswa, dan pastikan benar dalam menghubungkan relasi
                    </small>
                </div>

                <!-- STATUS HUBUNGAN -->
                <div class="form-group">
                    <label>Status hubungan</label>
                    <select name="status">
                        <option value="">-- Pilih status --</option>
                        <option value="Ayah">Ayah</option>
                        <option value="Ibu">Ibu</option>
                        <option value="Wali">Wali</option>
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
