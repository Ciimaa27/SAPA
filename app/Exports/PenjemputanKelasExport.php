<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Penjemputan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PenjemputanKelasExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
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
        return [
            'No',
            'Nama Siswa',
            'Jumlah Penjemputan',
            'Tepat Waktu',
            'Terlambat',
            'Ayah',
            'Ibu',
            'Wali'
        ];
    }

    public function array(): array
    {
        [$tahun, $bulan] = explode('-', $this->bulan);

        $siswas = Siswa::where('id_kelas', $this->kelasId)
            ->orderBy('nama_siswa')
            ->get();

        $data = [];
        $no = 1;

        foreach ($siswas as $siswa) {

            $penjemputan = Penjemputan::join('siswa_wali', function ($join) {
                    $join->on('penjemputan.id_siswa', '=', 'siswa_wali.id_siswa')
                         ->on('penjemputan.id_wali', '=', 'siswa_wali.id_wali');
                })
                ->where('penjemputan.id_siswa', $siswa->id_siswa)
                ->whereYear('penjemputan.tanggal', $tahun)
                ->whereMonth('penjemputan.tanggal', $bulan)
                ->select(
                    'penjemputan.status_penjemputan',
                    'siswa_wali.hubungan'
                )
                ->get();

            $jumlah = $penjemputan->count();

            $tepat = $penjemputan
                ->where('status_penjemputan', 'Tepat Waktu')
                ->count();

            $terlambat = $penjemputan
                ->where('status_penjemputan', 'Terlambat')
                ->count();

            $ayah = $penjemputan
                ->where('hubungan', 'Ayah')
                ->count();

            $ibu = $penjemputan
                ->where('hubungan', 'Ibu')
                ->count();

            $wali = $penjemputan
                ->where('hubungan', 'Wali')
                ->count();

            $data[] = [
                $no++,
                $siswa->nama_siswa,
                $jumlah,
                $tepat,
                $terlambat,
                $ayah,
                $ibu,
                $wali,
            ];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Header
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '4472C4',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Baris terakhir
                $lastRow = $sheet->getHighestRow();

                // Border seluruh tabel
                $sheet->getStyle("A1:H{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Rata tengah header
                $sheet->getStyle("A1:H1")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Kolom No rata tengah
                $sheet->getStyle("A2:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Kolom angka rata tengah
                $sheet->getStyle("C2:H{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tinggi header
                $sheet->getRowDimension(1)->setRowHeight(22);
            }
        ];
    }
}