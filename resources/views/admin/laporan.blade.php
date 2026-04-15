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

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Laporan</h5>
        </div>

        <!-- TAB -->
        <div class="mb-3">
            <button class="btn-tab active" onclick="showTab('kehadiran')">Kehadiran</button>
            <button class="btn-tab" onclick="showTab('penjemputan')">Penjemputan</button>
        </div>

        <!-- FILTER -->
        <div class="card p-3 mb-3">
            <div class="d-flex gap-2">
                <input type="date" class="form-control w-auto">
                <select class="form-select w-auto">
                    <option>Semua</option>
                    <option>1-A</option>
                    <option>1-B</option>
                </select>
            </div>
        </div>

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
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT TAB -->
<script>
function showTab(tab){
    document.getElementById('kehadiran').style.display = 'none';
    document.getElementById('penjemputan').style.display = 'none';

    document.getElementById(tab).style.display = 'block';

    let buttons = document.querySelectorAll('.btn-tab');
    buttons.forEach(btn => btn.classList.remove('active'));

    event.target.classList.add('active');
}
</script>

@endsection
