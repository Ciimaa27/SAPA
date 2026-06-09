@extends('layouts.app')

@section('title','Laporan')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/laporan.css') }}">
@endpush

{{-- 🔥 CONTENT --}}
@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- Header --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Laporan</h5>
        </div>

        {{-- Filter Bulan --}}
        <div class="card p-3 mb-3">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <input
                    type="month"
                    name="bulan"
                    class="form-control w-auto"
                    value="{{ request('bulan', now()->format('Y-m')) }}">

                <button type="submit" class="btn btn-primary">
                    Terapkan
                </button>
            </form>
        </div>

<<<<<<< HEAD
        {{-- Tabel Laporan --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Bulan</th>
                            <th>Kehadiran</th>
                            <th>Penjemputan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($kelas as $kls)
                        <tr>
                            <td>{{ $kls->nama_kelas }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse(
                                    request('bulan', now()->format('Y-m'))
                                )->translatedFormat('F Y') }}
                            </td>

                            <td>
                                <a href="{{ route('laporan.kehadiran.export', [
                                    'id_kelas' => $kls->id_kelas,
                                    'bulan' => request('bulan', now()->format('Y-m'))
                                ]) }}"
                                class="btn-unduh">
                                    Export Excel
                                </a>
                            </td>

                            <td>
                                <a href="{{ route('laporan.penjemputan.export', [
                                    'id_kelas' => $kls->id_kelas,
                                    'bulan' => request('bulan', now()->format('Y-m'))
                                ]) }}"
                                class="btn-unduh">
                                    Export Excel
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Tidak ada data kelas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
=======
        <!-- ================= KEHADIRAN ================= -->
        <div id="kehadiran" class="tab-content">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                                <tr>
                                <th>Judul</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Jenis laporan</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kehadiran12022026.pdf</td>
                                <td>1-A</td>
                                <td>12-02-2026</td>
                                <td>Kehadiran</td>
                                <td><button class="btn-unduh">Unduh</button></td>
                            </tr>
                            <tr>
                                <td>Kehadiran13022026.pdf</td>
                                <td>1-B</td>
                                <td>13-02-2026</td>
                                <td>Kehadiran</td>
                                <td><button class="btn-unduh">Unduh</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= PENJEMPUTAN ================= -->
        <div id="penjemputan" class="tab-content" style="display:none;">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Jenis laporan</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Penjemputan12022026.pdf</td>
                                <td>1-A</td>
                                <td>12-02-2026</td>
                                <td>Penjemputan</td>
                                <td><button class="btn-unduh">Unduh</button></td>
                            </tr>
                            <tr>
                                <td>Penjemputan13022026.pdf</td>
                                <td>1-B</td>
                                <td>13-02-2026</td>
                                <td>Penjemputan</td>
                                <td><button class="btn-unduh">Unduh</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
>>>>>>> origin/main
            </div>
        </div>

    </div>
</div>

@endsection
