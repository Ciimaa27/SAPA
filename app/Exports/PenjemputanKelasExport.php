<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class PenjemputanKelasExport implements
    FromArray,
    WithHeadings,
    ShouldAutoSize,
    WithEvents
{
    protected $kelasId;
    protected $bulan;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct($kelasId, $bulan)
    {
        $this->kelasId = $kelasId;
        $this->bulan = $bulan;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER EXCEL
    |--------------------------------------------------------------------------
    */

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
            'Wali',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DATA EXCEL
    |--------------------------------------------------------------------------
    */

    public function array(): array
    {
        /*
        |--------------------------------------------------------------------------
        | PECAH PERIODE
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | 2026-07
        |
        | $tahun = 2026
        | $bulan = 07
        |
        */

        [$tahun, $bulan] = explode('-', $this->bulan);


        /*
        |--------------------------------------------------------------------------
        | QUERY REKAP PENJEMPUTAN
        |--------------------------------------------------------------------------
        */

        $siswas = Siswa::query()

            /*
            |--------------------------------------------------------------------------
            | JOIN PENJEMPUTAN
            |--------------------------------------------------------------------------
            */

            ->leftJoin(
                'penjemputan',
                'siswa.id_siswa',
                '=',
                'penjemputan.id_siswa'
            )


            /*
            |--------------------------------------------------------------------------
            | JOIN SISWA WALI
            |--------------------------------------------------------------------------
            |
            | Digunakan untuk mengetahui hubungan:
            |
            | Ayah
            | Ibu
            | Wali
            |
            */

            ->leftJoin('siswa_wali', function ($join) {

                $join->on(
                    'penjemputan.id_siswa',
                    '=',
                    'siswa_wali.id_siswa'
                );

                $join->on(
                    'penjemputan.id_wali',
                    '=',
                    'siswa_wali.id_wali'
                );
            })


            /*
            |--------------------------------------------------------------------------
            | FILTER KELAS
            |--------------------------------------------------------------------------
            */

            ->where(
                'siswa.id_kelas',
                $this->kelasId
            )


            /*
            |--------------------------------------------------------------------------
            | SELECT DATA SISWA
            |--------------------------------------------------------------------------
            */

            ->select(
                'siswa.id_siswa',
                'siswa.nama_siswa'
            )


            /*
            |--------------------------------------------------------------------------
            | JUMLAH PENJEMPUTAN
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                "
                COUNT(
                    CASE
                        WHEN YEAR(penjemputan.tanggal) = ?
                        AND MONTH(penjemputan.tanggal) = ?
                        THEN penjemputan.id
                    END
                ) AS jumlah_penjemputan
                ",
                [
                    $tahun,
                    $bulan
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | TEPAT WAKTU
            |--------------------------------------------------------------------------
            |
            | Menggunakan kolom:
            |
            | penjemputan.status
            |
            | BUKAN:
            |
            | penjemputan.status_penjemputan
            |
            */

            ->selectRaw(
                "
                COUNT(
                    CASE
                        WHEN LOWER(penjemputan.status) = 'tepat waktu'
                        AND YEAR(penjemputan.tanggal) = ?
                        AND MONTH(penjemputan.tanggal) = ?
                        THEN penjemputan.id
                    END
                ) AS tepat_waktu
                ",
                [
                    $tahun,
                    $bulan
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | TERLAMBAT
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                "
                COUNT(
                    CASE
                        WHEN LOWER(penjemputan.status) = 'terlambat'
                        AND YEAR(penjemputan.tanggal) = ?
                        AND MONTH(penjemputan.tanggal) = ?
                        THEN penjemputan.id
                    END
                ) AS terlambat
                ",
                [
                    $tahun,
                    $bulan
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | DIJEMPUT AYAH
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                "
                COUNT(
                    CASE
                        WHEN LOWER(siswa_wali.hubungan) = 'ayah'
                        AND YEAR(penjemputan.tanggal) = ?
                        AND MONTH(penjemputan.tanggal) = ?
                        THEN penjemputan.id
                    END
                ) AS ayah
                ",
                [
                    $tahun,
                    $bulan
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | DIJEMPUT IBU
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                "
                COUNT(
                    CASE
                        WHEN LOWER(siswa_wali.hubungan) = 'ibu'
                        AND YEAR(penjemputan.tanggal) = ?
                        AND MONTH(penjemputan.tanggal) = ?
                        THEN penjemputan.id
                    END
                ) AS ibu
                ",
                [
                    $tahun,
                    $bulan
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | DIJEMPUT WALI
            |--------------------------------------------------------------------------
            */

            ->selectRaw(
                "
                COUNT(
                    CASE
                        WHEN LOWER(siswa_wali.hubungan) = 'wali'
                        AND YEAR(penjemputan.tanggal) = ?
                        AND MONTH(penjemputan.tanggal) = ?
                        THEN penjemputan.id
                    END
                ) AS wali
                ",
                [
                    $tahun,
                    $bulan
                ]
            )


            /*
            |--------------------------------------------------------------------------
            | GROUP SISWA
            |--------------------------------------------------------------------------
            */

            ->groupBy(
                'siswa.id_siswa',
                'siswa.nama_siswa'
            )


            /*
            |--------------------------------------------------------------------------
            | URUTKAN NAMA
            |--------------------------------------------------------------------------
            */

            ->orderBy(
                'siswa.nama_siswa',
                'asc'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA UNTUK EXCEL
        |--------------------------------------------------------------------------
        */

        $data = [];


        foreach ($siswas as $idx => $siswa) {

            $data[] = [

                /*
                | No
                */

                $idx + 1,


                /*
                | Nama siswa
                */

                $siswa->nama_siswa,


                /*
                | Jumlah penjemputan
                */

                (int) $siswa->jumlah_penjemputan,


                /*
                | Tepat waktu
                */

                (int) $siswa->tepat_waktu,


                /*
                | Terlambat
                */

                (int) $siswa->terlambat,


                /*
                | Ayah
                */

                (int) $siswa->ayah,


                /*
                | Ibu
                */

                (int) $siswa->ibu,


                /*
                | Wali
                */

                (int) $siswa->wali,
            ];
        }


        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | STYLE EXCEL
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet =
                    $event->sheet->getDelegate();


                /*
                |--------------------------------------------------------------------------
                | HEADER TABEL
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle('A1:H1')
                    ->applyFromArray([

                        'font' => [

                            'bold' => true,

                            'color' => [
                                'rgb' => 'FFFFFF'
                            ],
                        ],

                        'fill' => [

                            'fillType' =>
                                Fill::FILL_SOLID,

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


                /*
                |--------------------------------------------------------------------------
                | TINGGI HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(24);


                /*
                |--------------------------------------------------------------------------
                | AMBIL BARIS TERAKHIR
                |--------------------------------------------------------------------------
                */

                $lastRow =
                    $sheet->getHighestRow();


                /*
                |--------------------------------------------------------------------------
                | BORDER TABEL
                |--------------------------------------------------------------------------
                */

                if ($lastRow >= 1) {

                    $sheet
                        ->getStyle(
                            "A1:H{$lastRow}"
                        )
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(
                            Border::BORDER_THIN
                        )
                        ->getColor()
                        ->setRGB('BFBFBF');
                }


                /*
                |--------------------------------------------------------------------------
                | ALIGNMENT DATA
                |--------------------------------------------------------------------------
                */

                if ($lastRow >= 2) {

                    /*
                    | No
                    */

                    $sheet
                        ->getStyle(
                            "A2:A{$lastRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    | Kolom angka
                    */

                    $sheet
                        ->getStyle(
                            "C2:H{$lastRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    | Vertical center
                    */

                    $sheet
                        ->getStyle(
                            "A2:H{$lastRow}"
                        )
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A2');
            },
        ];
    }
}