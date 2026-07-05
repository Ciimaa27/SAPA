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
            Kembali
        </a>

        <div class="info-wrapper">

            <div class="info-row">
                <label>Kelas</label>
                <span>:</span>
                <input
                    type="text"
                    value="{{ $kelas->nama_kelas }}"
                    readonly
                >
            </div>

            <div class="info-row">
                <label>Wali kelas</label>
                <span>:</span>
                <input
                    type="text"
                    value="{{ $kelas->guru ? $kelas->guru->nama_guru : 'N/A' }}"
                    readonly
                >
            </div>

            <div class="info-row">
                <label>Tanggal</label>
                <span>:</span>
                <input
                    type="text"
                    value="{{ $today->format('d-m-Y') }}"
                    readonly
                >
            </div>

        </div>

    </div>


    <!-- TABEL -->
    <div class="card-box mt-3">

        <!-- TOMBOL SIMPAN -->
        @if($siswas->count() > 0)
            <div class="d-flex justify-content-end mb-3">

                <button
                    type="submit"
                    form="form-status-{{ $siswas->first()->id_siswa }}"
                    class="btn btn-success btn-sm btn-simpan-status"
                >
                    Simpan
                </button>

            </div>
        @endif


        <!-- TABLE CONTAINER -->
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

                            <td>
                                {{ $row->nis }}
                            </td>

                            <td>
                                {{ $row->nama_siswa }}
                            </td>

                            <td>

                                <form
                                    action="{{ route('guru.penjemputan.update-status') }}"
                                    method="POST"
                                    id="form-status-{{ $row->id_siswa }}"
                                    class="form-status"
                                    onsubmit="return simpanStatus(this)"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="id_siswa"
                                        value="{{ $row->id_siswa }}"
                                    >

                                    <input
                                        type="hidden"
                                        name="tanggal"
                                        value="{{ $today->format('Y-m-d') }}"
                                    >


                                    <select
                                        name="status"
                                        class="status-select"
                                    >

                                        <option
                                            value="Menunggu"
                                            {{ $row->status == 'Menunggu' ? 'selected' : '' }}
                                        >
                                            Menunggu
                                        </option>

                                        <option
                                            value="Dijemput"
                                            {{ $row->status == 'Dijemput' ? 'selected' : '' }}
                                        >
                                            Dijemput
                                        </option>

                                    </select>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="3"
                                class="text-center text-muted"
                            >
                                Tidak ada siswa dalam kelas ini
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- PAGINATION -->
        <div class="p-3 d-flex justify-content-end">

            <nav>

                <ul class="pagination mb-0">

                    <!-- PREVIOUS -->
                    @if ($siswas->onFirstPage())

                        <li class="page-item disabled">
                            <span class="page-link">‹</span>
                        </li>

                    @else

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="{{ $siswas->previousPageUrl() }}"
                            >
                                ‹
                            </a>
                        </li>

                    @endif


                    @php
                        $current = $siswas->currentPage();
                        $last = $siswas->lastPage();
                    @endphp


                    <!-- FIRST PAGE -->
                    @if ($current > 3)

                        <li class="page-item">
                            <a
                                class="page-link"
                                href="{{ $siswas->url(1) }}"
                            >
                                1
                            </a>
                        </li>


                        @if ($current > 4)

                            <li class="page-item disabled">
                                <span class="page-link">
                                    ...
                                </span>
                            </li>

                        @endif

                    @endif


                    <!-- MIDDLE PAGES -->
                    @for (
                        $i = max(1, $current - 1);
                        $i <= min($last, $current + 1);
                        $i++
                    )

                        <li class="page-item {{ $i == $current ? 'active' : '' }}">

                            <a
                                class="page-link"
                                href="{{ $siswas->url($i) }}"
                            >
                                {{ $i }}
                            </a>

                        </li>

                    @endfor


                    <!-- LAST PAGE -->
                    @if ($current < $last - 2)

                        @if ($current < $last - 3)

                            <li class="page-item disabled">
                                <span class="page-link">
                                    ...
                                </span>
                            </li>

                        @endif


                        <li class="page-item">

                            <a
                                class="page-link"
                                href="{{ $siswas->url($last) }}"
                            >
                                {{ $last }}
                            </a>

                        </li>

                    @endif


                    <!-- NEXT -->
                    @if ($siswas->hasMorePages())

                        <li class="page-item">

                            <a
                                class="page-link"
                                href="{{ $siswas->nextPageUrl() }}"
                            >
                                ›
                            </a>

                        </li>

                    @else

                        <li class="page-item disabled">
                            <span class="page-link">
                                ›
                            </span>
                        </li>

                    @endif

                </ul>

            </nav>

        </div>
        <!-- END PAGINATION -->

    </div>
    <!-- END CARD TABLE -->

</div>
<!-- END MAIN DASHBOARD -->


<script>
    function simpanStatus(form) {

        const btn = document.querySelector('.btn-simpan-status');

        if (btn) {

            btn.disabled = true;

            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';

        }

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
