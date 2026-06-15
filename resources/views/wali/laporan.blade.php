@extends('layouts.app')

@section('title','Laporan')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/laporan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">

    <div class="container-dashboard">

        <!-- Judul -->
        <div class="page-title-card">
            <h5>Laporan</h5>
        </div>

        <!-- Informasi Anak -->
        <div class="student-card">

            <div class="student-avatar">
                <i class="fa-solid fa-user-graduate"></i>
            </div>

            <div class="student-info">
                <h6>Nabila Putri</h6>
                <span>Kelas 3A</span>
            </div>

        </div>

        <!-- Card Laporan -->
        <div class="report-card">

            <!-- Filter -->
            <div class="filter-section">

                <input type="date" class="filter-input">

                <select class="filter-select">
                    <option>Semua</option>
                    <option>Kehadiran</option>
                    <option>Penjemputan</option>
                </select>

                <button class="btn-search">
                    Cari
                </button>

                <button class="btn-refresh">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>

            </div>

            <!-- Table -->
            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Jenis Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Kehadiran Februari 2026.pdf</td>
                            <td>12-02-2026</td>
                            <td>Kehadiran</td>
                            <td>
                                <button class="btn-download">
                                    Unduh
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>Penjemputan Februari 2026.pdf</td>
                            <td>13-02-2026</td>
                            <td>Penjemputan</td>
                            <td>
                                <button class="btn-download">
                                    Unduh
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>Kehadiran Januari 2026.pdf</td>
                            <td>10-01-2026</td>
                            <td>Kehadiran</td>
                            <td>
                                <button class="btn-download">
                                    Unduh
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>Penjemputan Januari 2026.pdf</td>
                            <td>10-01-2026</td>
                            <td>Penjemputan</td>
                            <td>
                                <button class="btn-download">
                                    Unduh
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
