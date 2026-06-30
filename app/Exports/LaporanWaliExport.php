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

class LaporanWaliExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $siswa;
    protected $laporan;
    protected $hadir;
    protected $izin;
    protected $sakit;
    protected $alpha;

    public function __construct($siswa, $laporan, $hadir, $izin, $sakit, $alpha)
    {
        $this->siswa = $siswa;
        $this->laporan = $laporan;
        $this->hadir = $hadir;
        $this->izin = $izin;
        $this->sakit = $sakit;
        $this->alpha = $alpha;
    }

    public function view(): View
    {
        return view('wali.laporan_excel', [
            'siswa'   => $this->siswa,
            'laporan' => $this->laporan,
            'hadir'   => $this->hadir,
            'izin'    => $this->izin,
            'sakit'   => $this->sakit,
            'alpha'   => $this->alpha,
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
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(30);

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
                $lastRow = 8 + count($this->laporan);

                $sheet->getStyle("A8:E{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Rekap
                $rekapStart = $lastRow + 2; // Judul REKAP
                $rekapEnd   = $lastRow + 4; // Sampai baris data rekap

                $sheet->getStyle("A{$rekapStart}:E{$rekapEnd}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                    
                // Tengah
                $sheet->getStyle("A8:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            }

        ];
    }
}