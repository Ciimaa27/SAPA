@extends('layouts.app')

@section('title', 'Edit Jadwal Pulang')

@section('sidebar')
    @include('layouts.sidebar-admin')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('btnKirim').addEventListener('click', function() {

        let kelasDipilih = [];

        document.querySelectorAll('input[name="kelas_tujuan[]"]:checked')
            .forEach(function(item){
                kelasDipilih.push(item.value);
            });

        Swal.fire({
            title: '📢 Kirim Notifikasi?',
            html: `
                <p style="text-align:left">
                    Jadwal pulang akan diperbarui dan notifikasi WA akan dikirim ke seluruh wali kelas
                    <b>${kelasDipilih.join(', ')}</b>.
                </p>

                <p style="text-align:left;color:#666;margin-top:15px">
                    ⚠️ Pastikan data sudah benar.<br>
                    Proses ini tidak dapat dibatalkan.
                </p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4f8dfd',
            cancelButtonColor: '#dc3545',
            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {
                document.getElementById('formJadwalPulang').submit();
            }

        });

    });
    </script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/edit-jadwal-pulang.css') }}">
@endpush

@section('content')

<div class="container">

    {{-- HEADER --}}
    <div class="judul-halaman">
        Edit jadwal pulang
    </div>

    {{-- INFO --}}
    <div class="info-box">
        ! Fitur ini digunakan untuk mengirim notifikasi WhatsApp kepada wali siswa terkait perubahan jadwal pulang.
    </div>

    {{-- FORM --}}
   <form id="formJadwalPulang"
      action="{{ route('jadwal-pulang.update-satu') }}"
      method="POST">
    @csrf

    <input type="hidden" name="kelas" value="{{ $activeKelas }}">
    <input type="hidden" name="hari" value="{{ $hariDipilih }}">

    <div class="card-jadwal">

        <div class="form-grid">

            {{-- HARI --}}
            <div class="field-label">Hari</div>
            <div class="field-separator">:</div>

            <div class="field-control">
                <input type="text"
                    class="readonly-input"
                    value="{{ $hariDipilih }}"
                    readonly>
            </div>

            {{-- JAM --}}
            <div class="field-label">Jam pulang</div>
            <div class="field-separator">:</div>

            <div class="field-control">
                <input type="time"
                    name="jam"
                    class="jam-input"
                    value="{{ $jadwal && $jadwal->jam ? \Carbon\Carbon::parse($jadwal->jam)->format('H:i') : '' }}">
            </div>

            {{-- ALASAN --}}
            <div class="field-label">Alasan</div>
            <div class="field-separator">:</div>

            <div class="field-control">
                <select class="select-input" name="alasan">
                    <option value="">Pilih alasan</option>
                    <option value="Rapat guru">Rapat guru</option>
                    <option value="Kegiatan sekolah">Kegiatan sekolah</option>
                    <option value="Libur khusus">Libur khusus</option>
                    <option value="Gladi / persiapan acara">Gladi / persiapan acara</option>
                    <option value="Kelas tambahan">Kelas tambahan</option>
                </select>
            </div>

            {{-- PILIH KELAS --}}
            <div class="field-label">Pilih kelas</div>
            <div class="field-separator">:</div>

            <div class="field-control">

                <div class="checkbox-note">
                    *Perhatikan dengan benar ketika memilih kelas
                </div>

                <div class="checkbox-grid">
                    @for($i = 1; $i <= 6; $i++)
                    <label class="checkbox-item">
                        <input type="checkbox"
                            name="kelas_tujuan[]"
                            value="{{ $i }}"
                            {{ $i == $activeKelas ? 'checked' : '' }}>
                        Kelas {{ $i }}
                    </label>
                    @endfor
                </div>

            </div>

        </div>

        <div class="actions-row">

            <button type="button"
                    class="btn-batal"
                    onclick="window.location='{{ route('jadwal-pulang', ['kelas' => $activeKelas]) }}'">
                Batal
            </button>

            <button type="button" class="btn-simpan" id="btnKirim">
                Kirim
            </button>

        </div>

    </div>

</form>
