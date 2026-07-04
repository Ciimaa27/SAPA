<?php

namespace App\Exports\Sheets;

use App\Models\Penjemputan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapPenjemputanSheet implements
    FromArray,
    WithTitle,
    WithHeadings,
    WithStyles,
    WithCustomStartCell
{
    protected $bulan;
    protected $totalPenjemputan = 0;

    public function __construct($bulan)
    {
        $this->bulan = $bulan;
    }

    /*
    |--------------------------------------------------------------------------
    | NAMA SHEET
    |--------------------------------------------------------------------------
    |
    */
    public function title(): string
    {
        return 'Rekap Penjemputan';
    }

    /*
    |--------------------------------------------------------------------------
    | POSISI AWAL TABEL
    |--------------------------------------------------------------------------
    |
    */
    public function startCell(): string
    {
        return 'B5';
    }

    /*
    |--------------------------------------------------------------------------
    | DATA REKAP PENJEMPUTAN
    |--------------------------------------------------------------------------
    |
    */
    public function array(): array
    {
        [$tahun, $bulan] = explode('-', $this->bulan);

        /*
        |--------------------------------------------------------------------------
        | QUERY REKAP PER SISWA
        |--------------------------------------------------------------------------
        |
        | Data tidak ditampilkan satu per satu berdasarkan tanggal.
        | Semua transaksi dalam satu bulan dijumlahkan per siswa.
        |
        */
        $data = Penjemputan::query()
            ->join('siswa', 'penjemputan.id_siswa', '=', 'siswa.id_siswa')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->whereYear('penjemputan.tanggal', $tahun)
            ->whereMonth('penjemputan.tanggal', $bulan)
            ->select('siswa.id_siswa', 'siswa.nama_siswa', 'kelas.nama_kelas')
            /*
            |--------------------------------------------------------------------------
            | TOTAL PENJEMPUTAN
            |--------------------------------------------------------------------------
            */
            ->selectRaw('COUNT(penjemputan.id) AS jumlah_penjemputan')
            /*
            |--------------------------------------------------------------------------
            | GROUP PER SISWA
            |--------------------------------------------------------------------------
            */
            ->groupBy('siswa.id_siswa', 'siswa.nama_siswa', 'kelas.nama_kelas')
            ->orderBy('siswa.nama_siswa', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA EXCEL
        |--------------------------------------------------------------------------
        |
        */
        $this->totalPenjemputan = 0;
        $result = [];
        foreach ($data as $idx => $row) {
            $jumlahPenjemputan = (int) $row->jumlah_penjemputan;
            $this->totalPenjemputan += $jumlahPenjemputan;

            $result[] = [
                // No
                $idx + 1,
                // Nama siswa
                $row->nama_siswa,
                // Kelas
                $row->nama_kelas ?? '-',
                // Jumlah penjemputan
                $jumlahPenjemputan,
            ];
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER TABEL
    |--------------------------------------------------------------------------
    |
    */
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Jumlah Penjemputan',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STYLE EXCEL
    |--------------------------------------------------------------------------
    |
    */
    public function styles(Worksheet $sheet)
    {
        /*
        |--------------------------------------------------------------------------
        | GRIDLINES
        |--------------------------------------------------------------------------
        |
        */
        $sheet->setShowGridlines(true);

        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        |
        */
        $widths = [
            'A' => 5,
            // No
            'B' => 8,
            // Nama siswa
            'C' => 35,
            // Kelas
            'D' => 15,
            // Jumlah penjemputan
            'E' => 22,
        ];

        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        |
        */
        $sheet->setCellValue('B2', 'REKAPITULASI PENJEMPUTAN SISWA');
        $sheet->getStyle('B2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('0D47A1'));

        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        |
        */
        $sheet->setCellValue('B3', 'Periode: ' . $this->bulan);
        $sheet->getStyle('B3')->getFont()->setItalic(true);

        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        |
        */
        $sheet->getStyle('B5:E5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D47A1'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | TINGGI HEADER
        |--------------------------------------------------------------------------
        |
        */
        $sheet->getRowDimension(5)->setRowHeight(25);

        /*
        |--------------------------------------------------------------------------
        | LAST ROW
        |--------------------------------------------------------------------------
        |
        */
        $lastRow = $sheet->getHighestRow();

        /*
        |--------------------------------------------------------------------------
        | BORDER TABEL
        |--------------------------------------------------------------------------
        |
        */
        if ($lastRow >= 5) {
            $sheet->getStyle("B5:E{$lastRow}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()
                ->setRGB('B0BEC5');
        }

        /*
        |--------------------------------------------------------------------------
        | ALIGNMENT DATA
        |--------------------------------------------------------------------------
        |
        */
        if ($lastRow >= 6) {
            // No
            $sheet->getStyle("B6:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Kelas
            $sheet->getStyle("D6:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Jumlah penjemputan
            $sheet->getStyle("E6:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL ROW
        |--------------------------------------------------------------------------
        |
        */
        if ($lastRow >= 6) {
            $totalRow = $lastRow + 1;

            /*
            | Tulisan TOTAL
            |
            */
            $sheet->setCellValue("B{$totalRow}", 'TOTAL');

            /*
            | Gabungkan kolom TOTAL
            |
            */
            $sheet->mergeCells("B{$totalRow}:D{$totalRow}");

            /*
            | Total jumlah penjemputan
            |
            */
            $sheet->setCellValue("E{$totalRow}", $this->totalPenjemputan);

            /*
            |--------------------------------------------------------------------------
            | STYLE TOTAL ROW
            |--------------------------------------------------------------------------
            |
            */
            $sheet->getStyle("B{$totalRow}:E{$totalRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0'],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            /*
            | Center angka total
            |
            */
            $sheet->getStyle("E{$totalRow}:E{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            /*
            | Double border bawah
            |
            */
            $sheet->getStyle("B{$totalRow}:E{$totalRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
        }

        /*
        |--------------------------------------------------------------------------
        | FREEZE HEADER
        |--------------------------------------------------------------------------
        |
        */
        $sheet->freezePane('B6');
    }
}