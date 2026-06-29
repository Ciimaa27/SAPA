<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\KehadiranKelasExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjemputanKelasExport;


class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        [$tahun, $bulanAngka] = explode('-', $bulan);
        $kelasFilter = $request->filled('kelas') ? $request->kelas : null;

        $kelasOptions = Kelas::orderBy('nama_kelas')->get();

        $kelasQuery = Kelas::orderBy('nama_kelas');
        if ($kelasFilter) {
            $kelasQuery->where('id_kelas', $kelasFilter);
        }

        $kelas = $kelasQuery->paginate(10)->appends($request->query());

        $kehadiranQuery = Kehadiran::join('siswa', 'kehadiran.id_siswa', '=', 'siswa.id_siswa')
            ->whereYear('kehadiran.tanggal', $tahun)
            ->whereMonth('kehadiran.tanggal', $bulanAngka);

        $penjemputanQuery = Penjemputan::join('siswa', 'penjemputan.id_siswa', '=', 'siswa.id_siswa')
            ->whereYear('penjemputan.tanggal', $tahun)
            ->whereMonth('penjemputan.tanggal', $bulanAngka);

        if ($kelasFilter) {
            $kehadiranQuery->where('siswa.id_kelas', $kelasFilter);
            $penjemputanQuery->where('siswa.id_kelas', $kelasFilter);
        }

        $kehadiranCounts = $kehadiranQuery
            ->groupBy('siswa.id_kelas')
            ->select('siswa.id_kelas', DB::raw('COUNT(*) as total'))
            ->pluck('total', 'siswa.id_kelas');

        $penjemputanCounts = $penjemputanQuery
            ->groupBy('siswa.id_kelas')
            ->select('siswa.id_kelas', DB::raw('COUNT(*) as total'))
            ->pluck('total', 'siswa.id_kelas');

        return view('admin.laporan', compact('kelas', 'kelasOptions', 'bulan', 'kelasFilter', 'kehadiranCounts', 'penjemputanCounts'));
    }

    public function downloadKehadiran($id_siswa)
    {
        $record = Kehadiran::where('siswa_id', $id_siswa)
            ->latest()
            ->first();

        if (!$record) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $file = $record->file_path ?? $record->file ?? null;

        if ($file && file_exists(storage_path('app/' . $file))) {
            return response()->download(storage_path('app/' . $file));
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }

    public function downloadPenjemputan($id)
    {
        $record = Penjemputan::where('id_siswa', $id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_jemput', 'desc')
            ->first();

        if (!$record) {
            $record = Penjemputan::find($id);
        }

        if (!$record) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $file = $record->file_path ?? $record->file ?? null;

        if ($file && file_exists(storage_path('app/' . $file))) {
            return response()->download(storage_path('app/' . $file));
        }

        return redirect()->back()->with('error', 'File tidak ditemukan');
    }

    public function exportKehadiran(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);

        $bulan = $request->bulan ?? now()->format('Y-m');

        return Excel::download(
            new KehadiranKelasExport($id_kelas, $bulan),
            'Kehadiran_'.$kelas->nama_kelas.'_'.$bulan.'.xlsx'
        );
    }

    public function exportPenjemputan(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);

        $bulan = $request->bulan ?? now()->format('Y-m');

        return Excel::download(
            new PenjemputanKelasExport($id_kelas, $bulan),
            'Penjemputan_'.$kelas->nama_kelas.'_'.$bulan.'.xlsx'
        );
    }
}
