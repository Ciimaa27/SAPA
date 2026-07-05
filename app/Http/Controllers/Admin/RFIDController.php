<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RFIDController extends Controller
{
    // ================= HALAMAN RFID / SIDIK JARI =================
    public function index($tab = 'rfid')
    {
        if ($tab === 'rfid') {

            $data = DB::table('siswa')
                ->select(
                    'id_siswa',
                    'nama_siswa',
                    'rfid_uid'
                )
                ->where('is_active', 1)
                ->paginate(10);

            return view('admin.rfid', compact(
                'data',
                'tab'
            ));

        } elseif ($tab === 'sidik-jari') {

            $data = DB::table('wali')
                ->select(
                    'id_wali',
                    'nama_wali',
                    'fingerprint_id'
                )
                ->where('is_active', 1)
                ->paginate(10);

            return view('admin.sidik-jari', compact(
                'data',
                'tab'
            ));

        } else {

            abort(404);
        }
    }

    public function create()
    {
        return view('admin.tambah-data-rfid');
    }

    // ================= HAPUS =================
    public function destroy($tab, $id)
    {
        if ($tab === 'rfid') {

            DB::table('siswa')
                ->where('id_siswa', $id)
                ->delete();

        } elseif ($tab === 'sidik-jari') {

            DB::table('wali')
                ->where('id_wali', $id)
                ->delete();

        }

        return back()->with('success','Data berhasil dihapus');
    }

    public function latestRFID()
    {
        $data = DB::table('log_tap')
            ->whereNotNull('uid_rfid')
            ->latest('created_at')
            ->first();

        return response()->json($data);
    }

    public function latestFingerprint()
    {
        $log = DB::table('log_tap')
            ->whereNotNull('fingerprint_id')
            ->latest('created_at')
            ->first();

        if (!$log) {
            return response()->json([
                'fingerprint_id' => null,
                'wali' => null
            ]);
        }

        $wali = DB::table('wali')
            ->where('fingerprint_id', $log->fingerprint_id)
            ->where('is_active', 1)
            ->select(
                'id_wali',
                'nama_wali',
                'fingerprint_id'
            )
            ->first();

        return response()->json([
            'fingerprint_id' => $log->fingerprint_id,
            'wali' => $wali
        ]);
    }
}