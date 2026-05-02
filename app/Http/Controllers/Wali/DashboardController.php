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
}