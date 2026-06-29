<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran</title>
</head>
<body>

<table>
    <tr>
        <td colspan="6" align="center">
            <strong>LAPORAN KEHADIRAN SISWA</strong>
        </td>
    </tr>

    <tr></tr>

    <tr>
        <td><strong>Nama Siswa</strong></td>
        <td>:</td>
        <td>{{ $siswa->nama_siswa }}</td>
    </tr>

    <tr>
        <td><strong>NIS</strong></td>
        <td>:</td>
        <td style="mso-number-format:'\@';">
        {{ $siswa->nis }}
    </td>
    </tr>

    <tr>
        <td><strong>Kelas</strong></td>
        <td>:</td>
        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
    </tr>

    <tr></tr>

    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Status</th>
        <th>Jam Masuk</th>
        <th>Keterangan</th>
    </tr>

    @foreach($laporan as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>

        <td>
            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
        </td>

        <td>{{ $item->status_hadir }}</td>

        <td>{{ $item->jam_masuk ?? '-' }}</td>

        <td>-</td>
    </tr>
    @endforeach

    <tr></tr>

    <tr>
        <td colspan="2"><strong>Rekap Kehadiran</strong></td>
    </tr>

    <tr>
        <td>Hadir</td>
        <td>{{ $hadir }}</td>
    </tr>

    <tr>
        <td>Izin</td>
        <td>{{ $izin }}</td>
    </tr>

    <tr>
        <td>Sakit</td>
        <td>{{ $sakit }}</td>
    </tr>

    <tr>
        <td>Alpa</td>
        <td>{{ $alpha }}</td>
    </tr>

</table>

</body>
</html>