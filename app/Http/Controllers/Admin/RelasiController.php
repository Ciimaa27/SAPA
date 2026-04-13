<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relasi;
use App\Models\Siswa;
use App\Models\Wali;

class RelasiController extends Controller
{
    // Tampilkan data relasi
    public function index()
    {
        $relasi = Relasi::with(['siswa', 'wali'])
            ->orderBy('id_siswa', 'asc')
            ->paginate(10);

        return view('admin.relasi', compact('relasi'));
    }

    // Form tambah
    public function create()
    {
        $siswa = Siswa::all();
        $wali = Wali::all();
        return view('admin.tambah-relasi', compact('siswa','wali'));
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'id_wali' => 'required',
            'hubungan' => 'required',
        ]);

        Relasi::create($request->all());

        return redirect()->route('relasi.index')->with('success', 'Data berhasil ditambah');
    }

    // Hapus data
    public function destroy($id_siswa, $id_wali)
    {
        Relasi::where('id_siswa', $id_siswa)
              ->where('id_wali', $id_wali)
              ->delete();

        return redirect()->route('relasi.index')->with('success', 'Data berhasil dihapus');
    }
}