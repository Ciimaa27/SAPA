<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PenjemputanWaliExport implements FromView
{
    protected $siswa;
    protected $penjemputan;
    protected $bulan;
    protected $tahun;

    public function __construct($siswa, $penjemputan, $bulan, $tahun)
    {
        $this->siswa = $siswa;
        $this->penjemputan = $penjemputan;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('wali.laporan_penjemputan_excel', [
            'siswa' => $this->siswa,
            'penjemputan' => $this->penjemputan,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
