@extends('layouts.app')

@section('title', 'Relasi Siswa dan Wali')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/tambah-relasi.css') }}">
{{-- SELECT2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@endpush

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
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>

            {{-- FORM TAMBAH RELASI --}}
            <form action="{{ url('/admin/relasi') }}" method="POST">
                @csrf

                {{-- NAMA SISWA --}}
                <div class="form-group">
                    <label for="selectSiswa">Nama siswa</label>
                    <select name="id_siswa" id="selectSiswa" class="form-select searchable-select" required>
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
                    <select name="id_wali" id="selectWali" class="form-select searchable-select" required>
                        <option value=""></option>
                        @foreach ($wali as $item)
                            <option value="{{ $item->id_wali }}" data-no-hp="{{ $item->no_hp }}" {{ old('id_wali') == $item->id_wali ? 'selected' : '' }}>
                                {{ $item->nama_wali }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text">
                        *Ketik atau pilih nama wali siswa dengan benar
                    </small>
                </div>

                {{-- NO. TELEPON --}}
                <div class="form-group">
                    <label for="nomorTlp">Nomor telepon</label>
                    <input type="text" id="nomorTlp" class="form-control" value="{{ optional($wali->firstWhere('id_wali', old('id_wali')))->no_hp ?? '' }}" readonly>
                    <small class="form-text">
                        *Nomor telepon wali akan muncul otomatis saat memilih nama wali
                    </small>
                </div>

                {{-- STATUS HUBUNGAN --}}
                    <div class="form-group">
                        <label for="hubungan">Status hubungan</label>
                        <select name="hubungan" id="hubungan" class="form-select" required>
                            <option value="">-- Pilih status --</option>
                            <option value="Ayah" {{ old('hubungan') == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="Ibu" {{ old('hubungan') == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="Wali 1" {{ old('hubungan') == 'Wali 1' ? 'selected' : '' }}>Wali 1</option>
                            <option value="Wali 2" {{ old('hubungan') == 'Wali 2' ? 'selected' : '' }}>Wali 2</option>
                            <option value="Wali 3" {{ old('hubungan') == 'Wali 3' ? 'selected' : '' }}>Wali 3</option>
                        </select>
                    </div>

                {{-- BUTTON --}}
                <div class="form-action">
                    <button type="reset" id="btnReset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('#selectSiswa').select2({
        placeholder: '-- Cari atau pilih siswa --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () {
                return 'Nama siswa tidak ditemukan';
            }
        }
    });

    $('#selectWali').select2({
        placeholder: '-- Cari atau pilih wali --',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () {
                return 'Nama wali tidak ditemukan';
            }
        }
    });

    $('#btnReset').on('click', function () {
        setTimeout(function () {
            $('#selectSiswa').val(null).trigger('change');
            $('#selectWali').val(null).trigger('change');
        }, 0);
    });

    $('#selectWali').on('change', function () {
        const selectedOption = $(this).find('option:selected');
        $('#nomorTlp').val(selectedOption.attr('data-no-hp') || '');
    }).trigger('change');
});
</script>
@endpush
