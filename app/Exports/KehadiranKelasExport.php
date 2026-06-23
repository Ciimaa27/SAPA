<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KehadiranKelasExport implements FromArray, WithHeadings
{
    protected $kelasId;
    protected $bulan;

    public function __construct($kelasId, $bulan)
    {
        $this->kelasId = $kelasId;
        $this->bulan = $bulan;
    }

    public function array(): array
    {
        [$tahun, $bulan] = explode('-', $this->bulan);

        return Siswa::leftJoin('kehadiran', 'siswa.id_siswa', '=', 'kehadiran.id_siswa')
            ->where('siswa.id_kelas', $this->kelasId)
            ->select(
                'siswa.nama_siswa',
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'hadir' AND YEAR(kehadiran.tanggal) = $tahun AND MONTH(kehadiran.tanggal) = $bulan THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'izin' AND YEAR(kehadiran.tanggal) = $tahun AND MONTH(kehadiran.tanggal) = $bulan THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'sakit' AND YEAR(kehadiran.tanggal) = $tahun AND MONTH(kehadiran.tanggal) = $bulan THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'alpa' AND YEAR(kehadiran.tanggal) = $tahun AND MONTH(kehadiran.tanggal) = $bulan THEN 1 ELSE 0 END) as alpa")
            )
        ->groupBy(
            'siswa.id_siswa',
            'siswa.nama_siswa'
        )
        ->orderBy('siswa.nama_siswa')
        ->get()
        ->toArray();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpa'
        ];
    }
}
