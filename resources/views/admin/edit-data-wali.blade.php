@extends('layouts.app')

@section('title', 'Edit Data Wali')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/edit-data-wali.css') }}">
@endpush

{{-- 🔥 CONTENT --}}
@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Data wali
        </div>

        <!-- CARD -->
        <div class="card-form">

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- BUTTON KEMBALI -->
            <a href="{{ route('data-wali') }}" class="btn btn-kembali mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <form action="{{ route('update-wali', $wali->id_wali) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- ID FINGERPRINT -->
                <div class="form-group full">
                    <label>ID Fingerprint</label>
                    <input type="text" name="fingerprint_id" value="{{ old('fingerprint_id', $wali->fingerprint_id) }}" placeholder="Masukkan nomor ID fingerprint">
                    <small class="form-text">
                        *Masukkan ID fingerprint yang terdaftar pada sensor sidik jari
                    </small>
                </div>

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama orangtua/wali</label>
                    <input type="text" name="nama_wali" value="{{ old('nama_wali', $wali->nama_wali) }}">
                    <small class="form-text">
                        *Perhatikan penulisan nama, agar tidak ada kesalahan pendataan
                    </small>
                </div>

                <!-- NOMOR HP -->
                <div class="form-group full">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $wali->no_hp) }}">
                    <small class="form-text">
                        *Nomor yang dimasukkan wajib terdaftar di WhatsApp
                    </small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih jenis kelamin --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $wali->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $wali->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="form-action">
                    <a href="{{ route('data-wali') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
