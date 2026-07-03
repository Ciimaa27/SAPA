<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjemputan Siswa</title>

    <style>

        body{
            font-family: Calibri, Arial, sans-serif;
            font-size:11pt;
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

        .info td{
            border:none;
            padding:5px;
        }

        .data td{
            border:1px solid #000;
            background:#FFFFFF;
            color:#000000;
        }

    </style>

</head>

<body>

<table>

    <!-- Judul -->
    <tr>
        <td colspan="5" class="title">
            LAPORAN PENJEMPUTAN SISWA SDIT NURUL FIKRI
        </td>
    </tr>

    <tr>
        <td colspan="5"></td>
    </tr>

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

    <tr>
        <td colspan="5"></td>
    </tr>

    <!-- Header -->

    <tr>

        <th style="background:#4472C4;color:#FFFFFF;font-weight:bold;text-align:center;border:1px solid #000;">
            No
        </th>

        <th style="background:#4472C4;color:#FFFFFF;font-weight:bold;text-align:center;border:1px solid #000;">
            Tanggal
        </th>

        <th style="background:#4472C4;color:#FFFFFF;font-weight:bold;text-align:center;border:1px solid #000;">
            Jam Jemput
        </th>

        <th style="background:#4472C4;color:#FFFFFF;font-weight:bold;text-align:center;border:1px solid #000;">
            Penjemput
        </th>

        <th style="background:#4472C4;color:#FFFFFF;font-weight:bold;text-align:center;border:1px solid #000;">
            Status
        </th>

    </tr>

    @forelse($penjemputan as $item)

    <tr class="data">

        <td style="text-align:center;">
            {{ $loop->iteration }}
        </td>

        <td style="text-align:center;">
            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
        </td>

        <td style="text-align:center;">
            {{ \Carbon\Carbon::parse($item->jam_jemput)->format('H:i') }}
        </td>

        <td>
            {{ $item->wali?->nama_wali ?? '-' }}
        </td>

        <td style="text-align:center;">
            {{ $item->status_penjemputan }}
        </td>

    </tr>

    @empty

    <tr class="data">
        <td colspan="5" style="text-align:center;">
            Tidak ada data penjemputan.
        </td>
    </tr>

    @endforelse

    <tr>
        <td colspan="5"></td>
    </tr>

    <!-- REKAP -->

    <tr>
        <td colspan="5"
            style="
                background:#D9EAD3;
                text-align:center;
                font-weight:bold;
                border:1px solid #000;">
            REKAP PENJEMPUTAN
        </td>
    </tr>

    <tr style="background:#4472C4;color:#fff;font-weight:bold;">

        <td colspan="2"
            style="border:1px solid #000;text-align:center;">
            Tepat Waktu
        </td>

        <td colspan="2"
            style="border:1px solid #000;text-align:center;">
            Terlambat
        </td>

        <td
            style="border:1px solid #000;text-align:center;">
            Total
        </td>

    </tr>

    <tr>

        <td colspan="2"
            style="border:1px solid #000;text-align:center;">
            {{ $tepat }}
        </td>

        <td colspan="2"
            style="border:1px solid #000;text-align:center;">
            {{ $terlambat }}
        </td>

        <td
            style="border:1px solid #000;text-align:center;">
            {{ $tepat + $terlambat }}
        </td>

    </tr>

</table>

</body>
</html>