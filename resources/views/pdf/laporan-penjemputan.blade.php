<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjemputan</title>

    <link rel="stylesheet" href="{{ public_path('css/wali/pdf-penjemputan.css') }}">
</head>
<body>

<div class="container">

    <!-- ===========================
            HEADER SEKOLAH
    ============================ -->
    <img src="{{ public_path('foto/sdit.png') }}" class="header-image">

    <div class="line"></div>

    <!-- ===========================
            JUDUL
    ============================ -->
    <div class="title">
        <h1>LAPORAN PENJEMPUTAN SISWA</h1>

        <p>
            Periode :
            <b>
                {{ \Carbon\Carbon::createFromDate($tahun,$bulan,1)->locale('id')->translatedFormat('F Y') }}
            </b>
        </p>
    </div>

    <div class="line"></div>

    <!-- ===========================
            IDENTITAS SISWA
    ============================ -->

    <table class="student-table">

        <tr>
            <td class="label">Nama Siswa</td>
            <td class="colon">:</td>
            <td><b>{{ $siswa->nama_siswa }}</b></td>

            <td class="label">Kelas</td>
            <td class="colon">:</td>
            <td><b>{{ $siswa->kelas->nama_kelas }}</b></td>
        </tr>

        <tr>
            <td class="label">NIS</td>
            <td class="colon">:</td>
            <td><b>{{ $siswa->nis }}</b></td>

            <td class="label">Nama Wali</td>
            <td class="colon">:</td>
            <td><b>{{ $wali->nama_wali }}</b></td>
        </tr>

    </table>

    <div class="line space-line"></div>

    <p class="description">
        Laporan ini berisi rekapitulasi data penjemputan siswa selama periode
        <b>{{ \Carbon\Carbon::createFromDate($tahun,$bulan,1)->locale('id')->translatedFormat('F Y') }}</b>.
    </p>

    <!-- ===========================
            RINGKASAN
    ============================ -->

    <table class="summary-table">
        <tr>

            <td class="card success">

                <div class="card-title">
                    Tepat Waktu
                </div>

                <div class="card-value">
                    {{ $tepat }}
                </div>

                <div class="card-desc">
                    Kali Penjemputan
                </div>

            </td>

            <td width="18"></td>

            <td class="card warning">

                <div class="card-title">
                    Terlambat
                </div>

                <div class="card-value">
                    {{ $terlambat }}
                </div>

                <div class="card-desc">
                    Kali Penjemputan
                </div>

            </td>

            <td width="18"></td>

            <td class="card danger">

                <div class="card-title">
                    Belum Dijemput
                </div>

                <div class="card-value">
                    {{ $belum }}
                </div>

                <div class="card-desc">
                    Kali Penjemputan
                </div>

            </td>

        </tr>
    </table>

    <div class="section-title">
        DETAIL PENJEMPUTAN
    </div>

    <table class="detail-table">

        <thead>

        <tr>

            <th width="8%">No</th>
            <th width="18%">Tanggal</th>
            <th>Jam Pulang</th>
            <th>Jam Dijemput</th>
            <th>Status</th>

        </tr>

        </thead>
        <tbody>

        @forelse($penjemputanDetails as $index => $item)

<tr>

    <td>{{ $index + 1 }}</td>

    <td>
        {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d M Y') }}
    </td>

    <td>{{ $item['jam_pulang'] }}</td>

    <td>{{ $item['jam_jemput'] }}</td>

    <td>{{ $item['nama_penjemput'] }}</td>

    <td>

        @if($item['status'] == 'Tepat Waktu')

            <span class="badge-success">
                Tepat Waktu
            </span>

        @elseif($item['status'] == 'Terlambat')

            <span class="badge-warning">
                Terlambat
            </span>

        @else

            <span class="badge-danger">
                {{ $item['status'] }}
            </span>

        @endif

    </td>

</tr>

@empty

<tr>
    <td colspan="6" style="text-align:center">
        Tidak ada data penjemputan.
    </td>
</tr>

@endforelse