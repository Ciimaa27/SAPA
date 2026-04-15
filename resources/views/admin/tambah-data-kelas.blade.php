@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-data-kelas.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <h5 class="page-title">Tambah kelas</h5>

        <div class="card-form">
            <a href="{{ route('kelas') }}" class="btn-kembali">
                ← Kembali
            </a>

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('store-kelas') }}" method="POST">
                @csrf

                <div class="form-row">

                    <!-- Tingkat -->
                    <div class="form-group">
                        <label>Tingkat kelas</label>
                        <select name="tingkat" class="form-control" required>
                            <option value="">-- Pilih tingkat --</option>
                            @for($i=1;$i<=6;$i++)
                                <option value="{{ $i }}" {{ old('tingkat') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Sub -->
                    <div class="form-group">
                        <label>Sub-kelas</label>
                        <select name="sub_kelas" class="form-control" required>
                            <option value="">-- Pilih sub kelas --</option>
                            @foreach(['A','B','C','D','E'] as $sub)
                                <option value="{{ $sub }}" {{ old('sub_kelas') == $sub ? 'selected' : '' }}>
                                    {{ $sub }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Wali -->
                <div class="form-group full">
                    <label>Wali kelas</label>
                    <select name="id_guru" class="form-control" required>
                        <option value="">-- Pilih wali kelas --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Button -->
                <div class="form-action">
                    <a href="{{ route('kelas') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection
