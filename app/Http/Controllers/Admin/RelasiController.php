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
            ->whereHas('siswa', function ($q) {
            $q->where('is_active', 1);
        })
        ->orderBy('id_siswa', 'asc')
        ->paginate(10);

        return view('admin.relasi', compact('relasi'));
    }

    // Form tambah
    public function create()
    {
        $usedSiswaIds = Relasi::pluck('id_siswa')->all();
        $usedWaliIds = Relasi::pluck('id_wali')->all();

        $siswa = Siswa::where('is_active', 1)
            ->whereNotIn('id_siswa', $usedSiswaIds)
            ->orderBy('nama_siswa')
            ->get();

        $wali = Wali::where('is_active', 1)
            ->whereNotIn('id_wali', $usedWaliIds)
            ->orderBy('nama_wali')
            ->get();

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

    // Form edit
    public function edit($id_siswa, $id_wali)
    {
        $relasi = Relasi::where('id_siswa', $id_siswa)
            ->where('id_wali', $id_wali)
            ->firstOrFail();

        $usedSiswaIds = Relasi::where('id_siswa', '!=', $id_siswa)
            ->pluck('id_siswa')
            ->all();
        $usedWaliIds = Relasi::where('id_wali', '!=', $id_wali)
            ->pluck('id_wali')
            ->all();

        $siswa = Siswa::where('is_active', 1)
            ->where(function ($query) use ($id_siswa, $usedSiswaIds) {
                $query->whereNotIn('id_siswa', $usedSiswaIds)
                    ->orWhere('id_siswa', $id_siswa);
            })
            ->orderBy('nama_siswa')
            ->get();

        $wali = Wali::where('is_active', 1)
            ->where(function ($query) use ($id_wali, $usedWaliIds) {
                $query->whereNotIn('id_wali', $usedWaliIds)
                    ->orWhere('id_wali', $id_wali);
            })
            ->orderBy('nama_wali')
            ->get();

        return view('admin.edit-relasi', compact('relasi', 'siswa', 'wali'));
    }

    // Update data relasi
    public function update(Request $request, $id_siswa, $id_wali)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_wali' => 'required|exists:wali,id_wali',
            'hubungan' => 'required',
        ]);

        $newSiswa = $request->input('id_siswa');
        $newWali = $request->input('id_wali');
        $hubungan = $request->input('hubungan');

        if ($newSiswa == $id_siswa && $newWali == $id_wali) {
            Relasi::where('id_siswa', $id_siswa)
                ->where('id_wali', $id_wali)
                ->update(['hubungan' => $hubungan]);
        } else {
            $exists = Relasi::where('id_siswa', $newSiswa)
                ->where('id_wali', $newWali)
                ->exists();

            if ($exists) {
                return back()->withErrors(['relasi' => 'Relasi siswa dan wali sudah ada.'])->withInput();
            }

            Relasi::create([
                'id_siswa' => $newSiswa,
                'id_wali' => $newWali,
                'hubungan' => $hubungan,
            ]);

            Relasi::where('id_siswa', $id_siswa)
                ->where('id_wali', $id_wali)
                ->delete();
        }

        return redirect()->route('relasi.index')->with('success', 'Data relasi berhasil diupdate');
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
