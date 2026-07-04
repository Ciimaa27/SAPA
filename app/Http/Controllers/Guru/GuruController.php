<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\LogTap;
use App\Models\Wali;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function dashboard()
    {
        // ======================
        // DATA AKUN
        // ======================
        $totalSiswa = Siswa::count();
        $totalWali = User::where('id_role', 4)
            ->where('status', 'aktif')
            ->whereHas('wali', function ($query) {
                $query->where('is_active', 1);
            })
            ->count();

        // ======================
        // HARI INI
        // ======================
        $today = Carbon::now();

        $hadirHariIni = Kehadiran::whereDate('tanggal', $today)
            ->where('status_hadir', 'hadir')
            ->count();

        $tidakHadir = Kehadiran::whereDate('tanggal', $today)
            ->whereIn('status_hadir', ['sakit', 'izin', 'alpa'])
            ->count();

        // ======================
        // PENJEMPUTAN HARI INI
        // ======================
        $sudahJemput = Penjemputan::whereDate('tanggal', $today)->count();

        $belumJemput = Kehadiran::whereDate('tanggal', $today)
            ->where('status_hadir', 'hadir')
            ->whereNotIn('id_siswa', function($query) use ($today) {
                $query->select('id_siswa')
                    ->from('penjemputan')
                    ->whereDate('tanggal', $today);
            })
            ->count();

        return view('guru.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalWali' => $totalWali,
            'hadirHariIni' => $hadirHariIni,
            'tidakHadir' => $tidakHadir,
            'sudahJemput' => $sudahJemput,
            'belumJemput' => $belumJemput,
        ]);
    }

    public function kehadiran()
    {
        // Get paginated kelas with jumlah siswa
        $kelasList = Kelas::with('guru', 'siswa')
            ->paginate(10)
            ->through(function($kelas) {
                return [
                    'id_kelas' => $kelas->id_kelas,
                    'kelas' => $kelas->nama_kelas,
                    'wali' => $kelas->guru ? $kelas->guru->nama_guru : 'N/A',
                    'jumlah' => $kelas->siswa()->count(),
                ];
            });

        return view('guru.kehadiran', [
            'data' => $kelasList,
        ]);
    }

    public function detailKehadiran($id_kelas)
    {
        // Get kelas details with guru
        $kelas = Kelas::with('guru')->find($id_kelas);

        if (!$kelas) {
            abort(404, 'Kelas tidak ditemukan');
        }

        $tanggal = now()->format('Y-m-d');

        // Get all siswa in this kelas with their attendance for today
        $siswas = Siswa::where('id_kelas', $id_kelas)
            ->with(['kehadiran' => function($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal);
            }])
            ->get()
            ->map(function($siswa) use ($tanggal) {
                $kehadiran = $siswa->kehadiran->first();
                return (object)[
                    'id_siswa' => $siswa->id_siswa,
                    'nis' => $siswa->nis,
                    'nama_siswa' => $siswa->nama_siswa,
                    'status_hadir' => $kehadiran ? $kehadiran->status_hadir : null,
                ];
            });

        return view('guru.detail-kehadiran', [
            'kelas' => $kelas,
            'siswas' => $siswas,
            'tanggal' => $tanggal,
        ]);
    }

    public function updateDetailKehadiran(Request $request, $id_kelas)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'array',
            'status.*' => 'nullable|string',
        ]);

        $tanggal = $request->input('tanggal');
        $statusData = $request->input('status', []);

        foreach ($statusData as $id_siswa => $status_hadir) {
            $status_hadir = strtolower(trim($status_hadir));

            if ($status_hadir === '') {
                continue;
            }

            $validStatus = 'hadir';
            if ($status_hadir === 'i') {
                $validStatus = 'izin';
            } elseif ($status_hadir === 's') {
                $validStatus = 'sakit';
            } elseif ($status_hadir === 'a') {
                $validStatus = 'alpa';
            } elseif ($status_hadir === 'hadir' || $status_hadir === 'izin' || $status_hadir === 'sakit' || $status_hadir === 'alpa') {
                $validStatus = $status_hadir;
            }

            $kehadiran = Kehadiran::where('id_siswa', $id_siswa)
                ->where('tanggal', $tanggal)
                ->first();

            if ($kehadiran) {
                $kehadiran->update([
                    'status_hadir' => $validStatus,
                ]);
            } else {
                Kehadiran::create([
                    'id_siswa' => $id_siswa,
                    'id_device' => 1,
                    'tanggal' => $tanggal,
                    'metode' => 'manual',
                    'status_hadir' => $validStatus,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data kehadiran berhasil disimpan');
    }

    public function dataPenjemputan()
    {
        // Get paginated kelas with jumlah siswa
        $kelasList = Kelas::with('guru', 'siswa')
            ->paginate(10)
            ->through(function($kelas) {
                return [
                    'id_kelas' => $kelas->id_kelas,
                    'kelas' => $kelas->nama_kelas,
                    'wali' => $kelas->guru ? $kelas->guru->nama_guru : 'N/A',
                    'jumlah' => $kelas->siswa()->count(),
                ];
            });

        return view('guru.data-penjemputan', [
            'data' => $kelasList,
        ]);
    }

    public function penjemputan()
    {
        return redirect()->route('guru.data-penjemputan');
    }

    public function riwayatPenjemputan()
    {
        $today = now()->format('Y-m-d');

        $logs = Penjemputan::with('siswa', 'siswa.kelas')
            ->whereDate('tanggal', $today)
            ->orderByDesc('jam_jemput')
            ->paginate(10)
            ->through(function ($penjemputan) {
                return [
                    'waktu' => $penjemputan->tanggal . ' ' . ($penjemputan->jam_jemput ?? ''),
                    'id_scan' => $penjemputan->siswa ? 'FP-'.$penjemputan->siswa->id_siswa : '-',
                    'nama' => $penjemputan->siswa ? $penjemputan->siswa->nama_siswa : '-',
                    'alat' => $penjemputan->keterangan ?? 'Fingerprint',
                    'peran' => $penjemputan->siswa ? 'Siswa' : 'Tidak diketahui',
                    'status' => $penjemputan->status ?? '-',
                ];
            });

        return view('guru.riwayat-penjemputan', [
            'logs' => $logs,
        ]);
    }

    public function daftarPenjemputan($id_kelas)
        {
            $kelas = Kelas::with('guru')->findOrFail($id_kelas);

            $today = Carbon::today();

            $siswas = Siswa::where('id_kelas', $id_kelas)
                ->orderBy('nama_siswa')
                ->paginate(10)
                ->through(function ($siswa) use ($today) {

                    $penjemputan = Penjemputan::where(
                            'id_siswa',
                            $siswa->id_siswa
                        )
                        ->whereDate('tanggal', $today)
                        ->first();

                    return (object)[
                        'id_siswa' => $siswa->id_siswa,
                        'nis' => $siswa->nis,
                        'nama_siswa' => $siswa->nama_siswa,
                        'status' => $penjemputan
                            ? $penjemputan->status
                            : 'Menunggu',
                    ];
                });

            return view('guru.penjemputan', compact(
                'kelas',
                'siswas',
                'today'
            ));
        }

    public function updateStatusPenjemputan(Request $request)
{
    $request->validate([
        'id_siswa' => 'required',
        'tanggal'  => 'required|date',
        'status'   => 'required|in:Menunggu,Dijemput',
    ]);

    // Cari relasi siswa dengan wali
    $relasi = DB::table('siswa_wali')
        ->where('id_siswa', $request->id_siswa)
        ->first();

    if (!$relasi) {
        return back()->with(
            'error',
            'Relasi siswa dan wali tidak ditemukan.'
        );
    }

    // Cek data penjemputan siswa pada tanggal tersebut
    $penjemputan = DB::table('penjemputan')
        ->where('id_siswa', $request->id_siswa)
        ->whereDate('tanggal', $request->tanggal)
        ->first();

    if ($penjemputan) {

        if ($request->status === 'Dijemput') {
            DB::table('penjemputan')
                ->where('id', $penjemputan->id)
                ->update([
                    'status' => 'Dijemput',
                    'jam_jemput' => now()->format('H:i:s'),
                    'metode' => 'Manual',
                ]);
        } else {
            DB::table('penjemputan')
                ->where('id', $penjemputan->id)
                ->update([
                    'status' => 'Menunggu',
                ]);
        }

    } else {

        if ($request->status === 'Dijemput') {
            DB::table('penjemputan')->insert([
                'id_siswa'  => $request->id_siswa,
                'id_wali'   => $relasi->id_wali,
                'tanggal'   => $request->tanggal,
                'jam_jemput' => now()->format('H:i:s'),
                'status'    => 'Dijemput',
                'metode'    => 'Manual',
            ]);
        }
    }

    return back()->with(
        'success',
        'Status penjemputan berhasil diperbarui.'
    );
}
}
