<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Relasi;
use App\Models\Siswa;
use App\Models\Wali;

class RelasiController extends Controller
{
    // ========================
    // TAMPILKAN DATA RELASI
    // ========================
    public function index(Request $request)
    {
        $search = $request->query('search');
        $hubungan = $request->query('hubungan');

        $query = Relasi::with(['siswa', 'wali'])
            ->whereHas('siswa', function ($q) {
                $q->where('is_active', 1);
            })
            ->whereHas('wali', function ($q) {
                $q->where('is_active', 1);
            });

        // ========================
        // PENCARIAN
        // ========================
        if ($search) {
            $query->where(function ($q) use ($search) {
                // CARI NAMA SISWA ATAU NIS
                $q->whereHas('siswa', function ($siswa) use ($search) {
                    $siswa->where('nama_siswa', 'like', '%' . $search . '%')
                        ->orWhere('nis', 'like', '%' . $search . '%');
                })
                // CARI NAMA WALI ATAU NOMOR HP
                ->orWhereHas('wali', function ($wali) use ($search) {
                    $wali->where('nama_wali', 'like', '%' . $search . '%')
                        ->orWhere('no_hp', 'like', '%' . $search . '%');
                })
                // CARI HUBUNGAN
                ->orWhere('hubungan', 'like', '%' . $search . '%');
            });
        }

        // ========================
        // FILTER HUBUNGAN
        // ========================
        if ($hubungan) {
            $query->where('hubungan', $hubungan);
        }

        // ========================
        // PAGINATION
        // ========================
        $relasi = $query->orderByDesc('created_at')
            ->orderBy('id_siswa')
            ->paginate(10)
            ->withQueryString();

        return view('admin.relasi', compact('relasi'));
    }

    // ========================
    // FORM TAMBAH
    // ========================
    public function create()
    {
        $siswa = Siswa::where('is_active', 1)
            ->orderBy('nama_siswa')
            ->get();

        $wali = Wali::where('is_active', 1)
            ->orderBy('nama_wali')
            ->get();

        return view('admin.tambah-relasi', compact('siswa', 'wali'));
    }

    // ========================
    // SIMPAN DATA
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_wali' => 'required|exists:wali,id_wali',
            'hubungan' => 'required|string|max:50',
        ]);

        // CEK APAKAH PASANGAN RELASI SUDAH ADA
        $exists = Relasi::where('id_siswa', $request->id_siswa)
            ->where('id_wali', $request->id_wali)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['relasi' => 'Relasi siswa dan wali tersebut sudah ada.'])
                ->withInput();
        }

        Relasi::create([
            'id_siswa' => $request->id_siswa,
            'id_wali' => $request->id_wali,
            'hubungan' => $request->hubungan,
            'created_at' => now(),
        ]);

        return redirect()->route('relasi.index')
            ->with('success', 'Data relasi berhasil ditambahkan');
    }

    // ========================
    // FORM EDIT
    // ========================
    public function edit($id_siswa, $id_wali)
    {
        $relasi = Relasi::where('id_siswa', $id_siswa)
            ->where('id_wali', $id_wali)
            ->firstOrFail();

        // AMBIL SEMUA SISWA AKTIF
        $siswa = Siswa::where('is_active', 1)
            ->orderBy('nama_siswa')
            ->get();

        // AMBIL SEMUA WALI AKTIF
        $wali = Wali::where('is_active', 1)
            ->orderBy('nama_wali')
            ->get();

        return view('admin.edit-relasi', compact('relasi', 'siswa', 'wali'));
    }

    // ========================
    // UPDATE DATA RELASI
    // ========================
    public function update(Request $request, $id_siswa, $id_wali)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_wali' => 'required|exists:wali,id_wali',
            'hubungan' => 'required|string|max:50',
        ]);

        $newSiswa = $request->input('id_siswa');
        $newWali = $request->input('id_wali');
        $hubungan = $request->input('hubungan');

        // PASTIKAN RELASI LAMA ADA
        $relasiLama = Relasi::where('id_siswa', $id_siswa)
            ->where('id_wali', $id_wali)
            ->firstOrFail();

        // ========================
        // PASANGAN TIDAK BERUBAH
        // ========================
        if ((string) $newSiswa === (string) $id_siswa && (string) $newWali === (string) $id_wali) {
            $relasiLama->update([
                'hubungan' => $hubungan
            ]);
        }
        // ========================
        // PASANGAN BERUBAH
        // ========================
        else {
            $exists = Relasi::where('id_siswa', $newSiswa)
                ->where('id_wali', $newWali)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['relasi' => 'Relasi siswa dan wali tersebut sudah ada.'])
                    ->withInput();
            }

            DB::transaction(function () use ($relasiLama, $newSiswa, $newWali, $hubungan) {
                Relasi::create([
                    'id_siswa' => $newSiswa,
                    'id_wali' => $newWali,
                    'hubungan' => $hubungan,
                    'created_at' => now(),
                ]);

                $relasiLama->delete();
            });
        }

        return redirect()->route('relasi.index')
            ->with('success', 'Data relasi berhasil diperbarui');
    }

    // ========================
    // HAPUS DATA
    // ========================
    public function destroy($id_siswa, $id_wali)
    {
        $relasi = Relasi::where('id_siswa', $id_siswa)
            ->where('id_wali', $id_wali)
            ->firstOrFail();

        $relasi->delete();

        return redirect()->route('relasi.index')
            ->with('success', 'Data relasi berhasil dihapus');
    }
}
