<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IoTController extends Controller
{
    // ================= RFID / SIDIK JARI =================
    public function index($tab = 'rfid')
    {
        return view('admin.rfid');
    }

    // ================= STATUS PERANGKAT =================
    public function statusPerangkat()
    {
        $perangkat = DB::table('iot_device')->get();

        $logs = DB::table('log_tap')
            ->leftJoin('siswa', 'log_tap.uid_rfid', '=', 'siswa.rfid_uid')
            ->leftJoin('wali', 'log_tap.fingerprint_id', '=', 'wali.fingerprint_id')
            ->select(
                'log_tap.*',
                'siswa.nama_siswa',
                'wali.nama_wali',
                DB::raw("
                    CASE 
                        WHEN log_tap.uid_rfid IS NOT NULL THEN 'Siswa'
                        ELSE 'Orangtua/wali'
                    END as peran
                ")
            )
            ->orderBy('log_tap.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.status-perangkat', compact('perangkat', 'logs'));
    }
}