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

            {{-- ================= INFORMASI ================= --}}
                <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                    <i class="fas fa-circle-info me-3 mt-1"></i>

                    <div>
                        <strong>Informasi</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>Pastikan data <strong>Guru</strong> atau <strong>Orang Tua/Wali</strong> telah ditambahkan terlebih dahulu melalui menu <strong>Data Guru</strong> atau <strong>Data Wali</strong>.</li>
                            <li>Untuk peran <strong>Guru</strong> dan <strong>Orang Tua/Wali</strong>, pilih nama melalui dropdown yang tersedia.</li>
                            <li>Untuk peran <strong>Admin</strong> dan <strong>Kepala Sekolah</strong>, nama lengkap diisi secara manual.</li>
                        </ul>
                    </div>
                </div>
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
                            id="nama_lengkap"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                            placeholder="Masukkan nama lengkap..."
                            required>
                    <small class="text-muted d-block mt-1">
                        *Digunakan untuk peran admin dan kepsek
                    </small>
                        @error('nama_lengkap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Pilih Guru / Wali
                        </label>
                        <select
                            id="data_user"
                            name="data_user"
                            class="form-select"
                            disabled>
                            <option value="">Pilih Data</option>
                        </select>
                        <small class="text-muted">
                            *Digunakan untuk memilih data Guru atau Orang Tua/Wali yang sudah terdaftar.
                        </small>
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
                    <button type="reset" class="btn btn-reset">
                        Reset
                    </button>

                    <button type="submit" class="btn btn-success">
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

    // ==========================
    // DATA DARI CONTROLLER
    // ==========================
    const guru = @json($guru);
    const wali = @json($wali);
    console.log(wali);

    // ==========================
    // SHOW / HIDE PASSWORD
    // ==========================
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

    // ==========================
    // ELEMENT
    // ==========================
    const peran = document.getElementById('peran');
    const inputNama = document.getElementById('nama_lengkap');
    const dropdown = document.getElementById('data_user');

    // ==========================
    // SAAT PERAN BERUBAH
    // ==========================
    peran.addEventListener('change', function () {
        console.log(this.value);
        dropdown.innerHTML = '<option value="">Pilih Data</option>';

        inputNama.value = '';
        dropdown.value = '';

        if (this.value === 'Guru') {

            inputNama.readOnly = true;
            dropdown.disabled = false;

            guru.forEach(function(item){

                dropdown.innerHTML += `
                    <option value="${item.id_guru}">
                        ${item.nama_guru}
                    </option>
                `;

            });

        }

        else if (this.value === 'Orangtua/Wali') {

            inputNama.readOnly = true;
            dropdown.disabled = false;
            console.log(wali);
            wali.forEach(function(item){

                dropdown.innerHTML += `
                    <option value="${item.id_wali}">
                        ${item.nama_wali}
                    </option>
                `;
            });
        }
        else {
            inputNama.readOnly = false;
            dropdown.disabled = true;
            dropdown.innerHTML = '<option value="">Pilih Data</option>';
        }
    });
    // ==========================
    // SAAT MEMILIH GURU / WALI
    // ==========================
    dropdown.addEventListener('change', function () {
        if (peran.value === 'Guru') {
            const item = guru.find(g => g.id_guru == this.value);
            if(item){
                inputNama.value = item.nama_guru;
            }
        }
        else if (peran.value === 'Orangtua/Wali') {
            const item = wali.find(w => w.id_wali == this.value);
            if(item){
                inputNama.value = item.nama_wali;
            }
        }
    });

    // Jalankan event saat halaman pertama kali dibuka
    peran.dispatchEvent(new Event('change'));
});
</script>

@endsection
