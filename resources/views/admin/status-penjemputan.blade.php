@extends('layouts.app')

@section('title', 'Status Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/status-penjemputan.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- ================= JUDUL ================= --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Status Penjemputan</h5>
        </div>

        {{-- ================= TOMBOL KEMBALI ================= --}}
        <div class="mb-3">
            <a href="{{ route('data-penjemputan') }}" class="btn btn-kembali">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- ================= INFORMASI ================= --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    {{-- KELAS --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->nama_kelas }}" readonly>
                    </div>

                    {{-- WALI KELAS --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Wali Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->nama_guru }}" readonly>
                    </div>

                    {{-- TANGGAL --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" class="form-control" value="{{ $tanggal }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TABEL ================= --}}
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
                                    {{-- NO --}}
                                    <td class="text-center">{{ $loop->iteration }}</td>

                                    {{-- NIS --}}
                                    <td class="text-center">{{ $item->nis }}</td>

                                    {{-- NAMA SISWA --}}
                                    <td class="text-start">{{ $item->nama_siswa }}</td>

                                    <form action="{{ route('data-penjemputan.update-status') }}" method="POST" onsubmit="return simpanStatus(this)">
                                        @csrf
                                        <input type="hidden" name="id_siswa" value="{{ $item->id_siswa }}">
                                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                                        {{-- STATUS --}}
                                        <td>
                                            <select name="status" class="form-select form-select-sm status-select" onchange="ubahStatus(this)">
                                                <option value="Menunggu" {{ ($item->status ?? 'Menunggu') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Dijemput" {{ $item->status == 'Dijemput' ? 'selected' : '' }}>Dijemput</option>
                                            </select>
                                        </td>

                                        {{-- PENJEMPUT --}}
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

{{-- ================= SWEET ALERT SUCCESS ================= --}}
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

{{-- ================= SWEET ALERT ERROR ================= --}}
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

{{-- ================= SCRIPT ================= --}}
<script>
    /*
    |--------------------------------------------------------------------------
    | STATUS BERUBAH
    |--------------------------------------------------------------------------
    */
    function ubahStatus(select) {
        const row = select.closest('tr');
        const penjemput = row.querySelector('.penjemput-select');

        /* Jika Dijemput: aktifkan dropdown penjemput */
        if (select.value === 'Dijemput') {
            penjemput.disabled = false;
        }
        /* Jika Menunggu: kosongkan dan nonaktifkan penjemput */
        else {
            penjemput.value = '';
            penjemput.disabled = true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN STATUS
    |--------------------------------------------------------------------------
    */
    function simpanStatus(form) {
        const status = form.querySelector('.status-select');
        const penjemput = form.querySelector('.penjemput-select');

        /* Validasi penjemput */
        if (status.value === 'Dijemput' && penjemput.value === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Penjemput',
                text: 'Silakan pilih siapa yang menjemput siswa.',
                confirmButtonColor: '#6f42c1'
            });
            return false;
        }

        /* Loading button */
        const btn = form.querySelector('.btn-simpan');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menyimpan...`;

        return true;
    }
</script>
@endsection
