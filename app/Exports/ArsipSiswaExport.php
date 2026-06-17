<?php

namespace App\Exports;

use App\Models\ArsipSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArsipSiswaExport implements FromCollection, WithHeadings
{
    protected $tahun;
    protected $status;

    public function __construct($tahun, $status)
    {
        $this->tahun = $tahun;
        $this->status = $status;
    }

    public function collection()
    {
        return ArsipSiswa::where('tahun_lulus', $this->tahun)
            ->where('status', $this->status)
            ->select(
                'nis',
                'nama_siswa',
                'kelas_terakhir',
                'jenis_kelamin',
                'status',
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