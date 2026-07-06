@extends('layouts.app')

@section('title', 'Laporan')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/laporan.css') }}">
@endpush

@section('content')

@php
    $namaBulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $bulanAktif = (int) \Carbon\Carbon::parse($bulan)->format('m');
    $tahunAktif = (int) \Carbon\Carbon::parse($bulan)->format('Y');
@endphp

<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- ================= JUDUL ================= --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Laporan</h5>
        </div>

        {{-- ================= FILTER ================= --}}
        <div class="card p-3 mb-3">
            <form method="GET" class="filter-laporan" id="formFilter">

                {{-- FILTER KELAS --}}
                <select name="kelas" class="form-select filter-kelas">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasOptions as $option)
                        <option value="{{ $option->id_kelas }}" {{ isset($kelasFilter) && $kelasFilter == $option->id_kelas ? 'selected' : '' }}>
                            {{ $option->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                {{-- ================= PILIH BULAN ================= --}}
                <div class="month-picker">
                    <button type="button" class="month-picker-button" id="monthPickerButton">
                        <span id="monthPickerText">
                            {{ $namaBulan[$bulanAktif] }} {{ $tahunAktif }}
                        </span>
                        <i class="fa-regular fa-calendar"></i>
                    </button>

                    {{-- POPUP --}}
                    <div class="month-picker-popup" id="monthPickerPopup">

                        {{-- HEADER TAHUN --}}
                        <div class="month-picker-header">
                            <button type="button" class="year-button" id="prevYear">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <span id="pickerYear">{{ $tahunAktif }}</span>
                            <button type="button" class="year-button" id="nextYear">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>

                        {{-- DAFTAR BULAN --}}
                        <div class="month-grid">
                            @foreach($namaBulan as $nomor => $nama)
                                <button type="button" class="month-item {{ $nomor == $bulanAktif ? 'active' : '' }}" data-month="{{ str_pad($nomor, 2, '0', STR_PAD_LEFT) }}">
                                    {{ $nama }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- VALUE YANG DIKIRIM KE CONTROLLER --}}
                <input type="hidden" name="bulan" id="bulanInput" value="{{ $bulan }}">

                {{-- BUTTON TERAPKAN --}}
                <button type="submit" class="btn btn-primary btn-terapkan">
                    Terapkan
                </button>

            </form>
        </div>

        {{-- ================= TABEL ================= --}}
        <div class="card">
            <div class="table-responsive table-container">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Bulan</th>
                            <th>Total Kehadiran</th>
                            <th>Total Penjemputan</th>
                            <th>Aksi Kehadiran</th>
                            <th>Aksi Penjemputan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $kls)
                        <tr>
                            {{-- KELAS --}}
                            <td>{{ $kls->nama_kelas }}</td>

                            {{-- BULAN --}}
                            <td>
                                {{ \Carbon\Carbon::parse($bulan)->locale('id')->translatedFormat('F Y') }}
                            </td>

                            {{-- TOTAL KEHADIRAN --}}
                            <td>
                                {{ $kehadiranCounts->get($kls->id_kelas, 0) }}
                            </td>

                            {{-- TOTAL PENJEMPUTAN --}}
                            <td>
                                {{ $penjemputanCounts->get($kls->id_kelas, 0) }}
                            </td>

                            {{-- EXPORT KEHADIRAN --}}
                            <td>
                                <a href="{{ route('laporan.kehadiran.export', ['id_kelas' => $kls->id_kelas, 'bulan' => $bulan]) }}" class="btn-excel" title="Export Kehadiran">
                                    <i class="fa-solid fa-file-excel"></i>
                                </a>
                            </td>

                            {{-- EXPORT PENJEMPUTAN --}}
                            <td>
                                <a href="{{ route('laporan.penjemputan.export', ['id_kelas' => $kls->id_kelas, 'bulan' => $bulan]) }}" class="btn-excel" title="Export Penjemputan">
                                    <i class="fa-solid fa-file-excel"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data kelas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ================= PAGINATION ================= --}}
            @if ($kelas->hasPages())
            <div class="p-3 d-flex justify-content-end">
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- PREVIOUS --}}
                        @if ($kelas->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $kelas->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        @php
                            $current = $kelas->currentPage();
                            $last = $kelas->lastPage();
                        @endphp

                        {{-- FIRST PAGE --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $kelas->url(1) }}">1</a>
                            </li>
                            @if ($current > 4)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        {{-- MIDDLE PAGE --}}
                        @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $kelas->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- LAST PAGE --}}
                        @if ($current < $last - 2)
                            @if ($current < $last - 3)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $kelas->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- NEXT --}}
                        @if ($kelas->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $kelas->nextPageUrl() }}">›</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">›</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ================= SCRIPT MONTH PICKER ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('monthPickerButton');
    const popup = document.getElementById('monthPickerPopup');
    const text = document.getElementById('monthPickerText');
    const input = document.getElementById('bulanInput');
    const yearText = document.getElementById('pickerYear');
    const prevYear = document.getElementById('prevYear');
    const nextYear = document.getElementById('nextYear');
    const monthItems = document.querySelectorAll('.month-item');

    let selectedYear = {{ $tahunAktif }};
    let selectedMonth = '{{ str_pad($bulanAktif, 2, '0', STR_PAD_LEFT) }}';

    const monthNames = {
        '01': 'Januari',
        '02': 'Februari',
        '03': 'Maret',
        '04': 'April',
        '05': 'Mei',
        '06': 'Juni',
        '07': 'Juli',
        '08': 'Agustus',
        '09': 'September',
        '10': 'Oktober',
        '11': 'November',
        '12': 'Desember'
    };

    button.addEventListener('click', function (event) {
        event.stopPropagation();
        popup.classList.toggle('show');
    });

    prevYear.addEventListener('click', function () {
        selectedYear--;
        yearText.textContent = selectedYear;
        updateActiveMonth();
    });

    nextYear.addEventListener('click', function () {
        selectedYear++;
        yearText.textContent = selectedYear;
        updateActiveMonth();
    });

    monthItems.forEach(function (item) {
        item.addEventListener('click', function () {
            selectedMonth = this.dataset.month;
            input.value = selectedYear + '-' + selectedMonth;
            text.textContent = monthNames[selectedMonth] + ' ' + selectedYear;

            monthItems.forEach(function (month) {
                month.classList.remove('active');
            });

            this.classList.add('active');
            popup.classList.remove('show');
        });
    });

    function updateActiveMonth() {
        monthItems.forEach(function (item) {
            item.classList.remove('active');
            if (selectedYear == {{ $tahunAktif }} && item.dataset.month == selectedMonth) {
                item.classList.add('active');
            }
        });
    }

    document.addEventListener('click', function (event) {
        if (!popup.contains(event.target) && !button.contains(event.target)) {
            popup.classList.remove('show');
        }
    });
});
</script>

@endsection
