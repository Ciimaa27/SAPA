<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;
use App\Models\ArsipSiswa;
use App\Models\Wali;
use App\Models\SiswaWali;
use App\Models\User;
class DataSiswaController extends Controller
{
    // ========================
    // TAMPIL DATA
    // ========================
    public function index(Request $request)
    {
        $query = Siswa::with('kelas')
            ->where('is_active', 1);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search . '%')
                ->orWhere('nama_siswa', 'like', '%' . $request->search . '%');
            });
        }

        $siswa = $query->orderByDesc('id_siswa')
                    ->paginate(10)
                    ->withQueryString();

        $total = Siswa::where('is_active', 1)->count();

        return view('admin.data-siswa', compact('siswa', 'total'));
    }

    // ========================
    // FORM TAMBAH
    // ========================
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.tambah-siswa', compact('kelas'));
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

                // UID RFID wajib dan tidak boleh sama
                'rfid_uid' => 'required|string|unique:siswa,rfid_uid',
            ]);

            Siswa::create([
                'nis' => $request->nis,
                'nama_siswa' => $request->nama_siswa,
                'id_kelas' => $request->id_kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,

                // UID dari form
                'rfid_uid' => $request->rfid_uid,

                'status' => 'aktif',
                'is_active' => 1
            ]);

            return redirect()->route('data-siswa')
                ->with('success', 'Data siswa berhasil ditambahkan');
        }
    // ========================
// FORM EDIT
// ========================
public function edit($id)
{
    $siswa = Siswa::findOrFail($id);
    $kelas = Kelas::orderBy('nama_kelas')->get();
    return view('admin.edit-data-siswa', compact('siswa', 'kelas'));
}

