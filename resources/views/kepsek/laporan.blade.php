@extends('layouts.app')

@section('title', 'Laporan')

@section('sidebar')
    @include('layouts.sidebar-kepsek')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kepsek/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kepsek/laporan.css') }}">
@endpush

@section('content')
@php
    $namaBulanIndonesia = [
        1  => 'Januari', 2  => 'Februari', 3  => 'Maret', 4  => 'April', 5  => 'Mei', 6  => 'Juni',
        7  => 'Juli', 8  => 'Agustus', 9  => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $tanggalIndonesia = date('j') . ' ' . $namaBulanIndonesia[(int) date('n')] . ' ' . date('Y');
@endphp

<div class="main-kepsek">

    <!-- TITLE -->
    <div class="card-dashboard mb-3">
        <h5 class="mb-0">Laporan</h5>
    </div>

    <!-- FILTER TANGGAL -->
    <div class="card-dashboard mb-3">
        <div class="filter-box">
            <div class="filter-tanggal">
                <label class="small text-muted d-block mb-1">Tanggal</label>
                <input type="date" id="filter-tanggal" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>

    <!-- TABEL LAPORAN -->
    <div class="card-dashboard">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th class="col-aksi">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span id="nama-laporan">Laporan({{ $tanggalIndonesia }}).xlsx</span></td>
                    <td>Semua Kelas</td>
                    <td id="tanggal-laporan">{{ $tanggalIndonesia }}</td>
                    <td class="col-aksi">
                        <a href="javascript:void(0)" onclick="unduhExcelKepsek()" class="btn-excel" title="Download Laporan">
                            <i class="fa-solid fa-file-excel"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterTanggal = document.getElementById('filter-tanggal');
    const namaBulanIndonesia = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function formatTanggalIndonesia(tanggal) {
        if (!tanggal) return '';
        const bagianTanggal = tanggal.split('-');
        const tahun = bagianTanggal[0];
        const bulan = parseInt(bagianTanggal[1], 10) - 1;
        const hari = parseInt(bagianTanggal[2], 10);

        return hari + ' ' + namaBulanIndonesia[bulan] + ' ' + tahun;
    }

    filterTanggal.addEventListener('change', function () {
        if (!this.value) return;

        const tanggalIndonesia = formatTanggalIndonesia(this.value);
        document.getElementById('nama-laporan').textContent = 'Laporan(' + tanggalIndonesia + ').xlsx';
        document.getElementById('tanggal-laporan').textContent = tanggalIndonesia;
    });
});

function unduhExcelKepsek() {
    const tanggal = document.getElementById('filter-tanggal').value;

    if (!tanggal) {
        alert('Silakan pilih tanggal laporan terlebih dahulu!');
        return;
    }

    const bulan = tanggal.substring(0, 7);
    const baseUrl = "{{ url('/kepsek/laporan/download') }}";
    const downloadUrl = baseUrl + '/' + bulan + '?tanggal=' + encodeURIComponent(tanggal);

    window.location.href = downloadUrl;
}
</script>
@endpush
