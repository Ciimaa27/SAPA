<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\KehadiranKelasExport;
use App\Exports\PenjemputanKelasExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $kelasFilter = $request->filled('kelas') ? $request->kelas : null;
=======
        // Filter per hari
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $kelasFilter = $request->filled('kelas')
            ? $request->kelas
            : null;
>>>>>>> origin/isma

        $kelasOptions = Kelas::orderBy('nama_kelas')->get();

        $kelasQuery = Kelas::orderBy('nama_kelas');

        if ($kelasFilter) {
            $kelasQuery->where('id_kelas', $kelasFilter);
        }

        $kelas = $kelasQuery
            ->paginate(10)
            ->appends($request->query());

<<<<<<< HEAD
        $kehadiranQuery = Kehadiran::join('siswa', 'kehadiran.id_siswa', '=', 'siswa.id_siswa')
            ->whereDate('kehadiran.tanggal', $tanggal);

        $penjemputanQuery = Penjemputan::join('siswa', 'penjemputan.id_siswa', '=', 'siswa.id_siswa')
            ->whereDate('penjemputan.tanggal', $tanggal);
=======

        // ==========================
        // QUERY KEHADIRAN PER HARI
        // ==========================

        $kehadiranQuery = Kehadiran::join(
                'siswa',
                'kehadiran.id_siswa',
                '=',
                'siswa.id_siswa'
            )
            ->whereDate('kehadiran.tanggal', $tanggal);


        // ==========================
        // QUERY PENJEMPUTAN PER HARI
        // ==========================

        $penjemputanQuery = Penjemputan::join(
                'siswa',
                'penjemputan.id_siswa',
                '=',
                'siswa.id_siswa'
            )
            ->whereDate('penjemputan.tanggal', $tanggal);


        // ==========================
        // FILTER KELAS
        // ==========================
>>>>>>> origin/isma

        if ($kelasFilter) {

            $kehadiranQuery->where(
                'siswa.id_kelas',
                $kelasFilter
            );

            $penjemputanQuery->where(
                'siswa.id_kelas',
                $kelasFilter
            );
        }


        // ==========================
        // JUMLAH KEHADIRAN
        // ==========================

        $kehadiranCounts = $kehadiranQuery
            ->groupBy('siswa.id_kelas')
            ->select(
                'siswa.id_kelas',
                DB::raw('COUNT(*) as total')
            )
            ->pluck(
                'total',
                'siswa.id_kelas'
            );


        // ==========================
        // JUMLAH PENJEMPUTAN
        // ==========================

        $penjemputanCounts = $penjemputanQuery
            ->groupBy('siswa.id_kelas')
            ->select(
                'siswa.id_kelas',
                DB::raw('COUNT(*) as total')
            )
            ->pluck(
                'total',
                'siswa.id_kelas'
            );

<<<<<<< HEAD
=======

>>>>>>> origin/isma
        return view('admin.laporan', compact(
            'kelas',
            'kelasOptions',
            'tanggal',
            'kelasFilter',
            'kehadiranCounts',
            'penjemputanCounts'
        ));
    }


    public function downloadKehadiran($id_siswa)
    {
        $record = Kehadiran::where(
                'siswa_id',
                $id_siswa
            )
            ->latest()
            ->first();

        if (!$record) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'File tidak ditemukan'
                );
        }

        $file = $record->file_path
            ?? $record->file
            ?? null;

        if (
            $file &&
            file_exists(storage_path('app/' . $file))
        ) {
            return response()->download(
                storage_path('app/' . $file)
            );
        }

        return redirect()
            ->back()
            ->with(
                'error',
                'File tidak ditemukan'
            );
    }


    public function downloadPenjemputan($id)
    {
        $record = Penjemputan::where(
                'id_siswa',
                $id
            )
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_jemput', 'desc')
            ->first();

        if (!$record) {
            $record = Penjemputan::find($id);
        }

        if (!$record) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'File tidak ditemukan'
                );
        }

        $file = $record->file_path
            ?? $record->file
            ?? null;

        if (
            $file &&
            file_exists(storage_path('app/' . $file))
        ) {
            return response()->download(
                storage_path('app/' . $file)
            );
        }

        return redirect()
            ->back()
            ->with(
                'error',
                'File tidak ditemukan'
            );
    }


    // ==========================
    // EXPORT KEHADIRAN PER HARI
    // ==========================

    public function exportKehadiran(
        Request $request,
        $id_kelas
    ) {
        $kelas = Kelas::findOrFail($id_kelas);

        $tanggal = $request->tanggal
            ?? now()->format('Y-m-d');

        return Excel::download(
            new KehadiranKelasExport(
                $id_kelas,
                $tanggal
            ),
            'Kehadiran_'
                . $kelas->nama_kelas
                . '_'
                . $tanggal
                . '.xlsx'
        );
    }


    // ==========================
    // EXPORT PENJEMPUTAN PER HARI
    // ==========================

    public function exportPenjemputan(
        Request $request,
        $id_kelas
    ) {
        $kelas = Kelas::findOrFail($id_kelas);

        $tanggal = $request->tanggal
            ?? now()->format('Y-m-d');

        return Excel::download(
            new PenjemputanKelasExport(
                $id_kelas,
                $tanggal
            ),
            'Penjemputan_'
                . $kelas->nama_kelas
                . '_'
                . $tanggal
                . '.xlsx'
        );
    }
}