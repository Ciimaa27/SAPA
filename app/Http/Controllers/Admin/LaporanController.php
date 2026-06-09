<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\KehadiranKelasExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenjemputanKelasExport;


class LaporanController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.laporan', compact('kelas'));
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
