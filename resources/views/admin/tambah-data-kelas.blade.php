@extends('layouts.app')
@section('title', 'Tambah Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/tambah-data-kelas.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <h5 class="page-title">Tambah kelas</h5>

        <div class="card-form">
            <a href="{{ route('kelas') }}" class="btn-kembali">
                ← Kembali
            </a>

            <form action="#" method="POST">
                @csrf

                <div class="form-row">

                    <!-- Tingkat Kelas -->
                <div class="form-group">
                    <label>Tingkat kelas</label>
                    <select name="tingkat" class="form-control">
                    <option value="">-- Pilih tingkat --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    </select>
                </div>

                    <!-- Sub Kelas -->
                    <div class="form-group">
                    <label>Sub-kelas</label>
                    <select name="sub_kelas" class="form-control">
                    <option value="">-- Pilih sub kelas --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                </select>
            </div>

                </div>

                <!-- Wali Kelas -->
                <div class="form-group full">
                <label>Wali kelas</label>
                <select name="wali_kelas" class="form-control">
                    <option value="">-- Pilih wali kelas --</option>
                    <option value="Arif Nasution">Arif Nasution</option>
                    <option value="Radita Nabila">Radita Nabila</option>
                    <option value="Ismatul Hawa">Ismatul Hawa</option>
                    <option value="Ilham Basudara">Ilham Basudara</option>
                </select>
            </div>

                <!-- Button -->
                <div class="form-action">
                    <button type="button" class="btn-batal">Batal</button>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection
