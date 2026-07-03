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
        {{-- Judul --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Status Penjemputan</h5>
        </div>

        {{-- Tombol Kembali --}}
        <div class="mb-3">
            <a href="{{ route('data-penjemputan') }}" class="btn btn-kembali">
                <i class="fa-solid fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- Informasi --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->nama_kelas }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Wali Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->nama_guru }}" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" class="form-control" value="{{ $tanggal }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Table --}}
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
                                <th width="220">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item->nis }}
                                    </td>
                                    <td class="text-start">
                                        {{ $item->nama_siswa }}
                                    </td>
                                    <td>
                                        <form action="{{ route('data-penjemputan.update-status') }}" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="id_siswa" value="{{ $item->id_siswa }}">
                                            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                                            <select name="status" class="form-select form-select-sm status-select">
                                                <option value="Menunggu" {{ $item->status == 'Menunggu' ? 'selected' : '' }}>
                                                    Menunggu
                                                </option>
                                                <option value="Dijemput" {{ $item->status == 'Dijemput' ? 'selected' : '' }}>
                                                    Dijemput
                                                </option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm btn-simpan">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        Belum ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
            text: '{{ session('error') }}',
            confirmButtonColor: '#dc3545'
        });
    </script>
@endif

<script>
    function simpanStatus(form) {
        const btn = form.querySelector(".btn-simpan");
        btn.disabled = true;
        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            Menyimpan...
        `;
        return true;
    }
</script>
@endsection
