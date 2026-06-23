<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Wali;
use App\Models\Relasi;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\Penjemputan;
use App\Models\JadwalPulang;
use App\Models\Notifikasi;
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
                'siswa' => null,
                'kehadiran' => null,
                'penjemputan' => null,
                'jadwal_pulang' => null,
            ]);
        }

        // Ambil data siswa
        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            return view('wali.dashboard', [
                'siswa' => null,
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

        // Map hari ke Bahasa Indonesia
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $hariMap[Carbon::today()->format('l')];

        // Ambil jadwal pulang berdasarkan kelas dan hari
        // Hanya tampilkan jika admin sudah mengubahnya (updated_at > created_at)
        $jadwal_pulang = JadwalPulang::where('kelas', $siswa->id_kelas)
            ->where('hari', $hariIni)
            ->whereRaw('updated_at > created_at')
            ->first();

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
<<<<<<< HEAD
    public function statusPenjemputan()
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            return redirect()->route('wali.dashboard')->with('error', 'Data wali tidak ditemukan.');
=======

    public function laporan()
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            return redirect()->route('login')->with('error', 'Data wali tidak ditemukan.');
>>>>>>> limau
        }

        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            return redirect()->route('wali.dashboard')->with('error', 'Data anak tidak ditemukan.');
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            return redirect()->route('wali.dashboard')->with('error', 'Data anak tidak ditemukan.');
        }

<<<<<<< HEAD
        $today = Carbon::today()->toDateString();

        // Ambil data kehadiran hari ini
        $kehadiran = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', $today)
            ->first();

        // Ambil data penjemputan hari ini
        $penjemputan = Penjemputan::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', $today)
            ->first();

        // Map hari ke Bahasa Indonesia
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $hariMap[Carbon::today()->format('l')];

        // Ambil jadwal pulang berdasarkan kelas dan hari
        // Hanya tampilkan jika admin sudah mengubahnya (updated_at > created_at)
        $jadwal_pulang = JadwalPulang::where('kelas', $siswa->id_kelas)
            ->where('hari', $hariIni)
            ->whereRaw('updated_at > created_at')
            ->first();

        // Buat riwayat timeline
        $riwayat = [];

        if ($kehadiran && $kehadiran->jam_masuk) {
            $riwayat[] = [
                'jam' => Carbon::parse($kehadiran->jam_masuk)->format('H:i'),
                'status' => 'Masuk',
            ];
        }

        if ($jadwal_pulang && $jadwal_pulang->jam) {
            $riwayat[] = [
                'jam' => $jadwal_pulang->jam,
                'status' => 'Jadwal pulang',
            ];
        }

        if ($penjemputan && $penjemputan->jam_jemput) {
            $riwayat[] = [
                'jam' => Carbon::parse($penjemputan->jam_jemput)->format('H:i'),
                'status' => 'Dijemput',
            ];
        }

        // Jika tidak ada data, tampilkan pesan
        if (empty($riwayat)) {
            $riwayat = [
                ['jam' => '-', 'status' => 'Tidak ada data hari ini'],
            ];
        }

        return view('wali.status-penjemputan', compact('siswa', 'riwayat', 'penjemputan', 'jadwal_pulang'));
    }

    public function notifikasi()
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        $notifikasiQuery = Notifikasi::query();

        if ($wali) {
            $notifikasiQuery->where(function ($query) use ($wali) {
                $query->where('id_wali', $wali->id_wali)
                    ->orWhere('id_user', auth()->id());
            });
        } else {
            $notifikasiQuery->where('id_user', auth()->id());
        }

        $notifikasi = $notifikasiQuery->orderByDesc('created_at')
            ->get()
            ->map(function ($notif) {
                return [
                    'judul' => $notif->judul,
                    'pesan' => $notif->pesan,
                    'waktu' => $this->formatWaktu($notif->created_at),
                ];
            });

        return view('wali.notifikasi', compact('notifikasi'));
    }

    public function jadwalPulang()
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

        // Ambil jadwal pulang untuk kelas siswa
        // Hanya tampilkan jika admin sudah mengubahnya (updated_at > created_at)
        $jadwal = JadwalPulang::where('kelas', $siswa->id_kelas)
            ->whereRaw('updated_at > created_at')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get();

        // Format data untuk view
        $jadwalList = $jadwal->map(function ($j) {
            return [
                'hari' => $j->hari,
                'jam' => $j->libur ? 'Libur' : ($j->jam ? \Carbon\Carbon::parse($j->jam)->format('H:i') . ' WIB' : '-'),
            ];
        });

        return view('wali.jadwal-pulang', compact('siswa', 'jadwalList'));
    }

    private function formatWaktu($created_at)
    {
        $now = Carbon::now();
        $diff = $now->diffInDays($created_at);

        if ($diff == 0) {
            return 'Hari ini';
        } elseif ($diff == 1) {
            return 'Kemarin';
        } else {
            return $created_at->translatedFormat('d F');
        }
=======
        $kehadiranReports = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->orderBy('tanggal', 'desc')
            ->get(['tanggal', 'status_hadir']);

        $penjemputanReports = Penjemputan::where('id_siswa', $siswa->id_siswa)
            ->orderBy('tanggal', 'desc')
            ->get(['tanggal', 'jam_jemput']);

        return view('wali.laporan', compact('siswa', 'kehadiranReports', 'penjemputanReports'));
    }

    public function statusPenjemputan()
    {
        $riwayat = [
            ['jam' => '13:12', 'status' => 'Dijemput'],
            ['jam' => '13:00', 'status' => 'Jadwal pulang'],
            ['jam' => '07:12', 'status' => 'Masuk'],
        ];

        return view('wali.status-penjemputan', compact('riwayat'));
>>>>>>> limau
    }
}
