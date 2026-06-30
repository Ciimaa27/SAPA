<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanWaliExport implements FromView
{
    protected $siswa;
    protected $laporan;
    protected $hadir;
    protected $izin;
    protected $sakit;
    protected $alpha;

    public function __construct($siswa, $laporan, $hadir, $izin, $sakit, $alpha)
    {
        $this->siswa = $siswa;
        $this->laporan = $laporan;
        $this->hadir = $hadir;
        $this->izin = $izin;
        $this->sakit = $sakit;
        $this->alpha = $alpha;
    }

    public function view(): View
    {
       return view('wali.laporan_excel', [
            'siswa' => $this->siswa,
            'laporan' => $this->laporan,
            'hadir' => $this->hadir,
            'izin' => $this->izin,
            'sakit' => $this->sakit,
            'alpha' => $this->alpha,
        ]);
    }
}