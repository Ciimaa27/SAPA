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
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanWaliExport;
use App\Exports\PenjemputanWaliExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
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
    public function statusPenjemputan()
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

        $records = Penjemputan::where('id_siswa', $siswa->id_siswa)
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->get()
            ->keyBy('tanggal');

        $calendarStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $calendarDays = [];
        for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
            $record = $records->get($date->toDateString());
            $calendarDays[] = [
                'date' => $date->copy(),
                'currentMonth' => $date->month === $today->month,
                'status' => $record ? 'jemput' : null,
                'jam_jemput' => $record ? Carbon::parse($record->jam_jemput)->format('H:i') : null,
            ];
        }

        $stats = [
            'jemput' => $records->count(),
        ];

        $penjemputanToday = Penjemputan::where('id_siswa', $siswa->id_siswa)
            ->where('tanggal', $today->toDateString())
            ->first();

        return view('wali.status-penjemputan', compact('siswa', 'today', 'calendarDays', 'stats', 'penjemputanToday'));
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
    }

    public function laporan(Request $request)
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            return redirect()->route('wali.dashboard');
        }

        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            return redirect()->route('wali.dashboard');
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            return redirect()->route('wali.dashboard');
        }

        // Data laporan kehadiran per bulan
        $kehadiranReports = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->select(
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('MAX(tanggal) as tanggal')
            )
            ->groupBy(DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'))
            ->orderByDesc(DB::raw('YEAR(tanggal)'))
            ->orderByDesc(DB::raw('MONTH(tanggal)'))
            ->get();

        // Data laporan penjemputan per bulan
        $penjemputanReports = Penjemputan::where('id_siswa', $siswa->id_siswa)
            ->select(
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('MAX(tanggal) as tanggal')
            )
            ->groupBy(DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'))
            ->orderByDesc(DB::raw('YEAR(tanggal)'))
            ->orderByDesc(DB::raw('MONTH(tanggal)'))
            ->get();

        return view('wali.laporan', compact(
            'siswa',
            'kehadiranReports',
            'penjemputanReports'
        ));
    }

    public function downloadLaporan($bulan,$tahun)
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            abort(404);
        }

        $relasi = Relasi::where('id_wali',$wali->id_wali)->first();

        if (!$relasi) {
            abort(404);
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        $kehadiranReports = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->select(
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('MAX(tanggal) as tanggal')
            )
            ->groupBy(
                DB::raw('YEAR(tanggal)'),
                DB::raw('MONTH(tanggal)')
            )
            ->orderByDesc(DB::raw('YEAR(tanggal)'))
            ->orderByDesc(DB::raw('MONTH(tanggal)'))
            ->get();

        $penjemputan = Penjemputan::where('id_siswa',$siswa->id_siswa)
            ->whereMonth('tanggal',$bulan)
            ->whereYear('tanggal',$tahun)
            ->orderBy('tanggal')
            ->get();

        $hadir = $kehadiran->where('status_hadir','Hadir')->count();
        $izin = $kehadiran->where('status_hadir','Izin')->count();
        $sakit = $kehadiran->where('status_hadir','Sakit')->count();
        $alpa = $kehadiran->where('status_hadir','Alpa')->count();

        $totalHari = $kehadiran->count();

        $persentase = $totalHari > 0
            ? round(($hadir/$totalHari)*100)
            : 0;

        $tepat = $penjemputan->count();
        $terlambat = 0;

        $pdf = Pdf::loadView(
            'pdf.laporan-kehadiran',
            compact(
                'siswa',
                'kehadiran',
                'penjemputan',
                'bulan',
                'tahun',
                'hadir',
                'izin',
                'sakit',
                'alpa',
                'totalHari',
                'persentase',
                'tepat',
                'terlambat'
            )
        );

        $pdf->setPaper('A4','portrait');

        return $pdf->download(
            'Laporan_'.$siswa->nama_siswa.'.pdf'
        );
    }

    public function exportPdf($bulan, $tahun)
{
    $wali = Wali::where('id_user', auth()->id())->first();

    if (!$wali) {
        abort(404);
    }

    $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

    if (!$relasi) {
        abort(404);
    }

    $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

    if (!$siswa) {
        abort(404);
    }

    // ===========================
    // Data Kehadiran
    // ===========================
    $kehadiran = Kehadiran::where('id_siswa', $siswa->id_siswa)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->orderBy('tanggal')
        ->get();

    // ===========================
    // Data Penjemputan
    // ===========================
    $penjemputan = Penjemputan::where('id_siswa', $siswa->id_siswa)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->orderBy('tanggal')
        ->get();

    // ===========================
    // Statistik Kehadiran
    // ===========================
    $hadir = $kehadiran->filter(fn($item) => strtolower($item->status_hadir) == 'hadir')->count();

    $izin = $kehadiran->filter(fn($item) => strtolower($item->status_hadir) == 'izin')->count();

    $sakit = $kehadiran->filter(fn($item) => strtolower($item->status_hadir) == 'sakit')->count();

    $alpa = $kehadiran->filter(fn($item) => strtolower($item->status_hadir) == 'alpa')->count();

    $totalHari = $kehadiran->count();

    $persentase = $totalHari > 0
        ? round(($hadir / $totalHari) * 100)
        : 0;

    // ===========================
    // Statistik Penjemputan
    // ===========================
    $tepat = $penjemputan
        ->filter(fn($item) => strtolower($item->status_penjemputan) == 'tepat waktu')
        ->count();

    $terlambat = $penjemputan
        ->filter(fn($item) => strtolower($item->status_penjemputan) == 'terlambat')
        ->count();

    // ===========================
    // bulan dalam indonesia
    // ===========================

    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];


    // ===========================
    // Generate PDF
    // ===========================
    $pdf = Pdf::loadView(
    'pdf.laporan-kehadiran',
        compact(
            'siswa',
            'wali',
            'kehadiran',
            'penjemputan',
            'bulan',
            'tahun',
            'hadir',
            'izin',
            'sakit',
            'alpa',
            'totalHari',
            'persentase',
            'tepat',
            'terlambat'
        )
    );

    $pdf->setPaper('A4', 'portrait');

    return $pdf->download(
        'Laporan_' .
        $siswa->nama_siswa .
        '_' .
        $bulan .
        '_' .
        $tahun .
        '.pdf'
    );
}

    public function exportPdfPenjemputan($bulan, $tahun)
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            abort(404);
        }

        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            abort(404);
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            abort(404);
        }

        $penjemputan = Penjemputan::with('wali')
            ->where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $tepat = $penjemputan
            ->where('status_penjemputan', 'Tepat Waktu')
            ->count();

        $terlambat = $penjemputan
            ->where('status_penjemputan', 'Terlambat')
            ->count();

        $belum = 0;

        $penjemputanDetails = $penjemputan->map(function ($item) use ($siswa) {
            $hari = Carbon::parse($item->tanggal)
                ->locale('id')
                ->translatedFormat('l');

            $jadwal = JadwalPulang::where('kelas', $siswa->id_kelas)
                ->where('hari', $hari)
                ->whereRaw('updated_at > created_at')
                ->first();

            return [
                'tanggal' => $item->tanggal,
                'jam_pulang' => $jadwal && $jadwal->jam ? Carbon::parse($jadwal->jam)->format('H:i') : '-',
                'jam_jemput' => Carbon::parse($item->jam_jemput)->format('H:i'),
                'nama_penjemput' => $item->wali?->nama_wali ?? '-',
                'status' => $item->status_penjemputan,
            ];
        });

        $pdf = Pdf::loadView(
            'pdf.laporan-penjemputan',
            compact(
                'siswa',
                'wali',
                'penjemputanDetails',
                'bulan',
                'tahun',
                'tepat',
                'terlambat',
                'belum'
            )
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            'Laporan_Penjemputan_' .
            $siswa->nama_siswa .
            '_' .
            $bulan .
            '_' .
            $tahun .
            '.pdf'
        );
    }

    public function exportExcelPenjemputan($bulan, $tahun)
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            abort(404);
        }

        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            abort(404);
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        if (!$siswa) {
            abort(404);
        }

        $penjemputan = Penjemputan::with('wali')
            ->where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $tepat = $penjemputan
            ->where('status_penjemputan', 'Tepat Waktu')
            ->count();

        $terlambat = $penjemputan
            ->where('status_penjemputan', 'Terlambat')
            ->count();

        return Excel::download(
            new PenjemputanWaliExport(
                $siswa,
                $penjemputan,
                $tepat,
                $terlambat
            ),
            'Laporan_Penjemputan.xlsx'
        );
    }

    public function exportExcel($bulan, $tahun)
    {
        $wali = Wali::where('id_user', auth()->id())->first();

        if (!$wali) {
            abort(404);
        }

        $relasi = Relasi::where('id_wali', $wali->id_wali)->first();

        if (!$relasi) {
            abort(404);
        }

        $siswa = Siswa::with('kelas')->find($relasi->id_siswa);

        $laporan = Kehadiran::where('id_siswa', $siswa->id_siswa)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $hadir = $laporan->filter(fn($item) => strtolower($item->status_hadir) == 'hadir')->count();
        $izin  = $laporan->filter(fn($item) => strtolower($item->status_hadir) == 'izin')->count();
        $sakit = $laporan->filter(fn($item) => strtolower($item->status_hadir) == 'sakit')->count();
        $alpha = $laporan->filter(fn($item) => strtolower($item->status_hadir) == 'alpa')->count();
        return Excel::download(
            new LaporanWaliExport(
                $siswa,
                $laporan,
                $hadir,
                $izin,
                $sakit,
                $alpha
            ),
            'Laporan_Kehadiran.xlsx'
        );
    }
}

