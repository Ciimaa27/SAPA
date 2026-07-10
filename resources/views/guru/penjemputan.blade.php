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
    <div class="card-box">
        <h5 class="page-title">Daftar Penjemputan Siswa</h5>
    </div>

    <div class="card-box mt-3">
        <a href="{{ route('guru.data-penjemputan') }}" class="btn-kembali">
            <i class="fas fa-arrow-left"></i> Kembali
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

    <div class="card-box mt-3">
         {{-- CATATAN --}}
        <div class="alert alert-warning py-2 px-3 mb-3 small" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            <strong>Catatan:</strong>
            Simpan perubahan setiap siswa satu per satu sebelum melanjutkan ke siswa berikutnya.
        </div>
        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Status</th>
                        <th>Penjemput</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $row)
                        @php
                            $penjemputSiswa = $daftarPenjemput->get($row->id_siswa, collect());
                        @endphp
                        <tr>
                            <td>{{ $row->nis }}</td>
                            <td>{{ $row->nama_siswa }}</td>
                            <td>
                                <form action="{{ route('guru.penjemputan.update-status') }}" method="POST" class="form-status" onsubmit="return simpanStatus(this)">
                                    @csrf
                                    <input type="hidden" name="id_siswa" value="{{ $row->id_siswa }}">
                                    <input type="hidden" name="tanggal" value="{{ $today->format('Y-m-d') }}">

                                    <select name="status" class="status-select" onchange="ubahStatus(this)">
                                        <option value="Menunggu" {{ ($row->status ?? 'Menunggu') === 'Menunggu' ? 'selected' : '' }}>
                                            Menunggu
                                        </option>
                                        <option value="Dijemput" {{ $row->status === 'Dijemput' ? 'selected' : '' }}>
                                            Dijemput
                                        </option>
                                    </select>
                            </td>
                            <td>
                                <select name="id_wali" class="penjemput-select" {{ $row->status === 'Dijemput' ? '' : 'disabled' }}>
                                    <option value="">Pilih Penjemput</option>
                                    @foreach($penjemputSiswa as $penjemput)
                                        <option value="{{ $penjemput->id_wali }}" {{ $row->id_wali == $penjemput->id_wali ? 'selected' : '' }}>
                                            {{ ucfirst($penjemput->hubungan) }} - {{ $penjemput->nama_wali }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn-simpan">
                                     Simpan
                                </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada siswa dalam kelas ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswas->hasPages())
        <div class="p-3 d-flex justify-content-end">
            <nav>
                <ul class="pagination mb-0">
                    @if ($siswas->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">‹</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $siswas->previousPageUrl() }}">‹</a></li>
                    @endif

                    @php
                        $current = $siswas->currentPage();
                        $last = $siswas->lastPage();
                    @endphp

                    @if ($current > 3)
                        <li class="page-item"><a class="page-link" href="{{ $siswas->url(1) }}">1</a></li>
                        @if ($current > 4)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif

                    @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                        <li class="page-item {{ $i == $current ? 'active' : '' }}">
                            <a class="page-link" href="{{ $siswas->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($current < $last - 2)
                        @if ($current < $last - 3)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item"><a class="page-link" href="{{ $siswas->url($last) }}">{{ $last }}</a></li>
                    @endif

                    @if ($siswas->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $siswas->nextPageUrl() }}">›</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">›</span></li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<script>
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

    function simpanStatus(form) {
        const status = form.querySelector('.status-select');
        const penjemput = form.querySelector('.penjemput-select');

        if (status.value === 'Dijemput' && penjemput.value === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Penjemput',
                text: 'Silakan pilih siapa yang menjemput siswa.',
                confirmButtonColor: '#6f42c1',
                confirmButtonText: 'OK'
            });
            return false;
        }

        const btn = form.querySelector('.btn-simpan');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menyimpan...`;
        }
        return true;
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
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
        text: @json(session('error')),
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'OK'
    });
</script>
@endif
@endsection
