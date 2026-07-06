<?php

namespace App\Exports;

use Carbon\Carbon;
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
            'Tanggal',
            'Nama Siswa',
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
        | 2026-07
        |
        | $tahun = 2026
        | $bulan = 07
        |
        */
        [$tahun, $bulan] = explode('-', $this->bulan);

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PERIODE LAPORAN
        |--------------------------------------------------------------------------
        */
        $awalBulan = Carbon::createFromFormat('Y-m-d', $this->bulan . '-01')->startOfDay();
        $akhirBulan = $awalBulan->copy()->endOfMonth()->endOfDay();
        $hariIni = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | JIKA BULAN YANG DIPILIH MASIH DI MASA DEPAN
        |--------------------------------------------------------------------------
        |
        | Tidak ada data yang boleh ditampilkan.
        |
        */
        if ($awalBulan->gt($hariIni)) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | BATAS AKHIR DATA
        |--------------------------------------------------------------------------
        |
        | Jika bulan laporan adalah bulan berjalan:
        | batas akhir = hari ini.
        |
        | Jika bulan laporan sudah lewat:
        | batas akhir = akhir bulan.
        |
        */
        $batasAkhir = $akhirBulan->gt($hariIni) ? $hariIni : $akhirBulan;

        /*
        |--------------------------------------------------------------------------
        | QUERY DATA PENJEMPUTAN PER HARI
        |--------------------------------------------------------------------------
        */
        $penjemputans = DB::table('penjemputan')
            /*
            |--------------------------------------------------------------------------
            | JOIN SISWA
            |--------------------------------------------------------------------------
            */
            ->join('siswa', 'penjemputan.id_siswa', '=', 'siswa.id_siswa')
            /*
            |--------------------------------------------------------------------------
            | JOIN SISWA WALI
            |--------------------------------------------------------------------------
            */
            ->leftJoin('siswa_wali', function ($join) {
                $join->on('penjemputan.id_siswa', '=', 'siswa_wali.id_siswa');
                $join->on('penjemputan.id_wali', '=', 'siswa_wali.id_wali');
            })
            /*
            |--------------------------------------------------------------------------
            | FILTER KELAS
            |--------------------------------------------------------------------------
            */
            ->where('siswa.id_kelas', $this->kelasId)
            /*
            |--------------------------------------------------------------------------
            | FILTER AWAL BULAN
            |--------------------------------------------------------------------------
            */
            ->whereDate('penjemputan.tanggal', '>=', $awalBulan->format('Y-m-d'))
            /*
            |--------------------------------------------------------------------------
            | FILTER SAMPAI BATAS AKHIR
            |--------------------------------------------------------------------------
            */
            ->whereDate('penjemputan.tanggal', '<=', $batasAkhir->format('Y-m-d'))
            /*
            |--------------------------------------------------------------------------
            | PILIH DATA
            |--------------------------------------------------------------------------
            */
            ->select(
                'penjemputan.tanggal',
                'penjemputan.status',
                'siswa.nama_siswa',
                'siswa_wali.hubungan'
            )
            /*
            |--------------------------------------------------------------------------
            | URUTKAN BERDASARKAN TANGGAL
            |--------------------------------------------------------------------------
            */
            ->orderBy('penjemputan.tanggal', 'asc')
            /*
            |--------------------------------------------------------------------------
            | URUTKAN NAMA SISWA
            |--------------------------------------------------------------------------
            */
            ->orderBy('siswa.nama_siswa', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA UNTUK EXCEL
        |--------------------------------------------------------------------------
        */
        $data = [];

        foreach ($penjemputans as $index => $item) {
            /*
            |--------------------------------------------------------------------------
            | STATUS PENJEMPUTAN
            |--------------------------------------------------------------------------
            */
            $status = strtolower(trim($item->status ?? ''));

            /*
            |--------------------------------------------------------------------------
            | HUBUNGAN PENJEMPUT
            |--------------------------------------------------------------------------
            */
            $hubungan = strtolower(trim($item->hubungan ?? ''));

            /*
            |--------------------------------------------------------------------------
            | MASUKKAN DATA
            |--------------------------------------------------------------------------
            */
            $data[] = [
                /*
                | No
                */
                $index + 1,

                /*
                | Tanggal
                */
                date('d-m-Y', strtotime($item->tanggal)),

                /*
                | Nama Siswa
                */
                $item->nama_siswa,

                /*
                | Tepat Waktu
                */
                $status === 'tepat waktu' ? 1 : 0,

                /*
                | Terlambat
                */
                $status === 'terlambat' ? 1 : 0,

                /*
                | Ayah
                */
                $hubungan === 'ayah' ? 1 : 0,

                /*
                | Ibu
                */
                $hubungan === 'ibu' ? 1 : 0,

                /*
                | Wali
                */
                $hubungan === 'wali' ? 1 : 0,
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
                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | STYLE HEADER
                |--------------------------------------------------------------------------
                */
                $sheet->getStyle('A1:H1')->applyFromArray([
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

                /*
                |--------------------------------------------------------------------------
                | TINGGI HEADER
                |--------------------------------------------------------------------------
                */
                $sheet->getRowDimension(1)->setRowHeight(26);

                /*
                |--------------------------------------------------------------------------
                | BARIS TERAKHIR
                |--------------------------------------------------------------------------
                */
                $lastRow = $sheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | BORDER SEMUA DATA
                |--------------------------------------------------------------------------
                */
                if ($lastRow >= 1) {
                    $sheet->getStyle("A1:H{$lastRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
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
                    $sheet->getStyle("A2:A{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    /*
                    | Tanggal
                    */
                    $sheet->getStyle("B2:B{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    /*
                    | Status dan Penjemput
                    */
                    $sheet->getStyle("D2:H{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    /*
                    | Vertical Center
                    */
                    $sheet->getStyle("A2:H{$lastRow}")
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */
                $sheet->freezePane('A2');

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */
                $sheet->setAutoFilter("A1:H{$lastRow}");
            },
        ];
    }
}
