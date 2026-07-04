<?php

namespace App\Exports\Sheets;

use App\Models\User;
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

class DataWaliSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithCustomStartCell
{
    public function title(): string
    {
        return 'Data Wali';
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function array(): array
    {
        $users = User::where('id_role', 4)
            ->where('status', 'aktif')
            ->with(['wali.siswa'])
            ->get();

        $result = [];

        foreach ($users as $idx => $user) {
            $wali = $user->wali;
            $namaSiswa = $wali && $wali->siswa
                ? $wali->siswa->pluck('nama_siswa')->implode(', ')
                : '-';

            $result[] = [
                $idx + 1,
                $wali->nama_wali ?? $user->name ?? '-',
                $namaSiswa ?: '-',
                $wali->no_hp ?? $user->no_hp ?? '-',
                $wali->fingerprint_id ?? '-',
                'Aktif',
            ];
        }

        return $result;
    }

    public function headings(): array
    {
        return ['No', 'Nama Wali', 'Nama Siswa', 'No HP', 'Fingerprint', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(true);

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(29);
        $sheet->getColumnDimension('C')->setWidth(17);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(12);

        $sheet->setCellValue('B2', 'MASTER DATA WALI TERDAFTAR');
        $sheet->getStyle('B2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('0D47A1'));

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('B5:G5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D47A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        if ($lastRow >= 5) {
            $sheet->getStyle("B5:G{$lastRow}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()->setRGB('B0BEC5');
        }
    }
}
