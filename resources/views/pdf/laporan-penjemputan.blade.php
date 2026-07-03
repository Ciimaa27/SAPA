<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjemputan</title>
    <link rel="stylesheet" href="{{ public_path('css/wali/pdf-penjemputan.css') }}">
</head>

<body>

<div class="container">

    <!-- ======================================
            HEADER SEKOLAH
    ======================================= -->
    <img src="{{ public_path('foto/sdit.png') }}" class="header-image">

    <div class="line"></div>

    <!-- ======================================
            JUDUL
    ======================================= -->
    <div class="title">
        <h1>REKAP PENJEMPUTAN SISWA</h1>
        <p>
            Periode :
            {{ \Carbon\Carbon::createFromDate($tahun,$bulan,1)
                ->locale('id')
                ->translatedFormat('F Y') }}
        </p>
    </div>

    <div class="line"></div>

    <!-- ======================================
            IDENTITAS SISWA
    ======================================= -->
    <table class="student-table">
        <tr>
            <td class="label">Nama</td>
            <td class="colon">:</td>
            <td>{{ $siswa->nama_siswa }}</td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td class="colon">:</td>
            <td>{{ $siswa->nis }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="colon">:</td>
            <td>{{ $siswa->kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td class="label">Nama Wali</td>
            <td class="colon">:</td>
            <td>{{ $wali->nama_wali }}</td>
        </tr>
    </table>

    <div class="line space-line"></div>

    <!-- ======================================
            DESKRIPSI
    ======================================= -->
    <p class="description">
        Berikut adalah rekap penjemputan siswa selama bulan
        <b>
            {{ strtoupper(
                \Carbon\Carbon::createFromDate($tahun,$bulan,1)
                ->locale('id')
                ->translatedFormat('F Y')
            ) }}
        </b>
    </p>

    <!-- ======================================
            TABEL REKAP
    ======================================= -->
    <table class="rekap-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Penjemputan</th>
                <th>Tepat Waktu</th>
                <th>Terlambat</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ strtoupper(
                        \Carbon\Carbon::createFromDate($tahun,$bulan,1)
                        ->locale('id')
                        ->translatedFormat('F Y')
                    ) }}
                </td>
                <td>
                    {{ $penjemputanDetails->count() }}
                </td>
                <td>
                    {{ $tepat }}
                </td>
                <td>
                    {{ $terlambat }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- ======================================
            STATISTIK PENJEMPUTAN
    ======================================= -->
    <div class="section-title">Statistik Penjemputan</div>

    <table class="statistik-table">
        <tr>
            <td>Ayah</td>
            <td>
                {{ $penjemputanDetails->where('nama_penjemput','Ayah')->count() }}
            </td>
        </tr>
        <tr>
            <td>Ibu</td>
            <td>
                {{ $penjemputanDetails->where('nama_penjemput','Ibu')->count() }}
            </td>
        </tr>
        <tr>
            <td>Wali</td>
            <td>
                {{ $penjemputanDetails
                    ->whereNotIn('nama_penjemput',['Ayah','Ibu'])
                    ->count() }}
            </td>
        </tr>
    </table>

    <!-- ======================================
            KETERANGAN
    ======================================= -->
    <div class="section-title">
        Keterangan
    </div>

    <table class="keterangan-table">
        <tr>
            <td width="25%">
                Total Penjemputan
            </td>
            <td width="2%">:</td>
            <td>
                Total seluruh penjemputan siswa selama periode laporan.
            </td>
        </tr>
        <tr>
            <td>
                Tepat Waktu
            </td>
            <td>:</td>
            <td>
                Penjemputan dilakukan sesuai jadwal pulang sekolah.
            </td>
        </tr>
        <tr>
            <td>
                Terlambat
            </td>
            <td>:</td>
            <td>
                Penjemputan dilakukan melewati jadwal pulang sekolah.
            </td>
        </tr>
        <tr>
            <td>
                Belum Dijemput
            </td>
            <td>:</td>
            <td>
                Sampai laporan dibuat siswa belum dijemput.
            </td>
        </tr>
    </table>

    <!-- ======================================
            CATATAN
    ======================================= -->
    <div class="section-title">
        Catatan
    </div>

    <div class="catatan">
        Laporan ini dibuat secara otomatis oleh
        <b>SAPA (Sistem Absensi dan Penjemputan Anak)</b>.
        Data yang ditampilkan merupakan hasil rekapitulasi
        penjemputan siswa selama periode yang dipilih.
    </div>

    <!-- ======================================
            PENUTUP
    ======================================= -->
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                Mengetahui,
                <br>
                Orang Tua / Wali
                <div class="signature-space"></div>
                <u>
                    <b>
                        {{ $wali->nama_wali }}
                    </b>
                </u>
            </td>
            <td class="footer-right">
                Banjarmasin,
                {{ now()->locale('id')->translatedFormat('d F Y') }}
                <br>
                Wali Kelas
                <div class="signature-space"></div>
                <u>
                    <b>
                        ....................................
                    </b>
                </u>
            </td>
        </tr>
    </table>

    <!-- ======================================
            INFO CETAK
    ======================================= -->
    <div class="print-info">
        Dokumen ini dicetak secara otomatis oleh
        <b>
            SAPA (Sistem Absensi dan Penjemputan Anak)
        </b>
        <br>
        Dicetak pada :
        {{ now()->translatedFormat('d F Y H:i') }}
        WITA
    </div>

</div>

</body>

</html>
