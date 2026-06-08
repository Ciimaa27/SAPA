@extends('layouts.app')

@section('title', 'Daftar Penjemputan Siswa')

@section('sidebar')
@include('layouts.sidebar-guru')
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('css/guru/daftar.css') }}">
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
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
        <i class="fa fa-angle-left"></i>
        Kembali
    </a>

    <div class="info-wrapper">

        <div class="info-row">
            <label>Kelas</label>
            <span>:</span>
            <input type="text" value="1-A" readonly>
        </div>

        <div class="info-row">
            <label>Wali kelas</label>
            <span>:</span>
            <input type="text" value="Arif Nasution" readonly>
        </div>

        <div class="info-row">
            <label>Tanggal</label>
            <span>:</span>
            <input type="text" value="12-02-2026" readonly>
        </div>

    </div>

</div>

<!-- TABEL -->
<div class="card-box mt-3">

    <div class="table-header">
        <a href="#" class="btn-laporan">
            <i class="fa fa-download"></i>
            Laporan
        </a>
    </div>

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

                <tr>
                    <td>00987643</td>
                    <td>Arif Nasution</td>
                    <td>
                        <span class="badge-purple">
                            Dijemput
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>00985651</td>
                    <td>Radita Nabila</td>
                    <td>
                        <span class="badge-purple">
                            Dijemput
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>00952763</td>
                    <td>Arif Rahman</td>
                    <td>
                        <span class="badge-orange">
                            Menunggu
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>00936121</td>
                    <td>Ismatul Hawa</td>
                    <td>
                        <span class="badge-purple">
                            Dijemput
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>00864041</td>
                    <td>Ilham Basudara</td>
                    <td>
                        <span class="badge-purple">
                            Dijemput
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>00855298</td>
                    <td>Indah Permatasari</td>
                    <td>
                        <span class="badge-purple">
                            Dijemput
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>00839844</td>
                    <td>Noor Maulida</td>
                    <td>
                        <span class="badge-orange">
                            Menunggu
                        </span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>
</div>

@endsection
