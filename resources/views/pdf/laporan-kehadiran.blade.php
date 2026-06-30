<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran</title>
    <link rel="stylesheet" href="{{ public_path('css/wali/pdf.css') }}">
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
        <h1>REKAP KEHADIRAN SISWA</h1>
        <p>
            Periode :
            {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y') }}
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

<p class="description">
    Berikut adalah rekap kehadiran siswa selama bulan
    <b>{{ strtoupper(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y')) }}</b>
</p>

    <!-- ======================================
        TABEL REKAP KEHADIRAN
======================================= -->
<table class="rekap-table">
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alpha</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ strtoupper(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y')) }}</td>
            <td>{{ $hadir }}</td>
            <td>{{ $izin }}</td>
            <td>{{ $sakit }}</td>
            <td>{{ $alpa }}</td>
        </tr>
    </tbody>
</table>
    <!-- ======================================
        KETERANGAN
======================================= -->
<div class="section-title">Keterangan</div>

<table class="keterangan-table">
    <tr>
        <td width="25%">Hadir</td>
        <td width="2%">:</td>
        <td>Siswa hadir mengikuti kegiatan belajar di sekolah.</td>
    </tr>
    <tr>
        <td>Izin</td>
        <td>:</td>
        <td>Siswa tidak hadir dengan surat atau izin dari orang tua.</td>
    </tr>
    <tr>
        <td>Sakit</td>
        <td>:</td>
        <td>Siswa tidak hadir karena sakit.</td>
    </tr>
    <tr>
        <td>Alpha</td>
        <td>:</td>
        <td>Siswa tidak hadir tanpa keterangan.</td>
    </tr>
</table>
<!-- ======================================
        CATATAN
======================================= -->
<div class="section-title">Catatan</div>

<div class="catatan">
    Laporan ini dibuat secara otomatis oleh
    <b>SAPA (Sistem Absensi dan Penjemputan Anak)</b>.
    Data yang ditampilkan merupakan hasil rekapitulasi
    kehadiran siswa selama periode yang dipilih.
</div>
<!-- ======================================
        PENUTUP
======================================= -->
<table class="footer-table">
    <tr>
            <td class="footer-left">
        Mengetahui,<br>
        Orang Tua / Wali
        <div class="signature-space"></div>
        <u><b>{{ $wali->nama_wali }}</b></u>
    </td>

    <td class="footer-right">
        Banjarmasin, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>
        Wali Kelas
        <div class="signature-space"></div>
        <u><b>....................................</b></u>
    </td>
    </tr>
</table>

<div class="print-info">
    Dokumen ini dicetak secara otomatis oleh
    <b>SAPA (Sistem Absensi dan Penjemputan Anak)</b><br>
    Dicetak pada : {{ now()->translatedFormat('d F Y H:i') }} WITA
</div>

</div>
</body>
</html>
