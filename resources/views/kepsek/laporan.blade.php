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
        <h5 class="mb-0">Laporan</h5>
    </div>

    <!-- TAB -->
    <div class="tab-wrapper mb-3">
        <button class="tab-btn active" onclick="showTab(event,'kehadiran')">Kehadiran</button>
        <button class="tab-btn" onclick="showTab(event,'penjemputan')">Penjemputan</button>
    </div>

    <!-- FILTER -->
    <div class="card-dashboard mb-3">
        <div class="filter-box">
            <input type="date" class="form-control form-control-sm">
            <select class="form-select form-select-sm">
                <option>Semua</option>
            </select>
        </div>
    </div>

    <!-- KEHADIRAN -->
    <div id="kehadiran" class="tab-content">
        <div class="card-dashboard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Jenis laporan</th>
                        <th>Aksi</th>
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
                </tbody>
            </table>
        </div>
    </div>

    <!-- PENJEMPUTAN -->
    <div id="penjemputan" class="tab-content" style="display:none;">
        <div class="card-dashboard">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Jenis laporan</th>
                        <th>Aksi</th>
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
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
function showTab(e, tab){
    document.getElementById('kehadiran').style.display = 'none';
    document.getElementById('penjemputan').style.display = 'none';

    document.getElementById(tab).style.display = 'block';

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    e.target.classList.add('active');
}
</script>
@endpush
