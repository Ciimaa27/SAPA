<?php

namespace App\Exports\Sheets;

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

class RiwayatAktivitasSheet implements
    FromArray,
    WithTitle,
    WithHeadings,
    WithStyles,
    WithCustomStartCell
{
    protected $bulan;

    public function __construct($bulan)
    {
        $this->bulan = $bulan;
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA SHEET
    |--------------------------------------------------------------------------
    */

    public function title(): string
    {
        return 'Riwayat Aktivitas';
    }


    /*
    |--------------------------------------------------------------------------
    | POSISI AWAL TABEL
    |--------------------------------------------------------------------------
    */

    public function startCell(): string
    {
        return 'B5';
    }


    /*
    |--------------------------------------------------------------------------
    | DATA AKTIVITAS
    |--------------------------------------------------------------------------
    */

    public function array(): array
    {
        [$tahun, $bulan] = explode('-', $this->bulan);

        $aktivitas = collect();


        /*
        |--------------------------------------------------------------------------
        | 1. DATA KEHADIRAN
        |--------------------------------------------------------------------------
        */

        $kehadiran = DB::table('kehadiran')

            ->join(
                'siswa',
                'kehadiran.id_siswa',
                '=',
                'siswa.id_siswa'
            )

            ->whereYear(
                'kehadiran.tanggal',
                $tahun
            )

            ->whereMonth(
                'kehadiran.tanggal',
                $bulan
            )

            ->select(
                'kehadiran.tanggal',
                'kehadiran.jam_masuk',
                'kehadiran.metode',
                'kehadiran.status_hadir',
                'siswa.nama_siswa'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | MASUKKAN KEHADIRAN KE AKTIVITAS
        |--------------------------------------------------------------------------
        */

        foreach ($kehadiran as $row) {

            /*
            | Tentukan nama metode
            */

            $metode = $row->metode ?? 'Manual';


            /*
            | Bentuk nama aktivitas
            */

            if (
                strtolower($row->status_hadir) === 'hadir'
            ) {

                $namaAktivitas =
                    'Absen Masuk (' . $metode . ')';

            } else {

                $namaAktivitas =
                    ucfirst($row->status_hadir)
                    . ' (' . $metode . ')';
            }


            $aktivitas->push([

                'tanggal' => $row->tanggal,

                'waktu' =>
                    $row->jam_masuk ?? '00:00:00',

                'aktivitas' =>
                    $namaAktivitas,

                'subjek' =>
                    $row->nama_siswa ?? '-',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. DATA PENJEMPUTAN
        |--------------------------------------------------------------------------
        */

        $penjemputan = DB::table('penjemputan')

            ->join(
                'siswa',
                'penjemputan.id_siswa',
                '=',
                'siswa.id_siswa'
            )

            ->leftJoin(
                'wali',
                'penjemputan.id_wali',
                '=',
                'wali.id_wali'
            )

            ->whereYear(
                'penjemputan.tanggal',
                $tahun
            )

            ->whereMonth(
                'penjemputan.tanggal',
                $bulan
            )

            ->select(
                'penjemputan.tanggal',
                'penjemputan.jam_jemput',
                'penjemputan.status',
                'penjemputan.metode',
                'siswa.nama_siswa',
                'wali.nama_wali'
            )

            ->get();


        /*
        |--------------------------------------------------------------------------
        | MASUKKAN PENJEMPUTAN KE AKTIVITAS
        |--------------------------------------------------------------------------
        */

        foreach ($penjemputan as $row) {

            /*
            | Metode
            */

            $metode =
                $row->metode ?? 'Manual';


            /*
            | Aktivitas berdasarkan status
            */

            if (
                strtolower($row->status) === 'selesai'
            ) {

                $namaAktivitas =
                    'Penjemputan Valid (' .
                    $metode .
                    ')';

            } elseif (
                strtolower($row->status) === 'terlambat'
            ) {

                $namaAktivitas =
                    'Penjemputan Terlambat (' .
                    $metode .
                    ')';

            } else {

                $namaAktivitas =
                    'Penjemputan ' .
                    ucfirst($row->status) .
                    ' (' .
                    $metode .
                    ')';
            }


            /*
            | Format subjek:
            |
            | Budi Santoso (Wali Noor Maulida)
            */

            $subjek =
                ($row->nama_wali ?? '-')
                .
                ' (Wali '
                .
                ($row->nama_siswa ?? '-')
                .
                ')';


            $aktivitas->push([

                'tanggal' =>
                    $row->tanggal,

                'waktu' =>
                    $row->jam_jemput
                    ?? '00:00:00',

                'aktivitas' =>
                    $namaAktivitas,

                'subjek' =>
                    $subjek,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | URUTKAN BERDASARKAN TANGGAL + WAKTU
        |--------------------------------------------------------------------------
        */

        $aktivitas = $aktivitas
            ->sortBy(function ($item) {

                return
                    $item['tanggal']
                    . ' '
                    . $item['waktu'];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | FORMAT UNTUK EXCEL
        |--------------------------------------------------------------------------
        */

        $result = [];

        foreach ($aktivitas as $item) {

            $result[] = [

                date(
                    'd-m-Y',
                    strtotime($item['tanggal'])
                ),

                date(
                    'H:i',
                    strtotime($item['waktu'])
                ),

                $item['aktivitas'],

                $item['subjek'],
            ];
        }


        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        return [
            'Tanggal',
            'Waktu',
            'Aktivitas',
            'Pengguna / Subjek',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | STYLE
    |--------------------------------------------------------------------------
    */

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(true);


        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getColumnDimension('A')
            ->setWidth(8);

        $sheet
            ->getColumnDimension('B')
            ->setWidth(18);

        $sheet
            ->getColumnDimension('C')
            ->setWidth(12);

        $sheet
            ->getColumnDimension('D')
            ->setWidth(38);

        $sheet
            ->getColumnDimension('E')
            ->setWidth(40);


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'B2',
            'LOG AKTIVITAS SISTEM REAL-TIME'
        );


        $sheet
            ->getStyle('B2')
            ->getFont()
            ->setSize(14)
            ->setBold(true)
            ->setColor(
                new Color('0D47A1')
            );


        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue(
            'B3',
            'Periode: ' . $this->bulan
        );


        $sheet
            ->getStyle('B3')
            ->getFont()
            ->setItalic(true);


        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getStyle('B5:E5')
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
                        'rgb' => '0D47A1'
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
            ->getRowDimension(5)
            ->setRowHeight(25);


        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        $lastRow = $sheet->getHighestRow();

        if ($lastRow >= 5) {

            $sheet
                ->getStyle(
                    "B5:E{$lastRow}"
                )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    Border::BORDER_THIN
                )
                ->getColor()
                ->setRGB('B0BEC5');
        }


        /*
        |--------------------------------------------------------------------------
        | ALIGNMENT DATA
        |--------------------------------------------------------------------------
        */

        if ($lastRow >= 6) {

            /*
            | Tanggal dan waktu center
            */

            $sheet
                ->getStyle(
                    "B6:C{$lastRow}"
                )
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );


            /*
            | Vertikal center semua data
            */

            $sheet
                ->getStyle(
                    "B6:E{$lastRow}"
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

        $sheet->freezePane('B6');
    }
}