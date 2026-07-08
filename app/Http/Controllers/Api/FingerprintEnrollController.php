<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class FingerprintEnrollController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fingerprint_id' => 'required|integer',
            'uid' => 'required|string',
        ]);

        $fingerprintId = $request->fingerprint_id;
        $uid = strtoupper(trim($request->uid));

        try {

            // ==========================================
            // 1. CARI SISWA BERDASARKAN RFID
            // ==========================================
            $siswa = DB::table('siswa')
                ->where('rfid_uid', $uid)
                ->where('is_active', 1)
                ->first();

            if (!$siswa) {
                return response()->json([
                    'status' => 'gagal',
                    'pesan' => 'RFID siswa tidak ditemukan',
                ], 404);
            }


            // ==========================================
            // 2. CEK APAKAH FINGERPRINT SUDAH TERDAFTAR
            // ==========================================
            $wali = DB::table('wali')
                ->where('fingerprint_id', $fingerprintId)
                ->first();


            // ==========================================
            // 3. SIMPAN HASIL ENROLL KE LOG
            // TIDAK UPDATE WALI DI SINI
            // ==========================================
            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' => $fingerprintId,
                'keterangan' => 'enroll fingerprint',
                'status' => 'berhasil',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // ==========================================
            // 4. RESPONSE
            // ==========================================
            return response()->json([
                'status' => 'berhasil',
                'pesan' => 'Hasil enroll fingerprint diterima',

                'fingerprint_id' => $fingerprintId,

                'uid' => $uid,

                'id_siswa' => $siswa->id_siswa,

                'nama_siswa' => $siswa->nama_siswa,

                'terdaftar' => $wali ? true : false,

                'nama_wali' => $wali->nama_wali ?? null,

            ], 200);


        } catch (Throwable $e) {

            return response()->json([
                'status' => 'gagal',
                'pesan' => 'Gagal menyimpan hasil enroll fingerprint',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}