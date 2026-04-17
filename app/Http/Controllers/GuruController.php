<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\LogTap;
use App\Models\Wali;
use Carbon\Carbon;

class GuruController extends Controller
{
    public function dashboard()
    {
        // ======================
        // DATA AKUN
        // ======================
        $totalSiswa = Siswa::count();
        $totalWali = User::where('id_role', 4)->count();

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
        // Get all kelas with jumlah siswa
        $kelasList = Kelas::with('guru', 'siswa')
            ->get()
            ->map(function($kelas) {
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

        // Get all siswa in this kelas
        $siswas = Siswa::where('id_kelas', $id_kelas)->get();

        return view('guru.detail-kehadiran', [
            'kelas' => $kelas,
            'siswas' => $siswas,
            'tanggal' => now()->format('Y-m-d'),
        ]);
    }

    public function dataPenjemputan()
    {
        // Get all kelas with jumlah siswa (same as kehadiran)
        $kelasList = Kelas::with('guru', 'siswa')
            ->get()
            ->map(function($kelas) {
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

    public function riwayatPenjemputan()
    {
        $logs = Penjemputan::with('siswa', 'siswa.kelas')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_jemput')
            ->get()
            ->map(function ($penjemputan) {
                return [
                    'waktu' => $penjemputan->jam_jemput,
                    'id_scan' => $penjemputan->siswa ? 'FP-'.$penjemputan->siswa->id_siswa : '-',
                    'nama' => $penjemputan->siswa ? $penjemputan->siswa->nama_siswa : '-',
                    'alat' => 'Fingerprint',
                    'peran' => 'Siswa',
                    'status' => 'Berhasil',
                ];
            });

        return view('guru.riwayat-penjemputan', [
            'logs' => $logs,
        ]);
    }
}