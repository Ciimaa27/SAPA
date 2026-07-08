<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Guru;
use Carbon\Carbon;
use App\Services\FonnteService;

class GuruKelasController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    // ========================
    // HALAMAN GURU
    // ========================
    public function guru(Request $request)
    {
        $kelasId = $request->query('kelas', null);
        $search = $request->query('search', null);

        // DROPDOWN SEMUA KELAS
        $kelasOptions = DB::table('kelas')
            ->select('id_kelas', 'nama_kelas', 'id_guru')
            ->orderByRaw('CAST(SUBSTRING(nama_kelas, 1, 1) AS UNSIGNED) ASC')
            ->orderByRaw('SUBSTRING(nama_kelas, 2) ASC')
            ->get();

        // QUERY AWAL GURU
        $query = Guru::query();

        // FILTER KELAS
        if ($kelasId) {
            $idGuru = DB::table('kelas')
                ->where('id_kelas', $kelasId)
                ->value('id_guru');

            if ($idGuru) {
                $query->where('id_guru', $idGuru);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        // PENCARIAN
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_guru', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%')
                    ->orWhere('tempat_lahir', 'like', '%' . $search . '%')
                    ->orWhere('tanggal_lahir', 'like', '%' . $search . '%');
            });
        }

        // TOTAL DATA SESUAI FILTER
        $total = (clone $query)->count();

        // PAGINATION
        $guru = $query->orderByDesc('id_guru')
            ->paginate(10)
            ->withQueryString();

        return view('admin.guru', compact('guru', 'total', 'kelasOptions', 'kelasId'));
    }

    // ========================
    // HALAMAN KELAS
    // ========================
    public function kelas(Request $request)
    {
        $search = $request->query('search', null);

        $query = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->leftJoin('siswa', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->select(
                'kelas.id_kelas',
                'kelas.nama_kelas',
                'kelas.id_guru',
                'guru.nama_guru',
                DB::raw('COUNT(siswa.id_siswa) as jumlah_siswa')
            );

        // PENCARIAN
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kelas.nama_kelas', 'like', '%' . $search . '%')
                    ->orWhere('guru.nama_guru', 'like', '%' . $search . '%');
            });
        }

        $kelas = $query->groupBy('kelas.id_kelas', 'kelas.nama_kelas', 'kelas.id_guru', 'guru.nama_guru')
            ->orderByRaw('CAST(SUBSTRING(kelas.nama_kelas, 1, 1) AS UNSIGNED) ASC')
            ->orderByRaw('SUBSTRING(kelas.nama_kelas, 2) ASC')
            ->paginate(10)
            ->withQueryString();

        $total = DB::table('kelas')->count();

        return view('admin.kelas', compact('kelas', 'total'));
    }

    // ========================
    // FORM TAMBAH KELAS
    // ========================
    public function createKelas()
    {
        $guru = Guru::orderBy('nama_guru')->get();

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
            'id_guru' => 'required|exists:guru,id_guru',
        ]);

        $namaKelas = $request->tingkat . $request->sub_kelas;

        // CEK DUPLIKAT KELAS
        $kelasSudahAda = DB::table('kelas')
            ->where('nama_kelas', $namaKelas)
            ->exists();

        if ($kelasSudahAda) {
            return back()
                ->withErrors(['nama_kelas' => 'Kelas sudah ada!'])
                ->withInput();
        }

        DB::table('kelas')->insert([
            'nama_kelas' => $namaKelas,
            'id_guru' => $request->id_guru,
        ]);

        return redirect()->route('kelas')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    // ========================
    // DATA SISWA PER KELAS
    // ========================
    public function siswaKelas(Request $request, $id)
    {
        $tanggal = $request->query('tanggal');

        if ($tanggal) {
            $tanggal = date('Y-m-d', strtotime($tanggal));
        } else {
            $tanggal = Carbon::today()->toDateString();
        }

        $today = Carbon::today()->toDateString();
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();

        // TANGGAL KEHADIRAN YANG TERSEDIA
        $availableDates = DB::table('kehadiran')
            ->join('siswa', 'kehadiran.id_siswa', '=', 'siswa.id_siswa')
            ->where('siswa.id_kelas', $id)
            ->whereBetween('kehadiran.tanggal', [$sevenDaysAgo, $today])
            ->distinct()
            ->orderByDesc('kehadiran.tanggal')
            ->pluck('kehadiran.tanggal');

        // DATA KELAS
        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->select('kelas.id_kelas', 'kelas.nama_kelas', 'kelas.id_guru', 'guru.nama_guru')
            ->where('kelas.id_kelas', $id)
            ->first();

        // DATA SISWA DAN KEHADIRAN
        $siswa = DB::table('siswa')
            ->leftJoin('kehadiran', function ($join) use ($tanggal) {
                $join->on('siswa.id_siswa', '=', 'kehadiran.id_siswa')
                    ->whereDate('kehadiran.tanggal', '=', $tanggal);
            })
            ->where('siswa.id_kelas', $id)
            ->where('siswa.is_active', 1)
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

            // LEWATI STATUS KOSONG
            if ($status_hadir === '') {
                continue;
            }

            $keteranganText = $keterangan[$id_siswa] ?? null;

            $kehadiran = DB::table('kehadiran')
                ->where('id_siswa', $id_siswa)
                ->where('tanggal', $tanggal)
                ->first();

            // DEFAULT TIDAK KIRIM NOTIF
            $kirimNotif = false;

            // =================================
            // DATA KEHADIRAN SUDAH ADA
            // =================================
            if ($kehadiran) {
                // KIRIM NOTIF JIKA STATUS BERUBAH
                if (strtolower($kehadiran->status_hadir) !== $status_hadir) {
                    $kirimNotif = true;
                }

                DB::table('kehadiran')
                    ->where('id_siswa', $id_siswa)
                    ->where('tanggal', $tanggal)
                    ->update([
                        'status_hadir' => $status_hadir,
                        'keterangan' => $keteranganText,
                    ]);
            }
            // =================================
            // DATA KEHADIRAN BELUM ADA
            // =================================
            else {
                DB::table('kehadiran')->insert([
                    'id_siswa' => $id_siswa,
                    'id_device' => 1,
                    'tanggal' => $tanggal,
                    'metode' => 'manual',
                    'status_hadir' => $status_hadir,
                    'keterangan' => $keteranganText,
                ]);

                $kirimNotif = true;
            }

            // =================================
            // KIRIM NOTIFIKASI
            // =================================
            if ($kirimNotif) {
                $siswa = DB::table('siswa')
                    ->where('id_siswa', $id_siswa)
                    ->first();

                if (!$siswa) {
                    continue;
                }

                $waliTujuan = DB::table('siswa_wali')
                    ->join('wali', 'siswa_wali.id_wali', '=', 'wali.id_wali')
                    ->where('siswa_wali.id_siswa', $id_siswa)
                    ->whereNotNull('wali.no_hp')
                    ->select('wali.id_wali', 'wali.id_user', 'wali.nama_wali', 'wali.no_hp')
                    ->get();

                foreach ($waliTujuan as $wali) {
                    $statusLabel = ucfirst($status_hadir);

                    // STATUS HADIR
                    if ($status_hadir === 'hadir') {
                        $pesan = "Assalamu'alaikum Wr. Wb.\n"
                            . "Yth. Bapak/Ibu {$wali->nama_wali},\n\n"
                            . "Kami informasikan bahwa {$siswa->nama_siswa} "
                            . "telah melakukan absensi masuk sekolah "
                            . "pada pukul " . now()->format('H:i') . ".\n\n"
                            . "Terima kasih.";
                    }
                    // STATUS SELAIN HADIR
                    else {
                        $pesan = "Assalamu'alaikum Wr. Wb.\n"
                            . "Yth. Bapak/Ibu {$wali->nama_wali},\n\n"
                            . "Kami informasikan bahwa {$siswa->nama_siswa} "
                            . "memiliki status kehadiran {$statusLabel} "
                            . "pada tanggal " . Carbon::parse($tanggal)->format('d-m-Y') . ".";

                        if ($keteranganText) {
                            $pesan .= "\nKeterangan: {$keteranganText}";
                        }

                        $pesan .= "\n\nTerima kasih.";
                    }

                    // ========================
                    // KIRIM WHATSAPP
                    // ========================
                    $hasil = $this->fonnte->kirim($wali->no_hp, $pesan);

                    // ========================
                    // SIMPAN NOTIFIKASI
                    // ========================
                    if ($wali->id_user) {
                        DB::table('notifikasi')->insert([
                            'id_user' => $wali->id_user,
                            'id_siswa' => $id_siswa,
                            'id_wali' => $wali->id_wali,
                            'judul' => 'Informasi Kehadiran',
                            'pesan' => $pesan,
                            'tipe' => 'kehadiran',
                            'status' => 'terkirim',
                            'is_pushed' => 1,
                            'tipe_notif' => 'wa',
                            'status_wa' => isset($hasil['status']) && $hasil['status'] ? 'sukses' : 'gagal',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
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
            'nama_guru' => 'required|string|max:255',
            'nip' => 'required|unique:guru,nip,' . $id . ',id_guru',
            'no_hp' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:255',
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
            ->with('success', 'Data guru berhasil diperbarui');
    }

    // ========================
    // SIMPAN GURU
    // ========================
    public function storeGuru(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'nip' => 'required|unique:guru,nip',
            'no_hp' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
        ]);

        Guru::create([
            'id_user' => null,
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
