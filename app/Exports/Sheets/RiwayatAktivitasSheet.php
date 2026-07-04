<?php

namespace App\Exports\Sheets;

use App\Models\LogTap;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class RiwayatAktivitasSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithCustomStartCell
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function title(): string
    {
        return 'Riwayat Aktivitas';
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function array(): array
    {
        $logs = LogTap::whereDate('created_at', $this->tanggal)
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        $result = [];

        foreach ($logs as $log) {
            $result[] = [
                $log->created_at->format('d-m-Y'),
                $log->created_at->format('H:i:s'),
                $log->aktivitas ?? 'Scan Device',
                $log->keterangan ?? '-',
            ];
        }

        return $result;
    }

    public function headings(): array
    {
        return ['Tanggal', 'Waktu', 'Aktivitas', 'Pengguna / Subjek'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(true);

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(33);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(29);
        $sheet->getColumnDimension('E')->setWidth(21);

        $sheet->setCellValue('B2', 'LOG AKTIVITAS SISTEM REAL-TIME');
        $sheet->getStyle('B2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('0D47A1'));

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('B5:E5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D47A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        if ($lastRow >= 5) {
            $sheet->getStyle("B5:E{$lastRow}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()->setRGB('B0BEC5');
        }
    }
}
