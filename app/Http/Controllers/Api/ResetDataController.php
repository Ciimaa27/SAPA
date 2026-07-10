<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class ResetDataController extends Controller
{
    // ==========================================================
    // RESET DATA SIDIK JARI
    // ==========================================================
    public function resetFingerprint()
    {
        try {
            DB::table('wali')->update([
                'fingerprint_id' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Semua fingerprint wali berhasil direset'
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
    // HAPUS SEMUA DATA SISWA
    // ==========================================================
    public function resetSiswa()
    {
        try {

            DB::transaction(function () {

                // Hapus tabel yang berhubungan dengan siswa terlebih dahulu
                DB::table('notifikasi')->delete();
                DB::table('penjemputan')->delete();
                DB::table('kehadiran')->delete();
                DB::table('log_tap')->delete();
                DB::table('siswa_wali')->delete();

                // Terakhir hapus siswa
                DB::table('siswa')->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Semua data siswa berhasil dihapus'
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data siswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}