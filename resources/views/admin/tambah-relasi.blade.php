@extends('layouts.app')

@section('title', 'Relasi Siswa dan Wali')

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
<link rel="stylesheet" href="{{ asset('css/admin/tambah-relasi.css') }}">

{{-- SELECT2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
    /* ==============================
       SELECT2 MENYESUAIKAN FORM
    ============================== */
    .select2-container {
        width: 100% !important;
    }
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        display: flex;
        align-items: center;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 12px;
        padding-right: 35px;
        line-height: normal;
        color: #212529;
    }
    .select2-container .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 5px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .select2-dropdown {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        overflow: hidden;
    }
    .select2-search--dropdown {
        padding: 8px;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #dee2e6 !important;
        border-radius: 5px;
        padding: 7px 10px;
        outline: none;
    }
    .select2-results__option {
        padding: 8px 12px;
    }
</style>
@endpush

{{-- ===========================
    CONTENT
=========================== --}}
@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- HEADER --}}
        <div class="page-title-box">
            Relasi siswa dan wali
        </div>

        {{-- CARD FORM --}}
        <div class="card-form">

            {{-- BUTTON KEMBALI --}}
            <a href="{{ route('relasi.index') }}" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            {{-- FORM TAMBAH RELASI --}}
            <form action="{{ url('/admin/relasi') }}" method="POST">
                @csrf

                {{-- NAMA SISWA --}}
                <div class="form-group">
                    <label for="selectSiswa">Nama siswa</label>
                    <select name="id_siswa" id="selectSiswa" class="form-select" required>
                        <option value=""></option>
                        @foreach ($siswa as $item)
                            <option value="{{ $item->id_siswa }}" {{ old('id_siswa') == $item->id_siswa ? 'selected' : '' }}>
                                {{ $item->nama_siswa }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text">
                        *Ketik atau pilih nama siswa yang akan dihubungkan dengan wali
                    </small>
                </div>

                {{-- NAMA WALI --}}
                <div class="form-group">
                    <label for="selectWali">Nama wali siswa</label>
                    <select name="id_wali" id="selectWali" class="form-select" required>
                        <option value=""></option>
                        @foreach ($wali as $item)
                            <option value="{{ $item->id_wali }}" {{ old('id_wali') == $item->id_wali ? 'selected' : '' }}>
                                {{ $item->nama_wali }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text">
                        *Ketik atau pilih nama wali siswa dengan benar
                    </small>
                </div>

                {{-- STATUS HUBUNGAN --}}
                <div class="form-group">
                    <label for="hubungan">Status hubungan</label>
                    <select name="hubungan" id="hubungan" class="form-select" required>
                        <option value="">-- Pilih status --</option>
                        <option value="Ayah" {{ old('hubungan') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                        <option value="Ibu" {{ old('hubungan') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                        <option value="Wali" {{ old('hubungan') == 'Wali' ? 'selected' : '' }}>Wali</option>
                    </select>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" id="btnReset" class="btn btn-danger btn-sm">Reset</button>
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
    // SEARCHABLE DROPDOWN SISWA
    $('#selectSiswa').select2({
        placeholder: '-- Cari atau pilih siswa --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () { return 'Nama siswa tidak ditemukan'; },
            searching: function () { return 'Mencari...'; }
        }
    });

    // SEARCHABLE DROPDOWN WALI
    $('#selectWali').select2({
        placeholder: '-- Cari atau pilih wali --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () { return 'Nama wali tidak ditemukan'; },
            searching: function () { return 'Mencari...'; }
        }
    });

    // RESET SELECT2 EVENT
    $('#btnReset').on('click', function () {
        setTimeout(function () {
            $('#selectSiswa').val(null).trigger('change');
            $('#selectWali').val(null).trigger('change');
        }, 0);
    });
});
</script>
@endpush
