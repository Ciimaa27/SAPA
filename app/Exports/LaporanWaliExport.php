<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PenjemputanWaliExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $siswa;
    protected $penjemputan;
    protected $tepat;
    protected $terlambat;

    public function __construct($siswa, $penjemputan, $tepat, $terlambat)
    {
        $this->siswa = $siswa;
        $this->penjemputan = $penjemputan;
        $this->tepat = $tepat;
        $this->terlambat = $terlambat;
    }

    public function view(): View
    {
        return view('wali.laporan_penjemputan_excel', [
            'siswa'        => $this->siswa,
            'penjemputan'  => $this->penjemputan,
            'tepat'        => $this->tepat,
            'terlambat'    => $this->terlambat,
        ]);
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->mergeCells('A1:E1');

                $sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);

                // Lebar kolom
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(18);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(20);

                // Header tabel
                $sheet->getStyle('A7:E7')->applyFromArray([
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

                // Border tabel
                $lastRow = 8 + count($this->penjemputan);

                $sheet->getStyle("A8:E{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Border rekap
                $rekapStart = $lastRow + 2;
                $rekapEnd   = $lastRow + 4;

                $sheet->getStyle("A{$rekapStart}:E{$rekapEnd}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Rata tengah isi tabel
                $sheet->getStyle("A8:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            }

        ];
    }
}