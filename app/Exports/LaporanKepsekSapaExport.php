<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKepsekSapaExport implements WithMultipleSheets
{
    protected $bulan;
    protected $tanggal;

    public function __construct($bulan, $tanggal = null)
    {
        $this->bulan = $bulan; // Format: '2026-07'
        $this->tanggal = $tanggal ?? date('Y-m-d'); // Default tanggal hari ini
    }

    public function sheets(): array
    {
        return [
            new Sheets\DashboardSheet($this->bulan, $this->tanggal),
            new Sheets\RekapKehadiranSheet($this->bulan),
            new Sheets\RekapPenjemputanSheet($this->bulan),
            new Sheets\StatistikBulananSheet(),
            new Sheets\DataWaliSheet(),
            new Sheets\RiwayatAktivitasSheet($this->tanggal),
        ];
    }
}