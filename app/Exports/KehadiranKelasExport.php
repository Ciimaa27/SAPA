<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class KehadiranKelasExport implements
    FromArray,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    protected $kelasId;
    protected $bulan;

    public function __construct($kelasId, $bulan)
    {
        $this->kelasId = $kelasId;
        $this->bulan = $bulan;
    }

    public function array(): array
    {
        [$tahun, $bulan] = explode('-', $this->bulan);

        $data = Siswa::leftJoin('kehadiran', 'siswa.id_siswa', '=', 'kehadiran.id_siswa')
            ->where('siswa.id_kelas', $this->kelasId)
            ->select(
                'siswa.nama_siswa',
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'hadir' AND YEAR(kehadiran.tanggal) = ? AND MONTH(kehadiran.tanggal) = ? THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'izin' AND YEAR(kehadiran.tanggal) = ? AND MONTH(kehadiran.tanggal) = ? THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'sakit' AND YEAR(kehadiran.tanggal) = ? AND MONTH(kehadiran.tanggal) = ? THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN kehadiran.status_hadir = 'alpa' AND YEAR(kehadiran.tanggal) = ? AND MONTH(kehadiran.tanggal) = ? THEN 1 ELSE 0 END) as alpa")
            )
            // Mengisi placeholder binding secara berurutan untuk masing-masing CASE WHEN
            ->setBindings([$tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan], 'select')
            ->groupBy('siswa.id_siswa', 'siswa.nama_siswa')
            ->orderBy('siswa.nama_siswa')
            ->get();

        $result = [];
        foreach ($data as $idx => $row) {
            $result[] = [
                $idx + 1, // Penambahan kolom No otomatis
                $row->nama_siswa,
                (int) $row->hadir,
                (int) $row->izin,
                (int) $row->sakit,
                (int) $row->alpa,
            ];
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpa',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header tabel (A1 sampai F1 karena ada kolom nomor)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Mengatur tinggi baris header agar lebih lega
        $sheet->getRowDimension(1)->setRowHeight(24);

        $lastRow = $sheet->getHighestRow();

        if ($lastRow >= 1) {
            // Border seluruh tabel
            $sheet->getStyle("A1:F{$lastRow}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()
                ->setRGB('BFBFBF'); // Warna border abu-abu soft agar terlihat clean

            // Judul kolom rata tengah (No)
            $sheet->getStyle("A2:A{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Isi rekap kehadiran rata tengah (Hadir, Izin, Sakit, Alpa)
            $sheet->getStyle("C2:F{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
}