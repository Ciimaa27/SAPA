<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Wali;

class IoTController extends Controller
{
    // Halaman RFID / Sidik-jari
    public function index($tab = 'rfid')
    {
        if ($tab === 'rfid') {
            $data = Siswa::select('id_siswa','nama_siswa','rfid_uid')->get();
            return view('admin.rfid', compact('data', 'tab'));
        } elseif ($tab === 'sidik-jari') {
            $data = Wali::select('id_wali','nama_wali','fingerprint_id')->get();
            return view('admin.sidik-jari', compact('data', 'tab'));
        } else {
            abort(404);
        }
    }

    // Tambah UID
    public function store(Request $request, $tab)
    {
        if ($tab === 'rfid') {
            $request->validate([
                'id_siswa' => 'required|exists:siswa,id_siswa',
                'rfid_uid' => 'required|unique:siswa,rfid_uid',
            ]);

            $siswa = Siswa::findOrFail($request->id_siswa);
            $siswa->rfid_uid = $request->rfid_uid;
            $siswa->save();

            return redirect()->route('iot.index', ['tab'=>'rfid'])
                             ->with('success', 'RFID siswa berhasil ditambahkan.');
        }

        if ($tab === 'sidik-jari') {
            $request->validate([
                'id_wali' => 'required|exists:wali,id_wali',
                'fingerprint_id' => 'required|unique:wali,fingerprint_id',
            ]);

            $wali = Wali::findOrFail($request->id_wali);
            $wali->fingerprint_id = $request->fingerprint_id;
            $wali->save();

            return redirect()->route('iot.index', ['tab'=>'sidik-jari'])
                             ->with('success', 'Fingerprint wali berhasil ditambahkan.');
        }
    }

    // Hapus UID
    public function destroy($tab, $id)
    {
        if ($tab === 'rfid') {
            $siswa = Siswa::findOrFail($id);
            $siswa->rfid_uid = null;
            $siswa->save();

            return redirect()->route('iot.index', ['tab'=>'rfid'])
                             ->with('success', 'RFID siswa berhasil dihapus.');
        }

        if ($tab === 'sidik-jari') {
            $wali = Wali::findOrFail($id);
            $wali->fingerprint_id = null;
            $wali->save();

            return redirect()->route('iot.index', ['tab'=>'sidik-jari'])
                             ->with('success', 'Fingerprint wali berhasil dihapus.');
        }
    }

    // Status perangkat
    public function statusPerangkat()
    {
        $perangkat = \DB::table('iot_device')->get();
        $log = \DB::table('log_tap')->latest()->take(50)->get();

        return view('admin.status-perangkat', compact('perangkat', 'log'));
    }
}