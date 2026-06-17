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
        $tahunArsip = ArsipSiswa::select(
        'tahun_lulus',
        'status'
        )
        ->selectRaw('COUNT(*) as total')
        ->groupBy('tahun_lulus', 'status')
        ->orderBy('tahun_lulus', 'desc')
        ->get();

        return view('admin.arsip-siswa', compact('tahunArsip'));
    }

    public function showByYear($tahun, $status)
    {
        $arsip = ArsipSiswa::where('tahun_lulus', $tahun)
            ->where('status', $status)
            ->orderBy('id_arsip', 'desc')
            ->paginate(10);

        return view(
            'admin.arsip-siswa-detail',
            compact('arsip', 'tahun', 'status')
        );
    }

    public function exportByYear($tahun, $status)
    {
        return Excel::download(
            new ArsipSiswaExport($tahun, $status),
            "arsip-siswa-{$status}-{$tahun}.xlsx"
        );
}
}