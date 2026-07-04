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

<div class="main-kepsek">

    <!-- TITLE -->
    <div class="card-dashboard mb-3">
        <h5 class="mb-0">Laporan</h5>
    </div>

    <!-- FILTER -->
    <div class="card-dashboard mb-3">
        <div class="filter-box">

            <!-- FILTER TANGGAL -->
            <input type="date"
                   class="form-control form-control-sm">

            <!-- FILTER KELAS -->
            <select class="form-select form-select-sm">
                <option value="">Semua Kelas</option>
                <option value="1-A">1-A</option>
                <option value="1-B">1-B</option>
                <option value="2-A">2-A</option>
            </select>

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
                    <td>
                        Laporan(12-02-2026).pdf
                    </td>

                    <td>
                        1-A
                    </td>

                    <td>
                        12-02-2026
                    </td>

                    <td class="col-aksi">
                        <a href="#"
                           class="btn-excel"
                           title="Download Laporan">

                            <i class="fa-solid fa-file-excel"></i>

                        </a>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection
