<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use Carbon\Carbon;

class GuruKelasController extends Controller
{
    // ========================
    // HALAMAN GURU
    // ========================
    public function guru()
    {
        $guru = Guru::paginate(10);
        $total = Guru::count();

        return view('admin.guru', compact('guru', 'total'));
    }

    // ========================
    // HALAMAN KELAS
    // ========================
    public function kelas()
    {
        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->leftJoin('siswa', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->select(
                'kelas.id_kelas',
                'kelas.nama_kelas',
                'kelas.id_guru',
                'guru.nama_guru',
                DB::raw('COUNT(siswa.id_siswa) as jumlah_siswa')
            )
            ->groupBy('kelas.id_kelas', 'kelas.nama_kelas', 'kelas.id_guru', 'guru.nama_guru')
            ->paginate(10);

        $total = DB::table('kelas')->count();

        return view('admin.kelas', compact('kelas', 'total'));
    }

    // ========================
    // FORM TAMBAH KELAS
    // ========================
    public function createKelas()
    {
        $guru = Guru::all();

        return view('admin.tambah-data-kelas', compact('guru'));
    }

    // ========================
    // SIMPAN KELAS
    // ========================
    public function storeKelas(Request $request)
    {
        $request->validate([
            'tingkat' => 'required',
            'sub_kelas' => 'required',
            'id_guru' => 'required',
        ]);

        $namaKelas = 'Kelas '.$request->tingkat.'-'.$request->sub_kelas;

        // 🚫 CEK DUPLIKAT
        $cek = DB::table('kelas')
            ->where('nama_kelas', $namaKelas)
            ->exists();

        if ($cek) {
            return back()->withErrors('Kelas sudah ada!')->withInput();
        }

        DB::table('kelas')->insert([
            'nama_kelas' => $namaKelas,
            'id_guru' => $request->id_guru
        ]);

        return redirect()->route('kelas')
            ->with('success','Kelas berhasil ditambahkan');
    }

    // ========================
    // DATA SISWA PER KELAS
    // ========================
    public function siswaKelas(Request $request, $id)
    {
        $tanggal = $request->query('tanggal', null);

        if ($tanggal) {
            $tanggal = date('Y-m-d', strtotime($tanggal));
        }

        $today = Carbon::today()->toDateString();
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();

        $availableDates = DB::table('kehadiran')
            ->join('siswa', 'kehadiran.id_siswa', '=', 'siswa.id_siswa')
            ->where('siswa.id_kelas', $id)
            ->whereBetween('kehadiran.tanggal', [$sevenDaysAgo, $today])
            ->distinct()
            ->orderByDesc('kehadiran.tanggal')
            ->pluck('kehadiran.tanggal');

        if (!$tanggal && $availableDates->isNotEmpty()) {
            $tanggal = $availableDates->first();
        }

        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->select('kelas.id_kelas', 'kelas.nama_kelas', 'kelas.id_guru', 'guru.nama_guru')
            ->where('kelas.id_kelas', $id)
            ->first();

        $siswa = DB::table('siswa')
            ->leftJoin('kehadiran', function ($join) use ($tanggal) {
                $join->on('siswa.id_siswa', '=', 'kehadiran.id_siswa')
                     ->whereDate('kehadiran.tanggal', '=', $tanggal);
            })
            ->where('siswa.id_kelas', $id)
            ->select(
                'siswa.id_siswa',
                'siswa.nis',
                'siswa.nama_siswa',
                'kehadiran.status_hadir',
                'kehadiran.keterangan',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar',
                'kehadiran.metode'
            )
            ->orderBy('siswa.nama_siswa')
            ->get();

        return view('admin.siswa-kelas', compact('kelas', 'siswa', 'tanggal', 'availableDates'));
    }

    // ========================
    // UPDATE KEHADIRAN
    // ========================
    public function updateKehadiranKelas(Request $request, $id)
    {
        $tanggal = $request->input('tanggal');
        $status = $request->input('status', []);
        $keterangan = $request->input('keterangan', []);

        foreach ($status as $id_siswa => $status_hadir) {

            $status_hadir = strtolower(trim($status_hadir));
            $keteranganText = $keterangan[$id_siswa] ?? null;

            $query = DB::table('kehadiran')
                ->where('id_siswa', $id_siswa)
                ->where('tanggal', $tanggal);

            $kehadiran = $query->first();

            if ($kehadiran) {
                $updateData = [];

                if ($status_hadir !== '') {
                    $updateData['status_hadir'] = $status_hadir;
                }

                if ($keteranganText !== null) {
                    $updateData['keterangan'] = $keteranganText;
                }

                if (!empty($updateData)) {
                    $query->update($updateData);
                }

            } elseif ($status_hadir !== '') {

                DB::table('kehadiran')->insert([
                    'id_siswa' => $id_siswa,
                    'id_device' => 1,
                    'tanggal' => $tanggal,
                    'metode' => 'manual',
                    'status_hadir' => $status_hadir,
                    'keterangan' => $keteranganText,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Data kehadiran berhasil disimpan');
    }

    // ========================
    // DETAIL GURU
    // ========================
    public function detailGuru($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.detail-guru', compact('guru'));
    }

    // ========================
    // EDIT GURU
    // ========================
    public function editGuru($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.edit-data-guru', compact('guru'));
    }

    // ========================
    // UPDATE GURU
    // ========================
    public function updateGuru(Request $request, $id)
    {
        $request->validate([
            'nama_guru' => 'required',
            'nip' => 'required',
            'no_hp' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        $guru = Guru::findOrFail($id);

        $guru->update([
            'nama_guru' => $request->nama_guru,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->route('guru')
            ->with('success', 'Data guru berhasil diupdate');
    }

    // ========================
    // SIMPAN GURU
    // ========================
    public function storeGuru(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required',
            'nip' => 'required|unique:guru,nip',
            'no_hp' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
        ]);

        Guru::create([
            'id_user' => auth()->user()->id,
            'nama_guru' => $request->nama_guru,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return redirect()->route('guru')
            ->with('success', 'Data guru berhasil ditambahkan');
    }

    // ========================
    // HAPUS GURU
    // ========================
    public function destroyGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->back()
            ->with('success', 'Data guru berhasil dihapus');
    }
}