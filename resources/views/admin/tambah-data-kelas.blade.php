@extends('layouts.app')

@section('title', 'Tambah Kelas')

{{-- ===========================
    SIDEBAR
=========================== --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- ===========================
    CSS
=========================== --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-data-kelas.css') }}">

{{-- SELECT2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endpush

{{-- ===========================
    CONTENT
=========================== --}}
@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- HEADER --}}
        <div class="page-title-box">
            Tambah kelas
        </div>

        {{-- FORM CARD --}}
        <div class="card-form">
            <a href="{{ route('kelas') }}" class="btn btn-kembali mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('store-kelas') }}" method="POST">
                @csrf

                {{-- TINGKAT DAN SUB KELAS --}}
                <div class="form-row">
                    {{-- TINGKAT --}}
                    <div class="form-group">
                        <label>Tingkat kelas</label>
                        <select name="tingkat" class="form-control" required>
                            <option value="">-- Pilih tingkat --</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('tingkat') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- SUB KELAS --}}
                    <div class="form-group">
                        <label>Sub-kelas</label>
                        <select name="sub_kelas" class="form-control" required>
                            <option value="">-- Pilih sub kelas --</option>
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $sub)
                                <option value="{{ $sub }}" {{ old('sub_kelas') == $sub ? 'selected' : '' }}>
                                    {{ $sub }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- WALI KELAS --}}
                <div class="form-group full">
                    <label>Wali kelas</label>
                    <select name="id_guru" id="wali_kelas" class="form-control" required>
                        <option value="">-- Pilih atau cari wali kelas --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- BUTTON ACTIONS --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

{{-- ===========================
    JAVASCRIPT
=========================== --}}
@push('scripts')
{{-- JQUERY --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- SELECT2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('#wali_kelas').select2({
        placeholder: '-- Pilih atau cari wali kelas --',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush
