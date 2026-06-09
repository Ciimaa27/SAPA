<?php

namespace App\Exports;

use App\Models\ArsipSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArsipSiswaExport implements FromCollection, WithHeadings
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return ArsipSiswa::where('tahun_lulus', $this->tahun)
            ->select(
                'nis',
                'nama_siswa',
                'kelas_terakhir',
                'jenis_kelamin',
                'tahun_lulus'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama',
            'Kelas Terakhir',
            'Jenis Kelamin',
            'Tahun Lulus'
        ];
    }
}