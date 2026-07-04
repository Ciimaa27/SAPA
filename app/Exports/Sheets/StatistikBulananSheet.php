<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class StatistikBulananSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithCharts, WithCustomStartCell
{
    public function title(): string
    {
        return 'Statistik Bulanan';
    }

    public function startCell(): string
    {
        return 'B5';
    }

    public function array(): array
    {
        $rows = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');

            $hadir = DB::table('kehadiran')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', $bulan)
                ->where('status_hadir', 'hadir')
                ->count();

            $tidakHadir = DB::table('kehadiran')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', $bulan)
                ->whereIn('status_hadir', ['sakit', 'izin', 'alpa'])
                ->count();

            $penjemputan = DB::table('penjemputan')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', $bulan)
                ->count();

            $rows[] = [$namaBulan, $hadir, $tidakHadir, $penjemputan];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Bulan', 'Hadir', 'Tidak Hadir', 'Penjemputan'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setShowGridlines(true);

        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(14);

        $sheet->setCellValue('B2', 'TREN STATISTIK BULANAN');
        $sheet->getStyle('B2')->getFont()->setSize(14)->setBold(true)->setColor(new Color('0D47A1'));

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('B5:E5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D47A1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle("B5:E{$lastRow}")
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setRGB('B0BEC5');
    }

    public function charts(): array
    {
        /*
        |--------------------------------------------------------------------------
        | KATEGORI BULAN
        |--------------------------------------------------------------------------
        */

        $categories = [
            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                "'Statistik Bulanan'!\$B\$6:\$B\$17",
                null,
                12
            ),
        ];


        /*
        |--------------------------------------------------------------------------
        | CHART 1: TREN KEHADIRAN
        |--------------------------------------------------------------------------
        */

        $kehadiranValues = [

            // Hadir
            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER,
                "'Statistik Bulanan'!\$C\$6:\$C\$17",
                null,
                12
            ),

            // Tidak Hadir
            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER,
                "'Statistik Bulanan'!\$D\$6:\$D\$17",
                null,
                12
            ),
        ];


        $kehadiranLabels = [

            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                "'Statistik Bulanan'!\$C\$5",
                null,
                1
            ),

            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                "'Statistik Bulanan'!\$D\$5",
                null,
                1
            ),
        ];


        $kehadiranSeries = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($kehadiranValues) - 1),
            $kehadiranLabels,
            $categories,
            $kehadiranValues
        );


        $kehadiranPlotArea = new PlotArea(
            null,
            [$kehadiranSeries]
        );


        $kehadiranLegend = new Legend(
            Legend::POSITION_RIGHT,
            null,
            false
        );


        $chartKehadiran = new Chart(
            'chartKehadiran',
            new Title('Tren Kehadiran Siswa'),
            $kehadiranLegend,
            $kehadiranPlotArea
        );


        $chartKehadiran->setTopLeftPosition('B20');

        $chartKehadiran->setBottomRightPosition('H34');


        /*
        |--------------------------------------------------------------------------
        | CHART 2: TREN PENJEMPUTAN
        |--------------------------------------------------------------------------
        */

        $penjemputanValues = [

            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER,
                "'Statistik Bulanan'!\$E\$6:\$E\$17",
                null,
                12
            ),
        ];


        $penjemputanLabels = [

            new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                "'Statistik Bulanan'!\$E\$5",
                null,
                1
            ),
        ];


        $penjemputanSeries = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            [0],
            $penjemputanLabels,
            $categories,
            $penjemputanValues
        );


        $penjemputanPlotArea = new PlotArea(
            null,
            [$penjemputanSeries]
        );


        $penjemputanLegend = new Legend(
            Legend::POSITION_RIGHT,
            null,
            false
        );


        $chartPenjemputan = new Chart(
            'chartPenjemputan',
            new Title('Tren Penjemputan Siswa'),
            $penjemputanLegend,
            $penjemputanPlotArea
        );


        /*
        |--------------------------------------------------------------------------
        | POSISI CHART PENJEMPUTAN
        |--------------------------------------------------------------------------
        */

        $chartPenjemputan->setTopLeftPosition('B36');

        $chartPenjemputan->setBottomRightPosition('H50');


        /*
        |--------------------------------------------------------------------------
        | RETURN DUA CHART
        |--------------------------------------------------------------------------
        */

        return [
            $chartKehadiran,
            $chartPenjemputan,
        ];
    }
}
