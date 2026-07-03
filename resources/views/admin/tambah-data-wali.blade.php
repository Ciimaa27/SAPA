@extends('layouts.app')

@section('title', 'Data Wali')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-data-wali.css') }}">
@endpush

@section('content')
    
<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Data wali
        </div>

        <!-- CARD -->
        <div class="card-form">

            <!-- BUTTON KEMBALI -->
            <div class="mb-4">
                <a href="/admin/data-wali" class="btn-kembali">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form action="{{ route('wali.store') }}" method="POST">
                @csrf

                <!-- ID FINGERPRINT -->
                    <div class="form-group full">
                        <label>ID Fingerprint</label>
                        <input
                            type="text"
                            name="fingerprint_id"
                            value="{{ old('fingerprint_id') }}"
                            placeholder="Masukkan nomor ID fingerprint"
                            required
                        >
                        <small class="form-text">
                            *Masukkan ID fingerprint yang terdaftar pada sensor sidik jari
                        </small>
                    </div>

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
                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
