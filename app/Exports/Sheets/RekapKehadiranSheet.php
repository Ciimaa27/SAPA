<?php

namespace App\Exports\Sheets;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
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

class RekapKehadiranSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithCustomStartCell
{
    protected $bulan;
    protected $totals = [];

    public function __construct($bulan)
    {
        $this->bulan = $bulan;
    }

    public function title(): string
    {
        return 'Rekap Kehadiran';
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function array(): array
    {
        [$tahun, $bln] = explode('-', $this->bulan);

        $data = Siswa::leftJoin('kehadiran', 'siswa.id_siswa', '=', 'kehadiran.id_siswa')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'siswa.id_siswa',
                'siswa.nis',
                'siswa.nama_siswa',
                'kelas.nama_kelas',
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'hadir' AND YEAR(kehadiran.tanggal) = {$tahun} AND MONTH(kehadiran.tanggal) = {$bln} THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'sakit' AND YEAR(kehadiran.tanggal) = {$tahun} AND MONTH(kehadiran.tanggal) = {$bln} THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'izin' AND YEAR(kehadiran.tanggal) = {$tahun} AND MONTH(kehadiran.tanggal) = {$bln} THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'alpa' AND YEAR(kehadiran.tanggal) = {$tahun} AND MONTH(kehadiran.tanggal) = {$bln} THEN 1 ELSE 0 END) as alpa")
            )
            ->groupBy('siswa.id_siswa', 'siswa.nis', 'siswa.nama_siswa', 'kelas.nama_kelas')
            ->orderBy('siswa.nama_siswa')
            ->get();

        $result = [];
        $this->totals = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpa' => 0,
        ];

        foreach ($data as $idx => $row) {
            $excelRow = 6 + $idx;

            $hadir = (int) $row->hadir;
            $sakit = (int) $row->sakit;
            $izin = (int) $row->izin;
            $alpa = (int) $row->alpa;

            $this->totals['hadir'] += $hadir;
            $this->totals['sakit'] += $sakit;
            $this->totals['izin'] += $izin;
            $this->totals['alpa'] += $alpa;

            $totalStatus = $hadir + $sakit + $izin + $alpa;
            $persentase = $totalStatus > 0 ? $hadir / $totalStatus : 0;

            $result[] = [
                $idx + 1,
                $row->nis ?? $row->id_siswa,
                $row->nama_siswa,
                $row->nama_kelas ?? '-',
                $hadir,
                $sakit,
                $izin,
                $alpa,
                $persentase,
            ];
        }

        return $result;
    }

    public function headings(): array
    {
        return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Hadir', 'Sakit', 'Izin', 'Alpha', 'Persentase'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(true);

        $widths = [
            'A' => 12, 'B' => 31, 'C' => 12, 'D' => 15, 'E' => 12,
            'F' => 15, 'G' => 15, 'H' => 15, 'I' => 15, 'J' => 19,
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->setCellValue('B2', 'REKAPITULASI KEHADIRAN SISWA');
        $sheet->getStyle('B2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('0D47A1'));
        $sheet->setCellValue('B3', 'Periode: ' . $this->bulan);
        $sheet->getStyle('B3')->getFont()->setItalic(true);

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('B5:J5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D47A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        if ($lastRow >= 6) {
            $sheet->getStyle("B5:J{$lastRow}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()->setRGB('B0BEC5');

            $sheet->getStyle("F6:J{$lastRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("J6:J{$lastRow}")
                ->getNumberFormat()->setFormatCode('0%');

            $totalRow = $lastRow + 1;
            $sheet->setCellValue("B{$totalRow}", 'TOTAL');
            $sheet->mergeCells("B{$totalRow}:E{$totalRow}");

            foreach (['F', 'G', 'H', 'I'] as $col) {
                $key = match ($col) {
                    'F' => 'hadir',
                    'G' => 'sakit',
                    'H' => 'izin',
                    'I' => 'alpa',
                };

                $sheet->setCellValue("{$col}{$totalRow}", $this->totals[$key]);
            }

            $totalCount = array_sum($this->totals);
            $sheet->setCellValue("J{$totalRow}", $totalCount > 0 ? $this->totals['hadir'] / $totalCount : 0);
            $sheet->getStyle("J{$totalRow}")->getNumberFormat()->setFormatCode('0%');

            $sheet->getStyle("B{$totalRow}:J{$totalRow}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E0E0']],
            ]);
            $sheet->getStyle("B{$totalRow}:J{$totalRow}")
                ->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
        }
    }
}
