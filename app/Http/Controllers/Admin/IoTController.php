<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IoTController extends Controller
{
    // =====================================================
    // RFID / SIDIK JARI
    // =====================================================
    public function index($tab = 'rfid')
    {
        if ($tab === 'sidik-jari') {

            $data = DB::table('wali')
                ->join(
                    'siswa_wali',
                    'wali.id_wali',
                    '=',
                    'siswa_wali.id_wali'
                )
                ->join(
                    'siswa',
                    'siswa_wali.id_siswa',
                    '=',
                    'siswa.id_siswa'
                )
                ->select(
                    'wali.id_wali',
                    'wali.nama_wali',
                    'wali.fingerprint_id',
                    'siswa.id_siswa',
                    'siswa.nama_siswa',
                    'siswa_wali.hubungan'
                )
                ->orderBy('wali.nama_wali')
                ->paginate(10)
                ->withQueryString();

            return view(
                'admin.sidik-jari',
                compact('data')
            );
        }


        // ================= RFID =================

        $data = DB::table('siswa')
            ->leftJoin(
                'kelas',
                'siswa.id_kelas',
                '=',
                'kelas.id_kelas'
            )
            ->select(
                'siswa.*',
                'kelas.nama_kelas'
            )
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.rfid',
            compact('data')
        );
    }


    // =====================================================
    // FINGERPRINT TERAKHIR DARI ALAT
    // =====================================================
    public function latestFingerprint()
    {
        $log = DB::table('log_tap')
            ->where(
                'keterangan',
                'enroll fingerprint'
            )
            ->orderByDesc('created_at')
            ->first();


        if (!$log) {

            return response()->json([
                'fingerprint_id' => null,
                'terdaftar' => false,
            ]);
        }


        // Cari siswa berdasarkan UID RFID
        $siswa = DB::table('siswa')
            ->where(
                'rfid_uid',
                $log->uid_rfid
            )
            ->first();


        // Cek apakah fingerprint sudah didaftarkan
        // oleh admin ke salah satu wali
        $wali = DB::table('wali')
            ->where(
                'fingerprint_id',
                $log->fingerprint_id
            )
            ->first();


        return response()->json([
            'fingerprint_id' =>
                $log->fingerprint_id,

            'id_siswa' =>
                $siswa->id_siswa ?? null,

            'nama_siswa' =>
                $siswa->nama_siswa ?? null,

            'terdaftar' =>
                $wali ? true : false,

            'nama_wali' =>
                $wali->nama_wali ?? null,
        ]);
    }


    // =====================================================
    // ADMIN MENDAFTARKAN FINGERPRINT KE WALI
    // =====================================================
    public function registerFingerprint(Request $request)
    {
        $request->validate([
            'id_wali' =>
                'required|integer|exists:wali,id_wali',

            'fingerprint_id' =>
                'required|integer',
        ]);


        $idWali = $request->id_wali;

        $fingerprintId =
            $request->fingerprint_id;


        // ==========================================
        // CEK FINGERPRINT SUDAH DIPAKAI
        // ==========================================

        $sudahDipakai = DB::table('wali')
            ->where(
                'fingerprint_id',
                $fingerprintId
            )
            ->where(
                'id_wali',
                '!=',
                $idWali
            )
            ->exists();


        if ($sudahDipakai) {

            return back()->with(
                'error',
                'Sidik jari sudah terdaftar pada wali lain.'
            );
        }


        // ==========================================
        // UPDATE WALI YANG DIPILIH ADMIN
        // ==========================================

        DB::table('wali')
            ->where(
                'id_wali',
                $idWali
            )
            ->update([
                'fingerprint_id' =>
                    $fingerprintId,

                'updated_at' =>
                    now(),
            ]);


        return redirect()
            ->route('iot.index', [
                'tab' => 'sidik-jari'
            ])
            ->with(
                'success',
                'Sidik jari berhasil didaftarkan.'
            );
    }


    // =====================================================
    // LEPAS FINGERPRINT DARI WALI
    // =====================================================
    public function destroy(
        $tab,
        $id
    ) {

        if ($tab === 'sidik-jari') {

            DB::table('wali')
                ->where(
                    'id_wali',
                    $id
                )
                ->update([
                    'fingerprint_id' => null,
                    'updated_at' => now(),
                ]);


            return redirect()
                ->route('iot.index', [
                    'tab' => 'sidik-jari'
                ])
                ->with(
                    'success',
                    'Sidik jari berhasil dilepas.'
                );
        }


        return back();
    }


    // =====================================================
    // STATUS PERANGKAT
    // =====================================================
    public function statusPerangkat()
    {
        $perangkat =
            DB::table('iot_device')
                ->get();


        $logs = DB::table('log_tap')

            ->leftJoin(
                'siswa',
                'log_tap.uid_rfid',
                '=',
                'siswa.rfid_uid'
            )

            ->leftJoin(
                'wali',
                'log_tap.fingerprint_id',
                '=',
                'wali.fingerprint_id'
            )

            ->select(
                'log_tap.*',
                'siswa.nama_siswa',
                'wali.nama_wali',

                DB::raw("
                    CASE
                        WHEN log_tap.id_device = 1
                            THEN 'Siswa'

                        WHEN log_tap.id_device = 2
                            THEN 'Orangtua/wali'

                        ELSE '-'
                    END as peran
                ")
            )

            ->orderBy(
                'log_tap.created_at',
                'desc'
            )

            ->paginate(10)

            ->withQueryString();


        return view(
            'admin.status-perangkat',
            compact(
                'perangkat',
                'logs'
            )
        );
    }
}