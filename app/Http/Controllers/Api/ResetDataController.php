<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class ResetDataController extends Controller
{
    // ==========================================================
    // RESET SEMUA ID SIDIK JARI WALI
    // ==========================================================
    public function resetFingerprint()
    {
        try {
            $jumlah = 0;

            DB::transaction(function () use (&$jumlah) {

                // 1. Kosongkan fingerprint_id semua wali
                $jumlah = DB::table('wali')
                    ->whereNotNull('fingerprint_id')
                    ->update([
                        'fingerprint_id' => null
                    ]);

                // 2. Hapus log fingerprint lama
                // Agar "Sidik Jari Terakhir Terdeteksi"
                // tidak menampilkan ID lama seperti ID 4
                DB::table('log_tap')
                    ->whereNotNull('fingerprint_id')
                    ->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Semua fingerprint wali dan log fingerprint berhasil direset',
                'jumlah_data_diubah' => $jumlah
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal reset fingerprint',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // ==========================================================
    // RESET SEMUA UID RFID SISWA
    // ==========================================================
    public function resetSiswa()
    {
        try {
            $jumlah = DB::table('siswa')
                ->whereNotNull('rfid_uid')
                ->update([
                    'rfid_uid' => null
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Semua UID RFID siswa berhasil direset',
                'jumlah_data_diubah' => $jumlah
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset UID RFID siswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}