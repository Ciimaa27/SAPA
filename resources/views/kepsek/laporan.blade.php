@extends('layouts.app')

@section('title','Laporan')

@section('sidebar')
    @include('layouts.sidebar-kepsek')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kepsek/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/kepsek/laporan.css') }}">
@endpush

@section('content')
<div class="main-kepsek">

    <!-- TITLE -->
    <div class="card-dashboard mb-3">
        <h5 class="mb-0">Laporan Ekspor Master SAPA</h5>
    </div>

    <!-- TAB -->
    <div class="tab-wrapper mb-3">
        <button class="tab-btn active" onclick="showTab(event,'kehadiran')">Kehadiran</button>
        <button class="tab-btn" onclick="showTab(event,'penjemputan')">Penjemputan</button>
    </div>

    <!-- FILTER -->
    <div class="card-dashboard mb-3">
        <div class="filter-box d-flex gap-3 align-items-center">
            <div>
                <label class="small text-muted d-block mb-1">Pilih Bulan & Tahun (Rekap Bulanan):</label>
                <input type="month" id="filter-bulan" class="form-control form-control-sm" value="{{ date('Y-m') }}">
            </div>
            <div>
                <label class="small text-muted d-block mb-1">Pilih Tanggal Log Spesifik (Harian / IoT):</label>
                <input type="date" id="filter-tanggal" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
            </div>
        </div>
    </div>

    <!-- KEHADIRAN TAB CONTENT -->
    <div id="kehadiran" class="tab-content">
        <div class="card-dashboard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Judul File</th>
                        <th>Kelas</th>
                        <th>Format Evaluasi</th>
                        <th>Jenis Laporan</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   <tr>
                        <td>
                            <i class="fa-regular fa-file-excel text-success me-2 fs-5"></i>
                            <strong>Laporan_Kepsek_SAPA_Final.xlsx</strong>
                        </td>
                        <td>Semua Kelas</td>
                        <td>Akumulasi Bulanan</td>
                        <td>Multi-Sheet Dashboard & Rekap Kehadiran</td>
                        <td class="col-aksi">
                            <a href="javascript:void(0)" onclick="unduhExcelKepsek()" class="btn-excel text-success fs-4" title="Unduh Excel">
                                <i class="fa-solid fa-file-excel"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PENJEMPUTAN TAB CONTENT -->
    <div id="penjemputan" class="tab-content" style="display:none;">
        <div class="card-dashboard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Judul File</th>
                        <th>Kelas</th>
                        <th>Format Evaluasi</th>
                        <th>Jenis Laporan</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <i class="fa-regular fa-file-excel text-success me-2 fs-5"></i>
                            <strong>Laporan_Kepsek_SAPA_Final.xlsx</strong>
                        </td>
                        <td>Semua Kelas</td>
                        <td>Harian / Bulanan</td>
                        <td>Multi-Sheet Log Penjemputan & Aktivitas Engine IoT</td>
                        <td class="col-aksi">
                            <a href="javascript:void(0)" onclick="unduhExcelKepsek()" class="btn-excel text-success fs-4" title="Unduh Excel">
                                <i class="fa-solid fa-file-excel"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fungsi untuk manajemen perpindahan tab
function showTab(e, tab){
    document.getElementById('kehadiran').style.display = 'none';
    document.getElementById('penjemputan').style.display = 'none';
    document.getElementById(tab).style.display = 'block';

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    e.target.classList.add('active');
}

// Fungsi eksekusi download Excel
function unduhExcelKepsek() {
    let bulan = document.getElementById('filter-bulan').value;
    let tanggal = document.getElementById('filter-tanggal').value;
    
    if (!bulan) {
        alert('Silakan tentukan bulan rekap laporan terlebih dahulu!');
        return;
    }

    // Mengarahkan ke route internal prefix /kepsek/laporan/download/{bulan}
    // Ditambahkan query parameter ?tanggal=YYYY-MM-DD secara aman
    let baseUrl = "{{ url('/kepsek/laporan/download') }}";
    let downloadUrl = baseUrl + '/' + bulan + '?tanggal=' + tanggal;

    // Trigger unduhan file di web browser pembuka
    window.location.href = downloadUrl;
}
</script>
@endpush