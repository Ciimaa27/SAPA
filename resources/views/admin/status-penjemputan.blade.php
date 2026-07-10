@extends('layouts.app')

@section('title', 'Status Penjemputan')

{{-- ===========================
    SIDEBAR
=========================== --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- ===========================
    CSS / SCRIPTS ATAS
=========================== --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/status-penjemputan.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

{{-- ===========================
    CONTENT
=========================== --}}
@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- JUDUL --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Status Penjemputan</h5>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="mb-3">
            <a href="{{ route('data-penjemputan') }}" class="btn btn-kembali">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
        {{-- CATATAN --}}
        <div class="alert alert-warning py-2 px-3 mb-3 small" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <strong>Catatan:</strong>
            Simpan perubahan setiap siswa satu per satu sebelum melanjutkan ke siswa berikutnya.
        </div>
        {{-- INFORMASI --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-semibold">Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->nama_kelas }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <label class="form-label fw-semibold">Wali Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->nama_guru }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" class="form-control" value="{{ $tanggal }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL DATA SISWA --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-semibold">Daftar Siswa</h6>
                </div>

                <div class="table-container">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="120">NIS</th>
                                <th class="text-start">Nama Siswa</th>
                                <th width="180">Status</th>
                                <th width="260">Penjemput</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $item)
                                @php
                                    $penjemputSiswa = $daftarPenjemput->get($item->id_siswa, collect());
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->nis }}</td>
                                    <td class="text-start">{{ $item->nama_siswa }}</td>

                                    <form action="{{ route('data-penjemputan.update-status') }}" method="POST" onsubmit="return simpanStatus(this)">
                                        @csrf
                                        <input type="hidden" name="id_siswa" value="{{ $item->id_siswa }}">
                                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                                        {{-- STATUS SELECT --}}
                                        <td>
                                            <select name="status" class="form-select form-select-sm status-select" onchange="ubahStatus(this)">
                                                <option value="Menunggu" {{ ($item->status ?? 'Menunggu') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Dijemput" {{ $item->status == 'Dijemput' ? 'selected' : '' }}>Dijemput</option>
                                            </select>
                                        </td>

                                        {{-- PENJEMPUT SELECT --}}
                                        <td>
                                            <select name="id_wali" class="form-select form-select-sm penjemput-select" {{ $item->status == 'Dijemput' ? '' : 'disabled' }}>
                                                <option value="">Pilih Penjemput</option>
                                                @foreach($penjemputSiswa as $penjemput)
                                                    <option value="{{ $penjemput->id_wali }}" {{ $item->id_wali == $penjemput->id_wali ? 'selected' : '' }}>
                                                        {{ ucfirst($penjemput->hubungan) }} - {{ $penjemput->nama_wali }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="text-center">
                                            <button type="submit" class="btn btn-success btn-sm btn-simpan">Simpan</button>
                                        </td>
                                    </form>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===========================
    SWEET ALERT NOTIFIKASI
=========================== --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Status berhasil diperbarui',
        text: 'Data penjemputan berhasil disimpan.',
        confirmButtonColor: '#198754',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        confirmButtonColor: '#dc3545'
    });
</script>
@endif

{{-- ===========================
    JAVASCRIPT INTERAKSI
=========================== --}}
<script>
    // EVENT STATUS BERUBAH
    function ubahStatus(select) {
        const row = select.closest('tr');
        const penjemput = row.querySelector('.penjemput-select');

        if (select.value === 'Dijemput') {
            penjemput.disabled = false;
        } else {
            penjemput.value = '';
            penjemput.disabled = true;
        }
    }

    // HANDLER SIMPAN STATUS
    function simpanStatus(form) {
        const status = form.querySelector('.status-select');
        const penjemput = form.querySelector('.penjemput-select');

        if (status.value === 'Dijemput' && penjemput.value === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Penjemput',
                text: 'Silakan pilih siapa yang menjemput siswa.',
                confirmButtonColor: '#6f42c1'
            });
            return false;
        }

        const btn = form.querySelector('.btn-simpan');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menyimpan...`;

        return true;
    }
</script>
@endsection
