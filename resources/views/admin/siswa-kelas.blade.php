@extends('layouts.app')

@section('title', 'Data siswa kelas')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/siswa-kelas.css') }}">
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data siswa kelas</h5>
        </div>

        <!-- TOMBOL KEMBALI -->
        <a href="{{ route('kelas') }}" class="btn btn-kembali mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- INFORMASI KELAS -->
        <div class="card mb-3 p-4">
            <div class="info-kelas">
                <div class="info-row">
                    <label>Kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $kelas->nama_kelas ?? '-' }}" readonly>
                </div>

                <div class="info-row">
                    <label>Wali kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $kelas->nama_guru ?? '-' }}" readonly>
                </div>

                <div class="info-row">
                    <label>Tanggal</label>
                    <span>:</span>
                    <form method="GET" action="{{ route('siswa-kelas', $kelas->id_kelas) }}" style="margin: 0;">
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
                    </form>
                </div>

                <div class="info-row">
                    <label>Jumlah siswa</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $siswa->count() }}" readonly>
                </div>
            </div>
        </div>

        <!-- TABEL KEHADIRAN -->
        <div class="card">
            <form method="POST" action="{{ route('update-kehadiran-kelas', $kelas->id_kelas) }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama lengkap</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $row)
                                @php
                                    $statusValue = $row->status_hadir ? strtolower(trim($row->status_hadir)) : '';
                                @endphp
                                <tr>
                                    <!-- NO -->
                                    <td>{{ $loop->iteration }}</td>

                                    <!-- NIS -->
                                    <td>{{ $row->nis ?? '-' }}</td>

                                    <!-- NAMA SISWA -->
                                    <td>{{ $row->nama_siswa ?? '-' }}</td>

                                    <!-- STATUS KEHADIRAN -->
                                    <td>
                                        <div class="status-group" data-siswa-id="{{ $row->id_siswa }}">
                                            <!-- HADIR -->
                                            <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'hadir' ? 'active' : '' }}" data-status="hadir" title="Hadir">H</button>
                                            <!-- IZIN -->
                                            <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'izin' ? 'active' : '' }}" data-status="izin" title="Izin">I</button>
                                            <!-- SAKIT -->
                                            <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'sakit' ? 'active' : '' }}" data-status="sakit" title="Sakit">S</button>
                                            <!-- ALPA -->
                                            <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'alpa' ? 'active' : '' }}" data-status="alpa" title="Alpa">A</button>

                                            <!-- NILAI STATUS -->
                                            <input type="hidden" name="status[{{ $row->id_siswa }}]" class="status-input" value="{{ $statusValue }}">
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada siswa di kelas ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="card-footer-absen">
                    <div class="form-action">
                        <a href="{{ route('kelas') }}" class="btn-batal">Batal</a>
                        <button type="submit" class="btn-simpan">Simpan</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- =====================================
     MODAL BOOTSTRAP SUCCESS
===================================== --}}
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-circle-check text-success me-2"></i> Berhasil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ session('success') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- SCRIPT STATUS KEHADIRAN -->
<script>
document.querySelectorAll('.status-group').forEach(group => {
    const buttons = group.querySelectorAll('.status-btn');
    const statusInput = group.querySelector('.status-input');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {

            // Jika tombol yang sama diklik lagi
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                statusInput.value = ''; // kosongkan status
                return;
            }

            // Hilangkan active dari semua tombol
            buttons.forEach(button => button.classList.remove('active'));

            // Aktifkan tombol yang dipilih
            this.classList.add('active');

            // Simpan status
            statusInput.value = this.dataset.status;
        });
    });
});
</script>

{{-- SCRIPT MODAL SUCCESS --}}
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('successModal');
    const successModal = new bootstrap.Modal(modalElement);
    successModal.show();
});
</script>
@endif
@endsection
