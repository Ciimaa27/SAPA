@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/edit-data-siswa.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="page-title-box">
            Data siswa
        </div>

        <!-- CARD -->
        <div class="card-form">
            <!-- BUTTON KEMBALI -->
            <a href="{{ route('data-siswa') }}" class="btn btn-kembali mb-3">
                ← Kembali
            </a>

            <form action="{{ route('update-siswa', $siswa->id_siswa) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- NIS -->
                <div class="form-row">
                    <div class="form-group">
                        <label>NIS siswa</label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}">
                    </div>
                </div>

                <!-- KELAS -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="id_kelas" class="form-select">
                            <option value="">Pilih kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- NAMA -->
                <div class="form-group full">
                    <label>Nama lengkap siswa</label>
                    <input type="text" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}">
                    <small class="form-text">*Pastikan penulisan nama siswa, agar tidak ada kesalahan pendataan</small>
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group full">
                    <label>Jenis kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>
                </div>

                <!-- TEMPAT LAHIR -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                    </div>
                </div>

                <!-- TANGGAL LAHIR -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="form-action">
                    <a href="{{ route('data-siswa') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
