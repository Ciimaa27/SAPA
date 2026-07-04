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
        <div class="filter-box d-flex gap-3 align-items-end">

            <!-- FILTER BULAN -->
            <div>
                <label class="small text-muted d-block mb-1">
                    Bulan dan Tahun
                </label>

                <input
                    type="month"
                    id="filter-bulan"
                    class="form-control form-control-sm"
                    value="{{ date('Y-m') }}"
                >
            </div>

            <!-- FILTER TANGGAL -->
            <div>
                <label class="small text-muted d-block mb-1">
                    Tanggal
                </label>

                <input
                    type="date"
                    id="filter-tanggal"
                    class="form-control form-control-sm"
                    value="{{ date('Y-m-d') }}"
                >
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

                    <!-- JUDUL -->
                    <td>
                        <span id="nama-laporan">
                            Laporan({{ date('d-m-Y') }}).xlsx
                        </span>
                    </td>

                    <!-- KELAS -->
                    <td>
                        Semua Kelas
                    </td>

                    <!-- TANGGAL -->
                    <td id="tanggal-laporan">
                        {{ date('d-m-Y') }}
                    </td>

                    <!-- AKSI -->
                    <td class="col-aksi">

                        <a href="javascript:void(0)"
                           onclick="unduhExcelKepsek()"
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


@push('scripts')

<script>

    const filterTanggal = document.getElementById('filter-tanggal');

    filterTanggal.addEventListener('change', function () {

        if (!this.value) {
            return;
        }

        const bagianTanggal = this.value.split('-');

        const tanggalFormat =
            bagianTanggal[2] + '-' +
            bagianTanggal[1] + '-' +
            bagianTanggal[0];

        document.getElementById('nama-laporan').textContent =
            'Laporan(' + tanggalFormat + ').xlsx';

        document.getElementById('tanggal-laporan').textContent =
            tanggalFormat;
    });


    function unduhExcelKepsek() {

        const bulan =
            document.getElementById('filter-bulan').value;

        const tanggal =
            document.getElementById('filter-tanggal').value;


        if (!bulan) {

            alert(
                'Silakan tentukan bulan rekap laporan terlebih dahulu!'
            );

            return;
        }


        const baseUrl =
            "{{ url('/kepsek/laporan/download') }}";


        const downloadUrl =
            baseUrl +
            '/' +
            bulan +
            '?tanggal=' +
            tanggal;


        window.location.href = downloadUrl;
    }

</script>

@endpush
