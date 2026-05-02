<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\LogTap;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ======================
        // DATA AKUN
        // ======================
        $totalSiswa = Siswa::count();
        $totalWali = User::where('id_role', 4)->count();
        $totalKepsek = User::where('id_role', 3)->count();
        $totalGuru = User::where('id_role', 2)->count();

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

        // ======================
        // LOG AKTIVITAS (ALL DEVICE)
        // ======================
        $logs = LogTap::with('device')
            ->latest('created_at') // urut dari terbaru
            ->take(5)              // hanya 5 entri terakhir
            ->get();
            
        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalWali',
            'totalGuru',
            'totalKepsek',
            'hadirHariIni',
            'tidakHadir',
            'sudahJemput',
            'belumJemput',
            'labels',
            'dataHadir',
            'dataTidakHadir',
            'logs'
        ));
    }

    public function dataAbsen()
    {
        return response()->json(
            \DB::table('kehadiran')
                ->join('siswa', 'kehadiran.id_siswa', '=', 'siswa.id_siswa')
                ->select(
                    'siswa.nama_siswa',
                    'kehadiran.tanggal',
                    'kehadiran.jam_masuk'
                )
                ->orderByDesc('kehadiran.tanggal')
                ->orderByDesc('kehadiran.jam_masuk')
                ->limit(20)
                ->get()
        );
    }
    }