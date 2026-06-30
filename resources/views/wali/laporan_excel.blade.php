<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran Siswa</title>

    <style>
        body{
            font-family: Calibri, Arial, sans-serif;
            font-size: 11pt;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td,th{
            padding:6px;
        }

        .title{
            font-size:18pt;
            font-weight:bold;
            text-align:center;
        }

        .subtitle{
            text-align:center;
            font-size:11pt;
        }

        .info td{
            border:none;
            padding:5px;
        }

        .data td{
            border:1px solid #000;
            background:#FFFFFF;
            color:#000000;
        }

        .header th{
            border:1px solid #000;
            text-align:center;
            font-weight:bold;
        }

        /* data rows: only bottom border to avoid extra vertical lines */
        /* rekap box styled as table with borders */
        .rekap-title{
            background:#D9EAD3;
            font-weight:bold;
            border:1px solid #000;
        }


        .left{
            text-align:left !important;
        }

        


    </style>

</head>
<body>

<table>

    <!-- Judul -->
    <tr>
        <td colspan="5" class="title">
            LAPORAN KEHADIRAN SISWA SDIT NURUL FIKRI
        </td>
    </tr>

    <tr><td colspan="5"></td></tr>

    <!-- Informasi Siswa -->

    <tr class="info">
        <td width="20%"><b>Nama Siswa</b></td>
        <td width="5%">:</td>
        <td width="40%">{{ $siswa->nama_siswa }}</td>
        <td></td>
        <td></td>
    </tr>

    <tr class="info">
        <td><b>NIS</b></td>
        <td>:</td>
        <td style="mso-number-format:'\@';">
            '{{ $siswa->nis }}
        </td>
        <td></td>
        <td></td>
    </tr>

    <tr class="info">
        <td><b>Kelas</b></td>
        <td>:</td>
        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
        <td></td>
        <td></td>
    </tr>

    <tr><td colspan="5"></td></tr>

    <!-- Header -->

    <tr>
        <th style="background:#4472C4;
                color:#FFFFFF;
                font-weight:bold;
                text-align:center;
                border:1px solid #000;">
            No
        </th>

        <th style="background:#4472C4;
                color:#FFFFFF;
                font-weight:bold;
                text-align:center;
                border:1px solid #000;">
            Tanggal
        </th>

        <th style="background:#4472C4;
                color:#FFFFFF;
                font-weight:bold;
                text-align:center;
                border:1px solid #000;">
            Status
        </th>

        <th style="background:#4472C4;
                color:#FFFFFF;
                font-weight:bold;
                text-align:center;
                border:1px solid #000;">
            Jam Masuk
        </th>

        <th style="background:#4472C4;
                color:#FFFFFF;
                font-weight:bold;
                text-align:center;
                border:1px solid #000;">
            Keterangan
        </th>
    </tr>

    @forelse($laporan as $item)

    <tr class="data">

        <td>{{ $loop->iteration }}</td>

        <td>
            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
        </td>

        <td>{{ $item->status_hadir }}</td>

        <td>{{ $item->jam_masuk ?? '-' }}</td>

        <td>-</td>

    </tr>

    @empty

    <tr class="data">
        <td colspan="5">
            Tidak ada data kehadiran.
        </td>
    </tr>

    @endforelse

    <tr><td colspan="5"></td></tr>

    <!-- Rekap -->

    <tr><td colspan="5"></td></tr>
    <tr>
        <td colspan="5"
            style="
                background:#D9EAD3;
                text-align:center;
                font-weight:bold;
                border:1px solid #000;">
            REKAP KEHADIRAN
        </td>
    </tr>

    <tr style="background:#4472C4;color:#fff;font-weight:bold;">
        <td style="border:1px solid #000;text-align:center;">Hadir</td>
        <td style="border:1px solid #000;text-align:center;">Izin</td>
        <td style="border:1px solid #000;text-align:center;">Sakit</td>
        <td style="border:1px solid #000;text-align:center;">Alpa</td>
        <td style="border:1px solid #000;text-align:center;">Total</td>
    </tr>

    <tr>
        <td style="border:1px solid #000;text-align:center;">{{ $hadir }}</td>
        <td style="border:1px solid #000;text-align:center;">{{ $izin }}</td>
        <td style="border:1px solid #000;text-align:center;">{{ $sakit }}</td>
        <td style="border:1px solid #000;text-align:center;">{{ $alpha }}</td>
        <td style="border:1px solid #000;text-align:center;">
            {{ $hadir + $izin + $sakit + $alpha }}
        </td>
    </tr>

</table>

</body>
</html>