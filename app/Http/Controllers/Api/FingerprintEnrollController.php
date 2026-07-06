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

            // Cek apakah ID fingerprint sudah digunakan wali
            $wali = DB::table('wali')
                ->where('fingerprint_id', $fingerprintId)
                ->first();


            // Simpan hasil enroll ke log_tap
            DB::table('log_tap')->insert([
                'id_device' => 2,
                'uid_rfid' => $uid,
                'fingerprint_id' => $fingerprintId,
                'keterangan' => 'enroll fingerprint',

                // status proses enroll berhasil
                'status' => 'berhasil',

                'created_at' => now(),
                'updated_at' => now(),
            ]);


            return response()->json([
                'status' => 'berhasil',
                'pesan' => 'Hasil enroll fingerprint diterima',
                'fingerprint_id' => $fingerprintId,
                'uid' => $uid,

                // Status pendaftaran dicek dari tabel wali
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