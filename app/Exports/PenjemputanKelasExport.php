<?php

namespace App\Exports;

use App\Models\Siswa;
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
    protected $tanggal;

    public function __construct($kelasId, $tanggal)
    {
        $this->kelasId = $kelasId;
        $this->tanggal = $tanggal;
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
        | QUERY REKAP PENJEMPUTAN
        |--------------------------------------------------------------------------
        */
        $siswas = Siswa::query()
            /* JOIN PENJEMPUTAN */
            ->leftJoin('penjemputan', 'siswa.id_siswa', '=', 'penjemputan.id_siswa')

            /* JOIN SISWA WALI */
            ->leftJoin('siswa_wali', function ($join) {
                $join->on('penjemputan.id_siswa', '=', 'siswa_wali.id_siswa');
                $join->on('penjemputan.id_wali', '=', 'siswa_wali.id_wali');
            })

            /* FILTER KELAS */
            ->where('siswa.id_kelas', $this->kelasId)

            /* SELECT DATA SISWA */
            ->select('siswa.id_siswa', 'siswa.nama_siswa')

            /* JUMLAH PENJEMPUTAN */
            ->selectRaw("
                COUNT(
                    CASE WHEN DATE(penjemputan.tanggal) = ? THEN penjemputan.id END
                ) AS jumlah_penjemputan
            ", [$this->tanggal])

            /* DIJEMPUT AYAH */
            ->selectRaw("
                COUNT(
                    CASE WHEN LOWER(siswa_wali.hubungan) = 'ayah' AND DATE(penjemputan.tanggal) = ? THEN penjemputan.id END
                ) AS ayah
            ", [$this->tanggal])

            /* DIJEMPUT IBU */
            ->selectRaw("
                COUNT(
                    CASE WHEN LOWER(siswa_wali.hubungan) = 'ibu' AND DATE(penjemputan.tanggal) = ? THEN penjemputan.id END
                ) AS ibu
            ", [$this->tanggal])

            /* DIJEMPUT WALI */
            ->selectRaw("
                COUNT(
                    CASE WHEN LOWER(siswa_wali.hubungan) = 'wali' AND DATE(penjemputan.tanggal) = ? THEN penjemputan.id END
                ) AS wali
            ", [$this->tanggal])

            /* GROUP SISWA */
            ->groupBy('siswa.id_siswa', 'siswa.nama_siswa')

            /* URUTKAN NAMA */
            ->orderBy('siswa.nama_siswa', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SUSUN DATA UNTUK EXCEL
        |--------------------------------------------------------------------------
        */
        $data = [];

        foreach ($siswas as $idx => $siswa) {
            $data[] = [
                $idx + 1,
                $siswa->nama_siswa,
                (int) $siswa->jumlah_penjemputan,
                (int) $siswa->ayah,
                (int) $siswa->ibu,
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
                $sheet = $event->sheet->getDelegate();

                /* HEADER TABEL */
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

                /* TINGGI HEADER */
                $sheet->getRowDimension(1)->setRowHeight(24);

                /* AMBIL BARIS TERAKHIR */
                $lastRow = $sheet->getHighestRow();

                /* BORDER TABEL */
                if ($lastRow >= 1) {
                    $sheet->getStyle("A1:F{$lastRow}")
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN)
                        ->getColor()
                        ->setRGB('BFBFBF');
                }

                /* ALIGNMENT DATA */
                if ($lastRow >= 2) {
                    /* NO */
                    $sheet->getStyle("A2:A{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    /* KOLOM ANGKA */
                    $sheet->getStyle("C2:F{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    /* VERTICAL CENTER */
                    $sheet->getStyle("A2:F{$lastRow}")
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                /* FREEZE HEADER */
                $sheet->freezePane('A2');
            },
        ];
    }
}
