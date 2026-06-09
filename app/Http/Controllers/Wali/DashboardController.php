<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Wali;
use App\Models\Relasi;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\JadwalPulang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil wali berdasarkan user yang login
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            return redirect()->route('login')->with('error', 'Data wali tidak ditemukan.');
        }

        // Ambil relasi siswa-wali
        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            return view('wali.dashboard', [
                'anak' => null,
                'kehadiran' => null,
                'penjemputan' => null,
                'jadwal_pulang' => null,
            ]);
        }

        // Ambil data siswa
        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            return view('wali.dashboard', [
                'anak' => null,
                'kehadiran' => null,
                'penjemputan' => null,
                'jadwal_pulang' => null,
            ]);
        }

        // Ambil kehadiran hari ini
        $kehadiran = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', Carbon::today()->toDateString())
            ->first();

        // Ambil penjemputan hari ini
        $penjemputan = Penjemputan::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', Carbon::today()->toDateString())
            ->first();

        // Ambil jadwal pulang berdasarkan kelas
        $jadwal_pulang = JadwalPulang::where('kelas', $siswa->id_kelas)->first();

        return view('wali.dashboard', compact('siswa', 'kehadiran', 'penjemputan', 'jadwal_pulang'));
    }

    public function kehadiran()
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            return redirect()->route('wali.dashboard')->with('error', 'Data wali tidak ditemukan.');
        }

        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            return redirect()->route('wali.dashboard')->with('error', 'Data anak tidak ditemukan.');
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            return redirect()->route('wali.dashboard')->with('error', 'Data anak tidak ditemukan.');
        }

        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $records = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->keyBy('tanggal');

        $calendarStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $calendarDays = [];
        for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
            $record = $records->get($date->toDateString());
            $status = $record ? strtolower(trim($record->status_hadir)) : null;
            $calendarDays[] = [
                'date' => $date->copy(),
                'currentMonth' => $date->month === $today->month,
                'status' => $status,
            ];
        }

        // Normalize counts by lowercasing stored values to match CSS classes
        $stats = [
            'hadir' => $records->filter(function ($r) { return strtolower(trim($r->status_hadir)) === 'hadir'; })->count(),
            'sakit' => $records->filter(function ($r) { return strtolower(trim($r->status_hadir)) === 'sakit'; })->count(),
            'izin' => $records->filter(function ($r) { return strtolower(trim($r->status_hadir)) === 'izin'; })->count(),
            'alpa' => $records->filter(function ($r) { return strtolower(trim($r->status_hadir)) === 'alpa'; })->count(),
        ];

        return view('wali.kehadiran', compact('siswa', 'today', 'calendarDays', 'stats'));
    }
}