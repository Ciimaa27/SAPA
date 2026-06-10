@extends('layouts.app')

@section('title', 'Relasi Siswa dan Wali')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-relasi.css') }}">
@endpush

@section('content')

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

           <form action="{{ url('/admin/relasi') }}" method="POST">
                 @csrf

                <!-- NAMA SISWA -->
                <div class="form-group">
                    <label>Nama siswa</label>
                    <select name="id_siswa" class="form-select">
                        <option value="">-- Pilih siswa --</option>
                        @foreach ($siswa as $item)
                            <option value="{{ $item->id_siswa }}">{{ $item->nama_siswa }}</option>
                        @endforeach
                    </select>
                    <small class="form-text">
                        *Pilih siswa yang akan dihubungkan dengan wali
                    </small>
                </div>

                <!-- NAMA WALI -->
                <div class="form-group">
                    <label>Nama wali siswa</label>
                    <select name="id_wali" class="form-select">
                        <option value="">-- Pilih wali --</option>
                        @foreach ($wali as $item)
                            <option value="{{ $item->id_wali }}">{{ $item->nama_wali }}</option>
                        @endforeach
                    </select>
                    <small class="form-text">
                        *Pilih wali siswa dengan benar
                    </small>
                </div>

                <!-- STATUS HUBUNGAN -->
                <div class="form-group">
                    <label>Status hubungan</label>
                    <select name="hubungan" class="form-select">
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
