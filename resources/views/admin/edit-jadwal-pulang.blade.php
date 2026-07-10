@extends('layouts.app')

@section('title','Edit Jadwal Pulang')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/edit-jadwal-pulang.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- HEADER --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Edit Jadwal Pulang</h5>
        </div>

        {{-- INFO --}}
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="info-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>
                        Perubahan jadwal pulang akan mengirimkan notifikasi WhatsApp kepada wali siswa yang dipilih.
                    </span>
                </div>
            </div>
        </div>

        <form id="formJadwalPulang"
              action="{{ route('jadwal-pulang.update-satu') }}"
              method="POST">

            @csrf

            <input type="hidden" name="kelas" value="{{ $activeKelas }}">
            <input type="hidden" name="hari" value="{{ $hariDipilih }}">

            <div class="card">
                <div class="card-body">

                    <div class="form-grid">

                        {{-- HARI --}}
                        <div class="field-label">Hari</div>
                        <div class="field-separator">:</div>
                        <div class="field-control">
                            <input type="text" class="form-control" value="{{ $hariDipilih }}" readonly>
                        </div>

                        {{-- JAM --}}
                        <div class="field-label">Jam Pulang</div>
                        <div class="field-separator">:</div>
                        <div class="field-control">
                            <input type="time"
                                   class="form-control"
                                   name="jam"
                                   value="{{ $jadwal && $jadwal->jam ? \Carbon\Carbon::parse($jadwal->jam)->format('H:i') : '' }}">
                        </div>

                        {{-- ALASAN --}}
                        <div class="field-label">Alasan</div>
                        <div class="field-separator">:</div>
                        <div class="field-control">
                            <select class="form-select" name="alasan">
                                <option value="">Pilih alasan</option>
                                <option value="Rapat guru">Rapat Guru</option>
                                <option value="Kegiatan sekolah">Kegiatan Sekolah</option>
                                <option value="Libur khusus">Libur Khusus</option>
                                <option value="Gladi / persiapan acara">Gladi / Persiapan Acara</option>
                                <option value="Kelas tambahan">Kelas Tambahan</option>
                            </select>
                        </div>

                        {{-- PILIH KELAS --}}
                        <div class="field-label">Pilih Kelas</div>
                        <div class="field-separator">:</div>
                        <div class="field-control">
                            <small class="text-muted d-block mb-3">
                                Pilih kelas yang akan menerima perubahan jadwal pulang.
                            </small>
                            <div class="checkbox-wrapper">
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

                    <div class="button-area">
                        <a href="{{ route('jadwal-pulang',['kelas'=>$activeKelas]) }}" class="btn-batal">Batal</a>
                        <button type="button" class="btn-simpan" id="btnKirim">Kirim</button>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>

<script>
document.getElementById('btnKirim').addEventListener('click', function () {
    let kelasDipilih = [];

    document.querySelectorAll('input[name="kelas_tujuan[]"]:checked').forEach(function (item) {
        kelasDipilih.push(item.value);
    });

    // Ambil alasan yang dipilih
    const alasan = document.querySelector('select[name="alasan"]').value;

    Swal.fire({
        title: 'Kirim Notifikasi?',
        html: `
            Jadwal pulang akan dikirim ke Orangtua/Wali siswa
            <b>Kelas ${kelasDipilih.join(', ')}</b>
            dengan alasan <b>${alasan}</b>.
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#7c4dff',
        cancelButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formJadwalPulang').submit();
        }
    });
});
</script>
@endsection
