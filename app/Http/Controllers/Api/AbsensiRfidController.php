<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\PengaturanSistem;

class AbsensiRfidController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function store(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
        ]);

        $uid = strtoupper(trim($request->uid));

        $uid = strtoupper(trim($request->uid));

        // =========================
        // CEK STATUS SISTEM
        // =========================

        $pengaturan = PengaturanSistem::first();

        if ($pengaturan && $pengaturan->status_sistem == 'nonaktif') {
            return response()->json([
                'status' => 'disabled',
                'pesan' => 'Sistem sedang dinonaktifkan'
            ], 200);
        }

        // =========================
        // CARI SISWA
        // =========================

        $siswa = DB::table('siswa')
            ->leftJoin(
                'kelas',
                'siswa.id_kelas',
                '=',
                'kelas.id_kelas'
            )
            ->where('siswa.rfid_uid', $uid)
            ->select(
                'siswa.*',
                'kelas.nama_kelas'
            )
            ->first();

        // =========================
        // RFID TIDAK TERDAFTAR
        // =========================

        if (!$siswa) {

            DB::table('log_tap')->insert([
                'uid_rfid' => $uid,
                'id_device' => 1,
                'keterangan' => 'scan rfid',
                'status' => 'gagal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'gagal',
                'pesan' => 'RFID tidak terdaftar',
            ], 404);
        }

        try {

            // =========================
            // CEK SUDAH ABSEN
            // =========================

            $sudahAbsen = DB::table('kehadiran')
                ->where('id_siswa', $siswa->id_siswa)
                ->whereDate(
                    'tanggal',
                    now()->toDateString()
                )
                ->exists();

            // =========================
            // ABSEN PERTAMA
            // =========================

            if (!$sudahAbsen) {

                DB::table('kehadiran')->insert([
                    'id_siswa' => $siswa->id_siswa,
                    'id_device' => 1,
                    'tanggal' => now()->toDateString(),
                    'jam_masuk' => now()->toTimeString(),
                    'metode' => 'rfid',
                    'status_hadir' => 'hadir',
                ]);

                // =========================
                // AMBIL WALI SISWA
                // =========================

                $waliTujuan = DB::table('siswa_wali')
                    ->join(
                        'wali',
                        'siswa_wali.id_wali',
                        '=',
                        'wali.id_wali'
                    )
                    ->where(
                        'siswa_wali.id_siswa',
                        $siswa->id_siswa
                    )
                    ->whereNotNull('wali.no_hp')
                    ->whereNotNull('wali.id_user')
                    ->select(
                        'wali.id_wali',
                        'wali.id_user',
                        'wali.nama_wali',
                        'wali.no_hp'
                    )
                    ->get();

                // =========================
                // KIRIM WA KE WALI
                // =========================

                foreach ($waliTujuan as $wali) {

                    $pesan =
                        "Assalamu'alaikum Wr. Wb.\n"
                        . "Yth. Bapak/Ibu {$wali->nama_wali},\n\n"
                        . "Kami informasikan bahwa {$siswa->nama_siswa} "
                        . "telah melakukan absensi masuk sekolah "
                        . "pada pukul "
                        . now()->format('H:i')
                        . ".\n\n"
                        . "Terima kasih.";

                    $hasil = $this->fonnte->kirim(
                        $wali->no_hp,
                        $pesan
                    );

                    // =========================
                    // SIMPAN NOTIFIKASI
                    // =========================

                    DB::table('notifikasi')->insert([
                        'id_user' => $wali->id_user,
                        'id_siswa' => $siswa->id_siswa,
                        'id_wali' => $wali->id_wali,
                        'judul' => 'Kehadiran Siswa',
                        'pesan' => $pesan,
                        'tipe' => 'kehadiran',
                        'status' => 'terkirim',
                        'is_pushed' => 1,
                        'tipe_notif' => 'wa',

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

            // =========================
            // SIMPAN LOG TAP
            // =========================

            DB::table('log_tap')->insert([
                'uid_rfid' => $uid,
                'id_device' => 1,
                'keterangan' => 'scan rfid',
                'status' => 'berhasil',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =========================
            // RESPONSE KE ESP32
            // =========================

            return response()->json([
                'status' => 'berhasil',
                'sudah_absen' => $sudahAbsen,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->nama_kelas ?? '-',
            ]);

        } catch (Throwable $e) {

            return response()->json([
                'status' => 'gagal',
                'pesan' => 'Gagal menyimpan kehadiran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}