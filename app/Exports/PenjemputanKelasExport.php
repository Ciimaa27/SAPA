<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Penjemputan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjemputanKelasExport implements FromArray, WithHeadings
{
    protected $kelasId;
    protected $bulan;

    public function __construct($kelasId, $bulan)
    {
        $this->kelasId = $kelasId;
        $this->bulan = $bulan;
    }

    public function headings(): array
    {
        $jumlahHari = Carbon::parse($this->bulan . '-01')->daysInMonth;

        $headings = ['Nama Siswa'];

        for ($i = 1; $i <= $jumlahHari; $i++) {
            $headings[] = $i;
        }

        return $headings;
    }

    public function array(): array
    {
        [$tahun, $bulan] = explode('-', $this->bulan);

        $jumlahHari = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        $data = [];

        $siswas = Siswa::where('id_kelas', $this->kelasId)
            ->orderBy('nama_siswa')
            ->get();

        foreach ($siswas as $siswa) {

            $row = [
                'Nama Siswa' => $siswa->nama_siswa
            ];

            for ($hari = 1; $hari <= $jumlahHari; $hari++) {

                $penjemputan = Penjemputan::join(
                        'wali',
                        'penjemputan.id_wali',
                        '=',
                        'wali.id_wali'
                    )
                    ->where('penjemputan.id_siswa', $siswa->id_siswa)
                    ->whereYear('penjemputan.tanggal', $tahun)
                    ->whereMonth('penjemputan.tanggal', $bulan)
                    ->whereDay('penjemputan.tanggal', $hari)
                    ->select('wali.nama_wali')
                    ->first();

                $row[$hari] = $penjemputan
                    ? $penjemputan->nama_wali
                    : '-';
            }

            $data[] = $row;
        }

        return $data;
    }
}
