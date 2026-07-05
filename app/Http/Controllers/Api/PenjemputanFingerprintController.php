<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PenjemputanFingerprintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
            'id_jari' => 'required|integer',
        ]);

        $uid = strtoupper(trim($request->uid));
        $fingerprintId = $request->id_jari;

        // Cari siswa berdasarkan RFID
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

        // RFID siswa tidak ditemukan
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

        // Cari wali berdasarkan fingerprint dan relasi siswa
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

        // Fingerprint tidak cocok
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

            $sudahJemput = DB::table('penjemputan')
                ->where('id_siswa', $siswa->id_siswa)
                ->whereDate('tanggal', now()->toDateString())
                ->exists();

            if (!$sudahJemput) {

                DB::table('penjemputan')->insert([
                    'id_siswa' => $siswa->id_siswa,
                    'id_wali' => $penjemput->id_wali,
                    'tanggal' => now()->toDateString(),
                    'jam_jemput' => now()->toTimeString(),
                ]);
            }

            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' => $fingerprintId,
                'keterangan' => 'scan fingerprint',
                'status' => 'berhasil',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Response disesuaikan dengan pembacaan ESP32
            return response()->json([
                'status' => 'berhasil',
                'sudah_dijemput' => $sudahJemput,

                'nama_siswa' => $siswa->nama_siswa,
                'kelas' => $siswa->nama_kelas ?? '-',

                'nama_penjemput' => $penjemput->nama_wali,
                'hubungan' => $penjemput->hubungan,

                'tanggal' => now()->format('d-m-Y'),
                'jam' => now()->format('H:i:s'),
            ]);

        } catch (Throwable $e) {

            return response()->json([
                'status' => 'gagal',
                'pesan' => 'Gagal menyimpan penjemputan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}