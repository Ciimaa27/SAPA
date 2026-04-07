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
                ->select('id_siswa','nama_siswa','rfid_uid')
                ->get();

        } elseif ($tab === 'sidik-jari') {

            $data = DB::table('wali')
                ->select('id_wali','nama_wali','fingerprint_id')
                ->get();

        } else {
            abort(404);
        }

        return view('admin.rfid', compact('data','tab'));
    }

    // ================= HAPUS =================
    public function destroy($tab, $id)
    {
        if ($tab === 'rfid') {

            DB::table('siswa')
                ->where('id_siswa', $id)
                ->update(['rfid_uid' => null]);

        } elseif ($tab === 'sidik-jari') {

            DB::table('wali')
                ->where('id_wali', $id)
                ->update(['fingerprint_id' => null]);

        }

        return back()->with('success','Data berhasil dihapus');
    }
}