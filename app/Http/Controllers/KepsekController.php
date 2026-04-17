<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\LogTap;
use App\Models\Kelas;
use Carbon\Carbon;

class KepsekController extends Controller
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
        // PENJEMPUTAN
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

        // ======================
        // GRAFIK MINGGUAN
        // ======================
        $labels = [];
        $dataHadir = [];
        $dataTidakHadir = [];

        $startWeek = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 6; $i++) {
            $date = $startWeek->copy()->addDays($i);
            $labels[] = $date->translatedFormat('D');

            $dataHadir[] = Kehadiran::whereDate('tanggal', $date)
                ->where('status_hadir', 'hadir')
                ->count();

            $dataTidakHadir[] = Kehadiran::whereDate('tanggal', $date)
                ->whereIn('status_hadir', ['sakit', 'izin', 'alpa'])
                ->count();
        }

        return view('kepsek.dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalWali' => $totalWali,
            'hadirHariIni' => $hadirHariIni,
            'tidakHadir' => $tidakHadir,
            'sudahJemput' => $sudahJemput,
            'belumJemput' => $belumJemput,
            'labels' => $labels,
            'dataHadir' => $dataHadir,
            'dataTidakHadir' => $dataTidakHadir,
        ]);
    }

    public function statistik()
    {
        $tanggal = request('tanggal', now()->format('Y-m-d'));
        $id_kelas = request('kelas');
        $status = request('status');

        // Query kehadiran dengan filter
        $query = Kehadiran::with('siswa.kelas');

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        if ($id_kelas) {
            $query->whereHas('siswa', function($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            });
        }

        if ($status && $status !== 'semua') {
            $query->where('status_hadir', $status);
        }

        $kehadiran = $query->get();


        // Statistik total
        $totalSiswa = $id_kelas ? Siswa::where('id_kelas', $id_kelas)->count() : Siswa::count();
        $hadir = $kehadiran->where('status_hadir', 'hadir')->count();
        $izin = $kehadiran->where('status_hadir', 'izin')->count();
        $sakit = $kehadiran->where('status_hadir', 'sakit')->count();
        $alpa = $kehadiran->where('status_hadir', 'alpa')->count();

        // Progress per kelas
        $kelasProgress = Kelas::with('siswa')->get()->map(function($kelas) use ($tanggal) {
            $totalSiswaKelas = $kelas->siswa->count();
            $hadirKelas = Kehadiran::whereDate('tanggal', $tanggal)
                ->where('status_hadir', 'hadir')
                ->whereHas('siswa', function($q) use ($kelas) {
                    $q->where('id_kelas', $kelas->id_kelas);
                })
                ->count();

            $persentase = $totalSiswaKelas > 0 ? round(($hadirKelas / $totalSiswaKelas) * 100) : 0;

            return [
                'nama_kelas' => $kelas->nama_kelas,
                'persentase' => $persentase,
                'hadir' => $hadirKelas,
                'total' => $totalSiswaKelas,
            ];
        });

        // Data untuk filter
        $kelasList = Kelas::all();

        return view('kepsek.statistik', [
            'totalSiswa' => $totalSiswa,
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'kelasProgress' => $kelasProgress,
            'kelasList' => $kelasList,
            'tanggal' => $tanggal,
            'selectedKelas' => $id_kelas,
            'selectedStatus' => $status,
        ]);
        }
}
