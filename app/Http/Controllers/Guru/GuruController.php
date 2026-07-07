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
use App\Services\FonnteService;
class GuruController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }
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

public function kehadiran(Request $request)
{
    $cari = $request->query('cari');

    $kelasList = Kelas::with(['guru', 'siswa'])
        ->when($cari, function ($query) use ($cari) {
            $query->where(function ($q) use ($cari) {

                // Cari nama kelas
                $q->where('nama_kelas', 'like', '%' . $cari . '%')

                  // Cari nama wali kelas / guru
                  ->orWhereHas('guru', function ($guru) use ($cari) {
                      $guru->where(
                          'nama_guru',
                          'like',
                          '%' . $cari . '%'
                      );
                  });
            });
        })
        ->paginate(10)
        ->withQueryString()
        ->through(function ($kelas) {
            return [
                'id_kelas' => $kelas->id_kelas,
                'kelas' => $kelas->nama_kelas,
                'wali' => $kelas->guru
                    ? $kelas->guru->nama_guru
                    : 'N/A',
                'jumlah' => $kelas->siswa->count(),
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

    public function dataPenjemputan(Request $request)
{
    $cari = $request->query('cari');

    $kelasList = Kelas::with(['guru', 'siswa'])
        ->when($cari, function ($query) use ($cari) {
            $query->where(function ($q) use ($cari) {

                // Cari berdasarkan nama kelas
                $q->where('nama_kelas', 'like', '%' . $cari . '%')

                  // Cari berdasarkan nama wali kelas
                  ->orWhereHas('guru', function ($guru) use ($cari) {
                      $guru->where(
                          'nama_guru',
                          'like',
                          '%' . $cari . '%'
                      );
                  });

            });
        })
        ->orderBy('nama_kelas')
        ->paginate(10)
        ->withQueryString()
        ->through(function ($kelas) {
            return [
                'id_kelas' => $kelas->id_kelas,
                'kelas' => $kelas->nama_kelas,
                'wali' => $kelas->guru
                    ? $kelas->guru->nama_guru
                    : 'N/A',
                'jumlah' => $kelas->siswa->count(),
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

        $logs = DB::table('penjemputan')
            ->join(
                'siswa',
                'penjemputan.id_siswa',
                '=',
                'siswa.id_siswa'
            )
            ->leftJoin(
                'wali',
                'penjemputan.id_wali',
                '=',
                'wali.id_wali'
            )
            ->leftJoin('siswa_wali', function ($join) {
                $join->on(
                    'penjemputan.id_siswa',
                    '=',
                    'siswa_wali.id_siswa'
                );

                $join->on(
                    'penjemputan.id_wali',
                    '=',
                    'siswa_wali.id_wali'
                );
            })
            ->whereDate(
                'penjemputan.tanggal',
                $today
            )
            ->select(
                'penjemputan.id',
                'penjemputan.tanggal',
                'penjemputan.jam_jemput',
                'penjemputan.status',
                'penjemputan.metode',
                'siswa.nama_siswa',
                'wali.nama_wali',
                'wali.fingerprint_id',
                'siswa_wali.hubungan'
            )
            ->orderByDesc('penjemputan.jam_jemput')
            ->paginate(10);

        return view('guru.riwayat-penjemputan', compact('logs'));
    }

    public function daftarPenjemputan($id_kelas)
{
    $kelas = Kelas::with('guru')->findOrFail($id_kelas);
    $today = Carbon::today();

    $siswas = Siswa::where('id_kelas', $id_kelas)
        ->orderBy('nama_siswa')
        ->paginate(10)
        ->through(function ($siswa) use ($today) {
            $penjemputan = Penjemputan::where('id_siswa', $siswa->id_siswa)
                ->whereDate('tanggal', $today)
                ->first();

            return (object) [
                'id_siswa'   => $siswa->id_siswa,
                'nis'        => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'status'     => $penjemputan ? $penjemputan->status : 'Menunggu',
                'id_wali'    => $penjemputan ? $penjemputan->id_wali : null,
            ];
        });

    $idSiswaHalaman = collect($siswas->items())->pluck('id_siswa');

    $daftarPenjemput = DB::table('siswa_wali')
        ->join('wali', 'siswa_wali.id_wali', '=', 'wali.id_wali')
        ->select(
            'siswa_wali.id_siswa',
            'siswa_wali.id_wali',
            'siswa_wali.hubungan',
            'wali.nama_wali'
        )
        ->whereIn('siswa_wali.id_siswa', $idSiswaHalaman)
        ->orderBy('siswa_wali.hubungan')
        ->get()
        ->groupBy('id_siswa');

    return view(
        'guru.penjemputan',
        compact(
            'kelas',
            'siswas',
            'today',
            'daftarPenjemput'
        )
    );
}

    public function updateStatusPenjemputan(Request $request)
    {
        $request->validate([
            'id_siswa' => ['required'],
            'tanggal'  => ['required', 'date'],
            'status'   => ['required', 'in:Menunggu,Dijemput'],
            'id_wali'  => [
                'nullable',
                'required_if:status,Dijemput'
            ],
        ]);

        $penjemputan = DB::table('penjemputan')
            ->where('id_siswa', $request->id_siswa)
            ->whereDate('tanggal', $request->tanggal)
            ->first();


        // =====================================================
        // STATUS DIJEMPUT
        // =====================================================

        if ($request->status === 'Dijemput') {

            // Cek relasi siswa dan wali
            $relasi = DB::table('siswa_wali')
                ->where('id_siswa', $request->id_siswa)
                ->where('id_wali', $request->id_wali)
                ->first();

            if (!$relasi) {
                return back()->with(
                    'error',
                    'Penjemput tidak terdaftar sebagai wali siswa.'
                );
            }


            // =================================================
            // AMBIL DATA SISWA
            // =================================================

            $siswa = DB::table('siswa')
                ->where('id_siswa', $request->id_siswa)
                ->first();


            // =================================================
            // AMBIL DATA PENJEMPUT
            // =================================================

            $penjemput = DB::table('wali')
                ->join(
                    'siswa_wali',
                    'wali.id_wali',
                    '=',
                    'siswa_wali.id_wali'
                )
                ->where(
                    'wali.id_wali',
                    $request->id_wali
                )
                ->where(
                    'siswa_wali.id_siswa',
                    $request->id_siswa
                )
                ->select(
                    'wali.id_wali',
                    'wali.nama_wali',
                    'siswa_wali.hubungan'
                )
                ->first();


            // =================================================
            // CEK APAKAH SEBELUMNYA SUDAH DIJEMPUT
            // =================================================

            $sebelumnyaSudahDijemput =
                $penjemputan &&
                $penjemputan->status === 'Dijemput';


            // =================================================
            // SIMPAN / UPDATE PENJEMPUTAN
            // =================================================

            if ($penjemputan) {

                DB::table('penjemputan')
                    ->where('id', $penjemputan->id)
                    ->update([
                        'id_wali'    => $request->id_wali,
                        'status'     => 'Dijemput',
                        'jam_jemput' => now()->format('H:i:s'),
                        'metode'     => 'Manual',
                    ]);

            } else {

                DB::table('penjemputan')->insert([
                    'id_siswa'   => $request->id_siswa,
                    'id_wali'    => $request->id_wali,
                    'tanggal'    => $request->tanggal,
                    'jam_jemput' => now()->format('H:i:s'),
                    'status'     => 'Dijemput',
                    'metode'     => 'Manual',
                ]);
            }


            // =================================================
            // KIRIM WA HANYA SAAT BARU DIJEMPUT
            // =================================================

            if (!$sebelumnyaSudahDijemput) {

                $waliTujuan = DB::table('siswa_wali')
                    ->join(
                        'wali',
                        'siswa_wali.id_wali',
                        '=',
                        'wali.id_wali'
                    )
                    ->where(
                        'siswa_wali.id_siswa',
                        $request->id_siswa
                    )
                    ->whereNotNull('wali.no_hp')
                    ->select(
                        'wali.id_wali',
                        'wali.id_user',
                        'wali.nama_wali',
                        'wali.no_hp'
                    )
                    ->get();


                // =============================================
                // KIRIM KE SEMUA WALI SISWA
                // =============================================

                foreach ($waliTujuan as $wali) {

                    $pesan =
                        "Assalamu'alaikum Wr. Wb.\n"
                        . "Yth. Bapak/Ibu {$wali->nama_wali},\n\n"
                        . "Kami informasikan bahwa "
                        . "{$siswa->nama_siswa} "
                        . "telah dijemput oleh "
                        . "{$penjemput->nama_wali} "
                        . "({$penjemput->hubungan}) "
                        . "pada pukul "
                        . now()->format('H:i')
                        . ".\n\n"
                        . "Terima kasih.";


                    // =========================================
                    // KIRIM WHATSAPP
                    // =========================================

                    $hasil = $this->fonnte->kirim(
                        $wali->no_hp,
                        $pesan
                    );


                    // =========================================
                    // SIMPAN NOTIFIKASI JIKA ADA AKUN
                    // =========================================

                    if ($wali->id_user) {

                        DB::table('notifikasi')->insert([
                            'id_user' => $wali->id_user,
                            'id_siswa' => $request->id_siswa,
                            'id_wali' => $wali->id_wali,

                            'judul' =>
                                'Informasi Penjemputan',

                            'pesan' => $pesan,

                            'tipe' =>
                                'penjemputan',

                            'status' =>
                                'terkirim',

                            'is_pushed' => 1,

                            'tipe_notif' =>
                                'wa',

                            'status_wa' =>
                                isset($hasil['status'])
                                && $hasil['status']
                                    ? 'sukses'
                                    : 'gagal',

                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }


        // =====================================================
        // STATUS MENUNGGU
        // =====================================================

        else {

            if ($penjemputan) {

                DB::table('penjemputan')
                    ->where('id', $penjemputan->id)
                    ->delete();
            }
        }


        return back()->with(
            'success',
            'Status penjemputan berhasil diperbarui.'
        );
    }

    public function showDetail($id)
    {
        // Contoh: FP-1 menjadi 1
        $idSiswa = str_replace('FP-', '', $id);

        $penjemputan = Penjemputan::with(['siswa', 'siswa.kelas', 'wali'])
            ->where('id_siswa', $idSiswa)
            ->whereDate('tanggal', now()->toDateString())
            ->latest('jam_jemput')
            ->first();

        if (!$penjemputan) {
            abort(404, 'Data penjemputan tidak ditemukan.');
        }

        $log = [
            'waktu' => $penjemputan->tanggal . ' ' . ($penjemputan->jam_jemput ?? ''),
            'id_scan' => 'FP-' . $penjemputan->id_siswa,
            'nama' => $penjemputan->siswa ? $penjemputan->siswa->nama_siswa : '-',
            'kelas' => $penjemputan->siswa && $penjemputan->siswa->kelas ? $penjemputan->siswa->kelas->nama_kelas : '-',
            'alat' => $penjemputan->metode ?? 'Fingerprint',
            'peran' => 'Siswa',
            'status' => $penjemputan->status ?? '-',
            'nama_wali' => $penjemputan->wali ? $penjemputan->wali->nama_wali : '-',
        ];

        return view('guru.detail-riwayat', compact('log'));
    }
}
