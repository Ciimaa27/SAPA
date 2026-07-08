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
    protected $tanggal;

    public function __construct($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;
    }

    public function array(): array
    {
        $data = Siswa::leftJoin(
                'kehadiran',
                'siswa.id_siswa',
                '=',
                'kehadiran.id_siswa'
            )

            ->where(
                'siswa.id_kelas',
                $this->kelasId
            )

            ->select(
                'siswa.nama_siswa'
            )

            // HADIR
            ->selectRaw(
                "
                SUM(
                    CASE
                        WHEN LOWER(kehadiran.status_hadir) = 'hadir'
                        AND DATE(kehadiran.tanggal) = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS hadir
                ",
                [$this->tanggal]
            )

            // IZIN
            ->selectRaw(
                "
                SUM(
                    CASE
                        WHEN LOWER(kehadiran.status_hadir) = 'izin'
                        AND DATE(kehadiran.tanggal) = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS izin
                ",
                [$this->tanggal]
            )

            // SAKIT
            ->selectRaw(
                "
                SUM(
                    CASE
                        WHEN LOWER(kehadiran.status_hadir) = 'sakit'
                        AND DATE(kehadiran.tanggal) = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS sakit
                ",
                [$this->tanggal]
            )

            // ALPA
            ->selectRaw(
                "
                SUM(
                    CASE
                        WHEN LOWER(kehadiran.status_hadir) = 'alpa'
                        AND DATE(kehadiran.tanggal) = ?
                        THEN 1
                        ELSE 0
                    END
                ) AS alpa
                ",
                [$this->tanggal]
            )

            ->groupBy(
                'siswa.id_siswa',
                'siswa.nama_siswa'
            )

            ->orderBy(
                'siswa.nama_siswa',
                'asc'
            )

            ->get();


        $result = [];

        foreach ($data as $idx => $row) {
            $result[] = [
                $idx + 1,
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
        // HEADER
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4472C4'
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,
            ],
        ]);


        // TINGGI HEADER
        $sheet
            ->getRowDimension(1)
            ->setRowHeight(24);


        $lastRow = $sheet->getHighestRow();


        if ($lastRow >= 1) {

            // BORDER
            $sheet
                ->getStyle("A1:F{$lastRow}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    Border::BORDER_THIN
                )
                ->getColor()
                ->setRGB('BFBFBF');


            // KOLOM NO
            if ($lastRow >= 2) {

                $sheet
                    ->getStyle("A2:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );


                // KOLOM HADIR - ALPA
                $sheet
                    ->getStyle("C2:F{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );


                // VERTICAL CENTER
                $sheet
                    ->getStyle("A2:F{$lastRow}")
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );
            }
        }


        // FREEZE HEADER
        $sheet->freezePane('A2');
    }
}