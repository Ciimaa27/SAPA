@extends('layouts.app')

@section('title', 'Edit Data Guru')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/edit-data-guru.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="page-title-box">
            Edit Data Guru
        </div>

        <div class="card-form">

            {{-- ERROR VALIDATION --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- BUTTON KEMBALI --}}
            <a href="{{ route('guru') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <form action="{{ route('update-guru', $guru->id_guru) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ROW 1 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip"
                            value="{{ old('nip', $guru->nip) }}"
                            placeholder="Masukkan NIP guru">
                    </div>

                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="text" name="no_hp"
                            value="{{ old('no_hp', $guru->no_hp) }}"
                            placeholder="Masukkan nomor HP">
                    </div>
                </div>

                {{-- NAMA --}}
                <div class="form-group full">
                    <label>Nama Lengkap Guru</label>
                    <input type="text" name="nama_guru"
                        value="{{ old('nama_guru', $guru->nama_guru) }}"
                        placeholder="Masukkan nama lengkap guru">
                </div>

                {{-- ROW 2 --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $guru->tempat_lahir) }}"
                            placeholder="Tempat lahir guru">
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}">
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="form-action">
                    <a href="{{ route('guru') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
