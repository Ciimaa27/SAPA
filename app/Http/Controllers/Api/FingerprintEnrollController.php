<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class FingerprintEnrollController extends Controller
{
    // =====================================================
    // CEK SISWA DARI RFID SEBELUM ENROLL
    // =====================================================
    public function cekSiswa(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
        ]);

        $uid = strtoupper(trim($request->uid));

        $siswa = DB::table('siswa')
            ->leftJoin(
                'kelas',
                'siswa.id_kelas',
                '=',
                'kelas.id_kelas'
            )
            ->where('siswa.rfid_uid', $uid)
            ->where('siswa.is_active', 1)
            ->select(
                'siswa.id_siswa',
                'siswa.nama_siswa',
                'kelas.nama_kelas'
            )
            ->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'RFID siswa tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'berhasil',
            'id_siswa' => $siswa->id_siswa,
            'nama_siswa' => $siswa->nama_siswa,
            'kelas' => $siswa->nama_kelas ?? '-',
        ], 200);
    }


    // =====================================================
    // SIMPAN HASIL ENROLL FINGERPRINT
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'fingerprint_id' => 'required|integer',
            'uid' => 'required|string',
        ]);

        $fingerprintId = $request->fingerprint_id;
        $uid = strtoupper(trim($request->uid));

        try {

            // Cek apakah fingerprint sudah dipakai wali
            $wali = DB::table('wali')
                ->where('fingerprint_id', $fingerprintId)
                ->first();


            // Simpan hasil enroll ke log_tap
            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' => $fingerprintId,
                'keterangan' => 'enroll fingerprint',
                'status' => 'berhasil',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            return response()->json([
                'status' => 'berhasil',
                'pesan' => 'Hasil enroll fingerprint diterima',

                'fingerprint_id' => $fingerprintId,
                'uid' => $uid,

                'terdaftar' => $wali ? true : false,

                'nama_wali' =>
                    $wali->nama_wali ?? null,

            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'status' => 'gagal',
                'pesan' =>
                    'Gagal menyimpan hasil enroll fingerprint',

                'error' => $e->getMessage(),

            ], 500);
        }
    }
}