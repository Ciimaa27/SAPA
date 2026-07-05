<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiRfidController extends Controller
{
    public function store(Request $request)
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
            ->select(
                'siswa.*',
                'kelas.nama_kelas'
            )
            ->first();

        if (!$siswa) {
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'RFID tidak terdaftar',
            ], 404);
        }

        $sudahAbsen = DB::table('kehadiran')
            ->where('id_siswa', $siswa->id_siswa)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        if (!$sudahAbsen) {
            DB::table('kehadiran')->insert([
                'id_siswa' => $siswa->id_siswa,
                'id_device' => 1,
                'tanggal' => now()->toDateString(),
                'jam_masuk' => now()->toTimeString(),
                'metode' => 'rfid',
                'status_hadir' => 'hadir',
            ]);
        }

        DB::table('log_tap')->insert([
            'uid_rfid' => $uid,
            'id_device' => 1,
            'keterangan' => 'scan rfid',
            'status' => 'berhasil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'berhasil',
            'sudah_absen' => $sudahAbsen,
            'nama_siswa' => $siswa->nama_siswa,
            'kelas' => $siswa->nama_kelas ?? '-',
        ]);
    }
}