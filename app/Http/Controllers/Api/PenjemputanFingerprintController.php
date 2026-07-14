<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\PengaturanSistem;

class PenjemputanFingerprintController extends Controller
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
            'id_jari' => 'required|integer',
        ]);

        $uid = strtoupper(trim($request->uid));
        $fingerprintId = $request->id_jari;

        $pengaturan = PengaturanSistem::first();

        if ($pengaturan && $pengaturan->status_sistem == 'nonaktif') {
            return response()->json([
                'status' => 'disabled',
                'pesan'  => 'Sistem sedang dinonaktifkan'
            ], 200);
        }

        // =========================
        // CARI SISWA
        // =========================

        $siswa = DB::table('siswa')
            ->leftJoin(
                'kelas',
                'kelas.id_kelas',
                '=',
                'siswa.id_kelas'
            )
            ->where('siswa.rfid_uid', $uid)
            ->select(
                'siswa.*',
                'kelas.nama_kelas'
            )
            ->first();


        // =========================
        // RFID TIDAK DITEMUKAN
        // =========================

        if (!$siswa) {

            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' => $fingerprintId,
                'keterangan' => 'scan fingerprint',
                'status' => 'gagal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'gagal',
                'pesan' => 'RFID siswa tidak terdaftar',
            ], 404);
        }


        // =========================
        // CARI PENJEMPUT
        // =========================

        $penjemput = DB::table('wali')
            ->join(
                'siswa_wali',
                'wali.id_wali',
                '=',
                'siswa_wali.id_wali'
            )
            ->where(
                'siswa_wali.id_siswa',
                $siswa->id_siswa
            )
            ->where(
                'wali.fingerprint_id',
                $fingerprintId
            )
            ->select(
                'wali.id_wali',
                'wali.nama_wali',
                'wali.fingerprint_id',
                'siswa_wali.id_siswa',
                'siswa_wali.hubungan'
            )
            ->first();


        // =========================
        // FINGERPRINT TIDAK COCOK
        // =========================

        if (!$penjemput) {

            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' => $fingerprintId,
                'keterangan' => 'scan fingerprint',
                'status' => 'gagal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'gagal',
                'pesan' => 'Fingerprint wali tidak cocok',
            ], 404);
        }


        try {

            // =========================
            // CEK SUDAH DIJEMPUT
            // =========================

            $sudahJemput = DB::table('penjemputan')
                ->where(
                    'id_siswa',
                    $siswa->id_siswa
                )
                ->whereDate(
                    'tanggal',
                    now()->toDateString()
                )
                ->where(
                    'status',
                    'Dijemput'
                )
                ->exists();


            // =========================
            // BELUM DIJEMPUT
            // =========================

            if (!$sudahJemput) {

                // =========================
                // CEK DATA HARI INI
                // =========================

                $dataHariIni = DB::table('penjemputan')
                    ->where(
                        'id_siswa',
                        $siswa->id_siswa
                    )
                    ->whereDate(
                        'tanggal',
                        now()->toDateString()
                    )
                    ->first();


                // =========================
                // UPDATE JIKA SUDAH ADA
                // =========================

                if ($dataHariIni) {

                    DB::table('penjemputan')
                        ->where(
                            'id',
                            $dataHariIni->id
                        )
                        ->update([
                            'id_wali' =>
                                $penjemput->id_wali,

                            'jam_jemput' =>
                                now()->toTimeString(),

                            'status' =>
                                'Dijemput',

                            'metode' =>
                                'Fingerprint',
                        ]);

                } else {

                    // =========================
                    // INSERT DATA BARU
                    // =========================

                    DB::table('penjemputan')
                        ->insert([
                            'id_siswa' =>
                                $siswa->id_siswa,

                            'id_wali' =>
                                $penjemput->id_wali,

                            'tanggal' =>
                                now()->toDateString(),

                            'jam_jemput' =>
                                now()->toTimeString(),

                            'status' =>
                                'Dijemput',

                            'metode' =>
                                'Fingerprint',
                        ]);
                }


                // =========================
                // AMBIL SEMUA WALI SISWA
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
                    ->whereNotNull(
                        'wali.no_hp'
                    )
                    ->select(
                        'wali.id_wali',
                        'wali.id_user',
                        'wali.nama_wali',
                        'wali.no_hp'
                    )
                    ->get();


                // =========================
                // KIRIM WA
                // =========================

                foreach ($waliTujuan as $wali) {

                    $pesan =
                        "Assalamu'alaikum Wr. Wb.\n"
                        . "Yth. Bapak/Ibu {$wali->nama_wali},\n\n"
                        . "Kami informasikan bahwa {$siswa->nama_siswa} "
                        . "telah dijemput oleh {$penjemput->nama_wali} "
                        . "({$penjemput->hubungan}) "
                        . "pada pukul "
                        . now()->format('H:i')
                        . ".\n\n"
                        . "Terima kasih.";


                    // =========================
                    // KIRIM KE FONNTE
                    // =========================

                    $hasil = $this->fonnte->kirim(
                        $wali->no_hp,
                        $pesan
                    );


                    // =========================
                    // SIMPAN NOTIFIKASI
                    // =========================

                    if ($wali->id_user) {

                        DB::table('notifikasi')
                            ->insert([
                                'id_user' =>
                                    $wali->id_user,

                                'id_siswa' =>
                                    $siswa->id_siswa,

                                'id_wali' =>
                                    $wali->id_wali,

                                'judul' =>
                                    'Informasi Penjemputan',

                                'pesan' =>
                                    $pesan,

                                'tipe' =>
                                    'penjemputan',

                                'status' =>
                                    'terkirim',

                                'is_pushed' =>
                                    1,

                                'tipe_notif' =>
                                    'wa',

                                'status_wa' =>
                                    isset($hasil['status'])
                                    && $hasil['status']
                                        ? 'sukses'
                                        : 'gagal',

                                'created_at' =>
                                    now(),

                                'updated_at' =>
                                    now(),
                            ]);
                    }
                }
            }


            // =========================
            // SIMPAN LOG TAP
            // =========================

            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' =>
                    $fingerprintId,

                'keterangan' =>
                    'scan fingerprint',

                'status' =>
                    'berhasil',

                'created_at' =>
                    now(),

                'updated_at' =>
                    now(),
            ]);


            // =========================
            // RESPONSE ESP32
            // =========================

            return response()->json([
                'status' =>
                    'berhasil',

                'sudah_dijemput' =>
                    $sudahJemput,

                'nama_siswa' =>
                    $siswa->nama_siswa,

                'kelas' =>
                    $siswa->nama_kelas ?? '-',

                'nama_penjemput' =>
                    $penjemput->nama_wali,

                'hubungan' =>
                    $penjemput->hubungan,

                'tanggal' =>
                    now()->format('d-m-Y'),

                'jam' =>
                    now()->format('H:i:s'),
            ]);


        } catch (Throwable $e) {

            return response()->json([
                'status' =>
                    'gagal',

                'pesan' =>
                    'Gagal menyimpan penjemputan',

                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }
}