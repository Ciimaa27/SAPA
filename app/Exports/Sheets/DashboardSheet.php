<?php

namespace App\Exports\Sheets;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Wali;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class DashboardSheet implements WithTitle, WithStyles
{
    protected $bulan;
    protected $tanggal;

    public function __construct($bulan, $tanggal)
    {
        $this->bulan = $bulan;
        $this->tanggal = $tanggal;
    }

    public function title(): string
    {
        return 'Dashboard';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(true);

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(36);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(32);
        $sheet->getColumnDimension('F')->setWidth(21);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(23);
        $sheet->getColumnDimension('I')->setWidth(12);

        $sheet->setCellValue('B2', 'SAPA - LAPORAN KEPALA SEKOLAH');
        $sheet->getStyle('B2')->getFont()->setName('Arial')->setSize(16)->setBold(true)->setColor(new Color('0D47A1'));

        $sheet->setCellValue('B3', 'Sistem Absensi & Penjemputan Anak');
        $sheet->getStyle('B3')->getFont()->setName('Arial')->setSize(11)->setItalic(true)->setColor(new Color('555555'));

        $sheet->setCellValue('B5', 'Nama Sekolah:');
        $sheet->setCellValue('C5', 'TK Manhajul Husna');
        $sheet->setCellValue('B6', 'Periode:');
        $sheet->setCellValue('C6', $this->bulan);
        $sheet->setCellValue('B7', 'Tanggal Cetak:');
        $sheet->setCellValue('C7', $this->tanggal);
        $sheet->getStyle('B5:B7')->getFont()->setBold(true);

        $totalSiswa = Siswa::where('is_active', 1)->count();
        $totalGuru = Guru::count();
        $totalWali = Wali::where('is_active', 1)->count();
        $hadirHariIni = Kehadiran::whereDate('tanggal', $this->tanggal)
            ->where('status_hadir', 'hadir')->count();
        $tidakHadir = Kehadiran::whereDate('tanggal', $this->tanggal)
            ->whereIn('status_hadir', ['sakit', 'izin', 'alpa'])->count();
        $jemputHariIni = Penjemputan::whereDate('tanggal', $this->tanggal)->count();
        $terlambat = Penjemputan::whereDate('tanggal', $this->tanggal)
            ->where('status', 'terlambat')->count();

        $cards = [
            ['TOTAL SISWA', $totalSiswa, 'B', 'C'],
            ['TOTAL GURU', $totalGuru, 'D', 'E'],
            ['KEHADIRAN HARI INI', $hadirHariIni, 'F', 'G'],
            ['PENJEMPUTAN HARI INI', $jemputHariIni, 'H', 'I'],
        ];

        $thinBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'B0BEC5'],
                ],
            ],
        ];

        foreach ($cards as [$title, $value, $start, $end]) {
            $sheet->mergeCells("{$start}9:{$end}9");
            $sheet->setCellValue("{$start}9", $title);
            $sheet->getStyle("{$start}9")->getFont()->setSize(9)->setBold(true)->setColor(new Color('555555'));
            $sheet->getStyle("{$start}9")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->mergeCells("{$start}10:{$end}11");
            $sheet->setCellValue("{$start}10", $value);
            $sheet->getStyle("{$start}10")->getFont()->setSize(20)->setBold(true)->setColor(new Color('0D47A1'));
            $sheet->getStyle("{$start}10")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $range = "{$start}9:{$end}11";
            $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle($range)->getFill()->getStartColor()->setRGB('E3F2FD');
            $sheet->getStyle($range)->applyFromArray($thinBorder);
        }

        $sheet->setCellValue('B13', 'Ringkasan Indikator Operasional');
        $sheet->setCellValue('E13', 'Ringkasan Kehadiran per Kelas');
        $sheet->getStyle('B13')->getFont()->setBold(true)->setColor(new Color('0D47A1'));
        $sheet->getStyle('E13')->getFont()->setBold(true)->setColor(new Color('0D47A1'));

        $sheet->fromArray(['Indikator', 'Jumlah'], null, 'B14');
        $sheet->fromArray(['Kelas', 'Siswa', 'Hadir', '%'], null, 'E14');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D47A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('B14:C14')->applyFromArray($headerStyle);
        $sheet->getStyle('E14:H14')->applyFromArray($headerStyle);

        $indicators = [
            ['Total Siswa', $totalSiswa],
            ['Total Guru', $totalGuru],
            ['Total Wali Terdaftar', $totalWali],
            ['Kehadiran Hari Ini', $hadirHariIni],
            ['Tidak Hadir', $tidakHadir],
            ['Penjemputan Hari Ini', $jemputHariIni],
            ['Terlambat Dijemput', $terlambat],
        ];

        foreach ($indicators as $i => $item) {
            $row = 15 + $i;
            $sheet->fromArray($item, null, "B{$row}");
            $sheet->getStyle("B{$row}:C{$row}")->applyFromArray($thinBorder);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        $classes = DB::table('kelas')
            ->leftJoin('siswa', function ($join) {
                $join->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                    ->where('siswa.is_active', 1);
            })
            ->leftJoin('kehadiran', function ($join) {
                $join->on('siswa.id_siswa', '=', 'kehadiran.id_siswa')
                    ->whereDate('kehadiran.tanggal', $this->tanggal)
                    ->where('kehadiran.status_hadir', 'hadir');
            })
            ->select(
                'kelas.nama_kelas',
                DB::raw('COUNT(DISTINCT siswa.id_siswa) as total_siswa'),
                DB::raw('COUNT(DISTINCT kehadiran.id_siswa) as hadir')
            )
            ->groupBy('kelas.id_kelas', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->get();

        foreach ($classes as $i => $kelas) {
            $row = 15 + $i;
            $persen = $kelas->total_siswa > 0 ? $kelas->hadir / $kelas->total_siswa : 0;
            $sheet->fromArray([
                $kelas->nama_kelas,
                $kelas->total_siswa,
                $kelas->hadir,
                $persen,
            ], null, "E{$row}");
            $sheet->getStyle("E{$row}:H{$row}")->applyFromArray($thinBorder);
            $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('0.0%');
        }
    }
}