// ========================
// UPDATE DATA
// ========================
public function update(Request $request, $id)
{
    $request->validate([
        'nis' => 'required|unique:siswa,nis,'.$id.',id_siswa',
        'nama_siswa' => 'required',
        'id_kelas' => 'required',
        'jenis_kelamin' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required|date',
    ]);

    $siswa = Siswa::findOrFail($id);

    $siswa->update([
        'nis' => $request->nis,
        'nama_siswa' => $request->nama_siswa,
        'id_kelas' => $request->id_kelas,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tempat_lahir' => $request->tempat_lahir,
        'tanggal_lahir' => $request->tanggal_lahir,
    ]);

    return redirect()->route('data-siswa')
        ->with('success', 'Data siswa berhasil diupdate');
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

    public function kenaikanKelas()
    {
        DB::beginTransaction();

        try {

            // ==========================
            // ARSIPKAN SEMUA KELAS 6
            // ==========================
            $kelas6 = Kelas::where('nama_kelas', 'like', '6%')
                ->pluck('id_kelas');

            $siswaLulus = Siswa::whereIn('id_kelas', $kelas6)->get();

            foreach ($siswaLulus as $siswa) {

                $kelasTerakhir = Kelas::find($siswa->id_kelas);

                ArsipSiswa::create([
                    'id_siswa_lama' => $siswa->id_siswa,
                    'id_kelas' => $siswa->id_kelas,
                    'nis' => $siswa->nis,
                    'nama_siswa' => $siswa->nama_siswa,
                    'tempat_lahir' => $siswa->tempat_lahir,
                    'tanggal_lahir' => $siswa->tanggal_lahir,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'rfid_uid' => $siswa->rfid_uid,
                    'status' => 'lulus',
                    'tahun_lulus' => date('Y'),
                    'kelas_terakhir' => $kelasTerakhir->nama_kelas,
                ]);
            }

    foreach ($siswaLulus as $siswa) {

        $relasi = SiswaWali::where('id_siswa', $siswa->id_siswa)->first();

        if ($relasi) {

            $masihAdaSiswaAktif = SiswaWali::join(
                    'siswa',
                    'siswa.id_siswa',
                    '=',
                    'siswa_wali.id_siswa'
                )
                ->where('siswa_wali.id_wali', $relasi->id_wali)
                ->where('siswa.is_active', 1)
                ->where('siswa.id_siswa', '!=', $siswa->id_siswa)
                ->exists();

            if (!$masihAdaSiswaAktif) {

                Wali::where('id_wali', $relasi->id_wali)
                    ->update([
                        'is_active' => 0
                    ]);

                $userId = Wali::where('id_wali', $relasi->id_wali)
                    ->value('id_user');

                User::where('id', $userId)
                    ->update([
                        'status' => 'nonaktif'
                    ]);
            }
        }
    }

    // Hapus siswa kelas 6
    Siswa::whereIn('id_kelas', $kelas6)
        ->update([
            'status' => 'lulus',
            'is_active' => 0
        ]);

            // ==========================
            // SIMPAN SISWA 2D DULU
            // ==========================
            $kelas2D = Kelas::where('nama_kelas', '2D')->first();

            $siswa2D = collect();

            if ($kelas2D) {
                $siswa2D = Siswa::where('id_kelas', $kelas2D->id_kelas)->get();
            }

            // ==========================
            // KENAIKAN KELAS NORMAL
            // ==========================
            $mapping = [

                // kelas 5 -> 6
                '5A' => '6A',
                '5B' => '6B',
                '5C' => '6C',

                // kelas 4 -> 5
                '4A' => '5A',
                '4B' => '5B',
                '4C' => '5C',

                // kelas 3 -> 4
                '3A' => '4A',
                '3B' => '4B',
                '3C' => '4C',

                // kelas 2 -> 3
                '2A' => '3A',
                '2B' => '3B',
                '2C' => '3C',

                // kelas 1 -> 2
                '1A' => '2A',
                '1B' => '2B',
                '1C' => '2C',
            ];

            // Simpan snapshot siswa sebelum kenaikan
            $dataKenaikan = [];

            foreach ($mapping as $asal => $tujuan) {

                $kelasAsal = Kelas::where('nama_kelas', $asal)->first();
                $kelasTujuan = Kelas::where('nama_kelas', $tujuan)->first();

                if (!$kelasAsal || !$kelasTujuan) {
                    continue;
                }

                $dataKenaikan[] = [
                    'siswa_ids' => Siswa::where('id_kelas', $kelasAsal->id_kelas)
                                        ->pluck('id_siswa'),
                    'tujuan' => $kelasTujuan->id_kelas
                ];
            }

            // Baru lakukan update
            foreach ($dataKenaikan as $data) {

                Siswa::whereIn('id_siswa', $data['siswa_ids'])
                    ->update([
                        'id_kelas' => $data['tujuan']
                    ]);
            }

            // ==========================
            // KHUSUS 2D -> 3A,3B,3C
            // ==========================
            if ($siswa2D->count() > 0) {

                $kelas3A = Kelas::where('nama_kelas', '3A')->first();
                $kelas3B = Kelas::where('nama_kelas', '3B')->first();
                $kelas3C = Kelas::where('nama_kelas', '3C')->first();

                $tujuan = [
                    $kelas3A->id_kelas,
                    $kelas3B->id_kelas,
                    $kelas3C->id_kelas,
                ];

                $index = 0;

                foreach ($siswa2D as $siswa) {

                    Siswa::where('id_siswa', $siswa->id_siswa)
                        ->update([
                            'id_kelas' => $tujuan[$index]
                        ]);

                    $index++;

                    if ($index >= count($tujuan)) {
                        $index = 0;
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('data-siswa')
                ->with('success', 'Kenaikan kelas berhasil diproses.');

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->route('data-siswa')
                ->with('error', $e->getMessage());
        }
    }

    public function arsipSiswa(Request $request)
    {
        $query = ArsipSiswa::query();

        if ($request->search) {
            $query->where('nama_siswa', 'like', '%' . $request->search . '%')
                ->orWhere('nis', 'like', '%' . $request->search . '%');
        }

        $arsip = $query->paginate(10);

        return view('admin.arsip-siswa', compact('arsip'));
    }

    public function arsipkan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $siswa = Siswa::findOrFail($id);

        $kelas = Kelas::find($siswa->id_kelas);

        ArsipSiswa::create([
            'id_siswa_lama' => $siswa->id_siswa,
            'id_kelas' => $siswa->id_kelas,
            'nis' => $siswa->nis,
            'nama_siswa' => $siswa->nama_siswa,
            'tempat_lahir' => $siswa->tempat_lahir,
            'tanggal_lahir' => $siswa->tanggal_lahir,
            'jenis_kelamin' => $siswa->jenis_kelamin,
            'rfid_uid' => $siswa->rfid_uid,
            'status' => $request->status,
            'tahun_lulus' => date('Y'),
            'kelas_terakhir' => $kelas->nama_kelas ?? '-',
        ]);

        // NONAKTIFKAN SISWA
        $siswa->update([
            'is_active' => 0
        ]);

        // CARI WALI YANG TERHUBUNG
        $waliIds = DB::table('siswa_wali')
            ->where('id_siswa', $siswa->id_siswa)
            ->pluck('id_wali');

        // NONAKTIFKAN DATA WALI
        DB::table('wali')
            ->whereIn('id_wali', $waliIds)
            ->update([
                'is_active' => 0
            ]);

        return back()->with(
            'success',
            'Data siswa berhasil diarsipkan'
        );
    }
}
