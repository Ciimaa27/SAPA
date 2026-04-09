<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class DataSiswaController extends Controller
{
    // ========================
    // TAMPIL DATA
    // ========================
    public function index()
    {
        $siswa = Siswa::all();
        $total = $siswa->count();

        return view('admin.data-siswa', compact('siswa', 'total'));
    }

    // ========================
    // FORM TAMBAH
    // ========================
    public function create()
    {
        return view('admin.tambah-siswa');
    }

    // ========================
    // SIMPAN DATA
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama_siswa' => 'required',
            'id_kelas' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        Siswa::create([
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'id_kelas' => $request->id_kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,

            // default value
            'rfid_uid' => 'RFID'.rand(100,999),
            'status' => 'aktif',
            'is_active' => 1
        ]);

        // 🔥 INI WAJIB (biar balik ke halaman)
        return redirect()->route('data-siswa')
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    // ========================
    // HAPUS
    // ========================
    public function destroy($id)
    {
        $data = Siswa::findOrFail($id);
        $data->delete();

        return redirect()->back()
            ->with('success', 'Data siswa berhasil dihapus');
    }

    // ========================
    // DETAIL / LIHAT DATA
    // ========================
    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('admin.detail-siswa', compact('siswa'));
    }
}