<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FonnteService;
class DataPenjemputanController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }
    /*
    |--------------------------------------------------------------------------
    | HALAMAN DATA PENJEMPUTAN
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $cari = $request->cari;

        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->leftJoin('siswa', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->select(
                'kelas.id_kelas',
                'kelas.nama_kelas',
                'guru.nama_guru',
                DB::raw('COUNT(siswa.id_siswa) as jumlah_siswa')
            )
            ->groupBy(
                'kelas.id_kelas',
                'kelas.nama_kelas',
                'guru.nama_guru'
            );

        /*
        |--------------------------------------------------------------------------
        | PENCARIAN
        |--------------------------------------------------------------------------
        */
        if ($cari) {
            $kelas->where(function ($q) use ($cari) {
                $q->where('kelas.nama_kelas', 'like', "%{$cari}%")
                  ->orWhere('guru.nama_guru', 'like', "%{$cari}%");
            });
        }

        $kelas = $kelas
        ->orderBy('kelas.nama_kelas')
        ->paginate(9)
        ->withQueryString();

        return view('admin.data-penjemputan', compact('kelas'));
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN STATUS PENJEMPUTAN
    |--------------------------------------------------------------------------
    */
    public function status(Request $request, $id_kelas)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');

        /*
        |--------------------------------------------------------------------------
        | DATA KELAS
        |--------------------------------------------------------------------------
        */
        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->select(
                'kelas.id_kelas',
                'kelas.nama_kelas',
                'guru.nama_guru'
            )
            ->where('kelas.id_kelas', $id_kelas)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | DAFTAR SISWA + STATUS PENJEMPUTAN
        |--------------------------------------------------------------------------
        */
        $siswa = DB::table('siswa')
            ->leftJoin('penjemputan', function ($join) use ($tanggal) {
                $join->on('siswa.id_siswa', '=', 'penjemputan.id_siswa')
                    ->whereDate('penjemputan.tanggal', $tanggal);
            })
            ->select(
                'siswa.id_siswa',
                'siswa.nis',
                'siswa.nama_siswa',
                'penjemputan.id',
                'penjemputan.status',
                'penjemputan.id_wali'
            )
            ->where('siswa.id_kelas', $id_kelas)
            ->orderBy('siswa.nama_siswa')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PENJEMPUT SETIAP SISWA
        |--------------------------------------------------------------------------
        |
        | Mengambil data dari:
        | siswa_wali -> wali
        |
        | Hasil:
        | Ayah - Nama Ayah
        | Ibu  - Nama Ibu
        | Wali - Nama Wali
        |
        */
        $daftarPenjemput = DB::table('siswa_wali')
            ->join('wali', 'siswa_wali.id_wali', '=', 'wali.id_wali')
            ->select(
                'siswa_wali.id_siswa',
                'siswa_wali.id_wali',
                'siswa_wali.hubungan',
                'wali.nama_wali'
            )
            ->whereIn('siswa_wali.id_siswa', $siswa->pluck('id_siswa'))
            ->orderBy('siswa_wali.hubungan')
            ->get()
            ->groupBy('id_siswa');

        return view(
            'admin.status-penjemputan',
            compact('kelas', 'siswa', 'tanggal', 'daftarPenjemput')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS PENJEMPUTAN
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'tanggal'  => 'required|date',
            'status'   => ['required', 'in:Menunggu,Dijemput'],
            'id_wali'  => ['nullable', 'required_if:status,Dijemput'],
        ]);

        // =========================================
        // CEK DATA PENJEMPUTAN
        // =========================================

        $penjemputan = DB::table('penjemputan')
            ->where('id_siswa', $request->id_siswa)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        // Menentukan apakah WA perlu dikirim
        $kirimNotif = false;


        // =========================================
        // STATUS DIJEMPUT
        // =========================================

        if ($request->status === 'Dijemput') {

            // Pastikan wali memang terhubung dengan siswa
            $relasi = DB::table('siswa_wali')
                ->where('id_siswa', $request->id_siswa)
                ->where('id_wali', $request->id_wali)
                ->first();

            if (!$relasi) {
                return back()->with(
                    'error',
                    'Penjemput tidak terdaftar sebagai wali siswa.'
                );
            }


            // =====================================
            // DATA SUDAH ADA
            // =====================================

            if ($penjemputan) {

                // Kirim WA hanya jika sebelumnya belum Dijemput
                if ($penjemputan->status !== 'Dijemput') {
                    $kirimNotif = true;
                }

                DB::table('penjemputan')
                    ->where('id', $penjemputan->id)
                    ->update([
                        'id_wali' => $request->id_wali,
                        'status' => 'Dijemput',
                        'jam_jemput' => now()->format('H:i:s'),
                        'metode' => 'manual',
                    ]);

            } else {

                // =================================
                // DATA BELUM ADA
                // =================================

                DB::table('penjemputan')->insert([
                    'id_siswa' => $request->id_siswa,
                    'id_wali' => $request->id_wali,
                    'tanggal' => $request->tanggal,
                    'jam_jemput' => now()->format('H:i:s'),
                    'status' => 'Dijemput',
                    'metode' => 'manual',
                ]);

                $kirimNotif = true;
            }


            // =====================================
            // KIRIM NOTIFIKASI WA
            // =====================================

            if ($kirimNotif) {

                // Ambil data siswa
                $siswa = DB::table('siswa')
                    ->where('id_siswa', $request->id_siswa)
                    ->first();


                // Ambil data penjemput
                $penjemput = DB::table('wali')
                    ->join(
                        'siswa_wali',
                        'wali.id_wali',
                        '=',
                        'siswa_wali.id_wali'
                    )
                    ->where(
                        'wali.id_wali',
                        $request->id_wali
                    )
                    ->where(
                        'siswa_wali.id_siswa',
                        $request->id_siswa
                    )
                    ->select(
                        'wali.nama_wali',
                        'siswa_wali.hubungan'
                    )
                    ->first();


                // Ambil semua wali tujuan
                $waliTujuan = DB::table('siswa_wali')
                    ->join(
                        'wali',
                        'siswa_wali.id_wali',
                        '=',
                        'wali.id_wali'
                    )
                    ->where(
                        'siswa_wali.id_siswa',
                        $request->id_siswa
                    )
                    ->whereNotNull('wali.no_hp')
                    ->select(
                        'wali.id_wali',
                        'wali.id_user',
                        'wali.nama_wali',
                        'wali.no_hp'
                    )
                    ->get();


                foreach ($waliTujuan as $wali) {

                    // Format sama dengan fingerprint
                    $pesan =
                        "Assalamu'alaikum Wr. Wb.\n"
                        . "Yth. Bapak/Ibu {$wali->nama_wali},\n\n"
                        . "Kami informasikan bahwa {$siswa->nama_siswa} "
                        . "telah dijemput oleh {$penjemput->nama_wali} "
                        . "({$penjemput->hubungan}) "
                        . "pada pukul "
                        . now()->format('H:i')
                        . ".\n\n"
                        . "Terima kasih.";


                    // Kirim WA
                    $hasil = $this->fonnte->kirim(
                        $wali->no_hp,
                        $pesan
                    );


                    // Simpan notifikasi jika wali punya akun
                    if ($wali->id_user) {

                        DB::table('notifikasi')->insert([
                            'id_user' => $wali->id_user,
                            'id_siswa' => $request->id_siswa,
                            'id_wali' => $wali->id_wali,
                            'judul' => 'Informasi Penjemputan',
                            'pesan' => $pesan,
                            'tipe' => 'penjemputan',
                            'status' => 'terkirim',
                            'is_pushed' => 1,
                            'tipe_notif' => 'wa',

                            'status_wa' =>
                                isset($hasil['status'])
                                && $hasil['status']
                                    ? 'sukses'
                                    : 'gagal',

                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

        } else {

            // =========================================
            // STATUS MENUNGGU
            // =========================================

            if ($penjemputan) {

                DB::table('penjemputan')
                    ->where('id', $penjemputan->id)
                    ->update([
                        'status' => 'Menunggu',
                        'id_wali' => null,
                        'jam_jemput' => null,
                        'metode' => 'manual',
                    ]);
            }
        }


        return back()->with(
            'success',
            'Status penjemputan berhasil diperbarui.'
        );
    }
}
