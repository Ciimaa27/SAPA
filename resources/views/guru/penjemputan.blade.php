@extends('layouts.app')

@section('title', 'Daftar Penjemputan Siswa')

@section('sidebar')
@include('layouts.sidebar-guru')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/guru/daftar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

<div class="main-dashboard">
<!-- JUDUL -->
<div class="card-box">
    <h5 class="page-title">
        Daftar Penjemputan siswa
    </h5>
</div>

<!-- INFORMASI -->
<div class="card-box mt-3">

<a href="{{ route('guru.data-penjemputan') }}" class="btn-kembali">
    <i class="fas fa-arrow-left"></i>
    <span>Kembali</span>
</a>

    <div class="info-wrapper">

        <div class="info-row">
            <label>Kelas</label>
            <span>:</span>
            <input type="text" value="{{ $kelas->nama_kelas }}" readonly>
        </div>

        <div class="info-row">
            <label>Wali kelas</label>
            <span>:</span>
            <input type="text" value="{{ $kelas->guru ? $kelas->guru->nama_guru : 'N/A' }}" readonly>
        </div>

        <div class="info-row">
            <label>Tanggal</label>
            <span>:</span>
            <input type="text" value="{{ $today->format('d-m-Y') }}" readonly>
        </div>

    </div>

</div>

<!-- TABEL -->
<div class="card-box mt-3">

    <div class="table-container">

        <table class="table-custom">

            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama lengkap</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($siswas as $row)
                <tr>
                    <td>{{ $row->nis }}</td>
                    <td>{{ $row->nama_siswa }}</td>
                    <td>
                        <form action="{{ route('guru.penjemputan.update-status') }}"
                            method="POST"
                            class="form-status"
                            onsubmit="return simpanStatus(this)">

                            @csrf

                            <input type="hidden"
                                name="id_siswa"
                                value="{{ $row->id_siswa }}">

                            <input type="hidden"
                                name="tanggal"
                                value="{{ $today->format('Y-m-d') }}">

                            <select name="status" class="status-select">
                                <option value="Menunggu"
                                    {{ $row->status == 'Menunggu' ? 'selected' : '' }}>
                                    Menunggu
                                </option>

                                <option value="Dijemput"
                                    {{ $row->status == 'Dijemput' ? 'selected' : '' }}>
                                    Dijemput
                                </option>
                            </select>

                            <button type="submit" class="btn-simpan-status">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan
                            </button>

                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Tidak ada siswa dalam kelas ini</td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
<div class="pagination-wrapper">

    {{-- PREVIOUS --}}
    @if ($siswas->onFirstPage())
        <span class="pagination-btn disabled">‹</span>
    @else
        <a href="{{ $siswas->previousPageUrl() }}"
           class="pagination-btn">
            ‹
        </a>
    @endif


    {{-- NOMOR HALAMAN --}}
    @for ($i = 1; $i <= $siswas->lastPage(); $i++)

        @if ($i == $siswas->currentPage())
            <span class="pagination-btn active">
                {{ $i }}
            </span>
        @else
            <a href="{{ $siswas->url($i) }}"
               class="pagination-btn">
                {{ $i }}
            </a>
        @endif

    @endfor


    {{-- NEXT --}}
    @if ($siswas->hasMorePages())
        <a href="{{ $siswas->nextPageUrl() }}"
           class="pagination-btn">
            ›
        </a>
    @else
        <span class="pagination-btn disabled">›</span>
    @endif

</div>

</div>
<script>
    function simpanStatus(form) {
        const btn = form.querySelector('.btn-simpan-status');

        btn.disabled = true;

        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            Menyimpan...
        `;

        return true;
    }
</script>


@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('success') }}',
        confirmButtonColor: '#6f42c1',
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
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'OK'
    });
</script>
@endif

@endsection
