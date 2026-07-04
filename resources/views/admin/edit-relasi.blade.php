@extends('layouts.app')

@section('title', 'Relasi Siswa dan Wali')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-relasi.css') }}">
@endpush

{{-- 🔥 CONTENT --}}
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

            <form action="{{ route('relasi.update', ['id_siswa' => $relasi->id_siswa, 'id_wali' => $relasi->id_wali]) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- NAMA SISWA -->
                <div class="form-group">
                    <label>Nama siswa</label>
                    <select name="id_siswa" class="form-select">
                        <option value="">-- Pilih siswa --</option>
                        @foreach($siswa as $item)
                            <option value="{{ $item->id_siswa }}" {{ $item->id_siswa == old('id_siswa', $relasi->id_siswa) ? 'selected' : '' }}>
                                {{ $item->nama_siswa }}
                            </option>
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
                        @foreach($wali as $item)
                            <option value="{{ $item->id_wali }}" {{ $item->id_wali == old('id_wali', $relasi->id_wali) ? 'selected' : '' }}>
                                {{ $item->nama_wali }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text">
                        *Pilih wali siswa, dan pastikan relasi sudah benar
                    </small>
                </div>

                <!-- STATUS HUBUNGAN -->
                <div class="form-group">
                    <label>Status hubungan</label>
                    <select name="hubungan" class="form-select">
                        <option value="">-- Pilih status --</option>
                        <option value="Ayah" {{ old('hubungan', $relasi->hubungan) == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                        <option value="Ibu" {{ old('hubungan', $relasi->hubungan) == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                        <option value="Wali" {{ old('hubungan', $relasi->hubungan) == 'Wali' ? 'selected' : '' }}>Wali</option>
                    </select>
                </div>

                <!-- BUTTON -->
                <div class="form-action">
                    <a href="{{ route('relasi.index') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection
