<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArsipSiswa;
use App\Exports\ArsipSiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class ArsipSiswaController extends Controller
{
    public function index()
    {
        $tahunArsip = ArsipSiswa::select('tahun_lulus')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus', 'desc')
            ->get();

        return view('admin.arsip-siswa', compact('tahunArsip'));
    }

    public function showByYear($tahun)
    {
        $arsip = ArsipSiswa::where('tahun_lulus', $tahun)
            ->orderBy('id_arsip', 'desc')
            ->paginate(10);

        return view('admin.arsip-siswa-detail', compact('arsip', 'tahun'));
    }

    public function exportByYear($tahun)
    {
        return Excel::download(new ArsipSiswaExport($tahun), "arsip-siswa-{$tahun}.xlsx");
    }
}