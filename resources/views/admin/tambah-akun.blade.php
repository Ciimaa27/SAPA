@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-akun.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Kelola Akun Pengguna</h5>
        </div>

        {{-- Form Card --}}
        <div class="card p-4">

            <a href="{{ route('kelola-akun.index') }}" class="btn btn-kembali mb-4">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>

            <form action="{{ route('kelola-akun.store') }}" method="POST">
                @csrf

                {{-- Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <h6 class="fw-bold mb-2">Terjadi Kesalahan!</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ================= PERAN ================= --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Peran
                    </label>

                    <select
                        name="peran"
                        id="peran"
                        class="form-select @error('peran') is-invalid @enderror"
                        required>

                        <option value="">Pilih Peran</option>

                        <option value="Admin"
                            {{ old('peran') == 'Admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="Guru"
                            {{ old('peran') == 'Guru' ? 'selected' : '' }}>
                            Guru
                        </option>

                        <option value="Orangtua/Wali"
                            {{ old('peran') == 'Orangtua/Wali' ? 'selected' : '' }}>
                            Orang Tua / Wali
                        </option>

                        <option value="Kepala Sekolah"
                            {{ old('peran') == 'Kepala Sekolah' ? 'selected' : '' }}>
                            Kepala Sekolah
                        </option>

                    </select>

                    <small class="text-muted d-block mt-1">
                        *Pilih peran akun yang akan dibuat.
                    </small>

                    @error('peran')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror

                </div>

                {{-- ================= NAMA & USERNAME ================= --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                            placeholder="Masukkan nama lengkap..."
                            required>

                        @error('nama_lengkap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Nama Pengguna
                        </label>

                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukkan nama pengguna..."
                            required>

                        <small class="text-muted d-block mt-1">
                            *Digunakan untuk login.
                        </small>

                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>

                {{-- ================= EMAIL ================= --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Masukkan email..."
                        required>

                    <small class="text-muted d-block mt-1">
                        *Pastikan email masih aktif.
                    </small>

                    @error('email')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror

                </div>

                {{-- ================= PASSWORD ================= --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Password
                        </label>

                        <div class="input-group">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password..."
                                required>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword">

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                        <small class="text-muted d-block mt-1">
                            *Password minimal <strong>6 karakter</strong>.
                        </small>

                        @error('password')
                            <small class="text-danger d-block">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Konfirmasi Password
                        </label>

                        <div class="input-group">

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Masukkan kembali password..."
                                required>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="toggleConfirmPassword">

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                        <small class="text-muted d-block mt-1">
                            *Masukkan password yang sama.
                        </small>

                    </div>

                </div>

                <hr class="my-4">

                {{-- ================= BUTTON ================= --}}
                <div class="d-flex justify-content-end gap-2">

                    <button
                        type="reset"
                        class="btn btn-outline-danger px-4">

                        <i class="fas fa-rotate-left me-1"></i>
                        Reset

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success px-4">

                        <i class="fas fa-floppy-disk me-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    function togglePassword(inputId, buttonId) {

        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);

        button.addEventListener('click', function () {

            const icon = this.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');

            } else {

                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');

            }

        });

    }

    togglePassword('password', 'togglePassword');
    togglePassword('password_confirmation', 'toggleConfirmPassword');

});
</script>

@endsection
