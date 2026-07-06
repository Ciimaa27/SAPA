<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPenjemputanController extends Controller
{
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

        $kelas = $kelas->orderBy('kelas.nama_kelas')->get();

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
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'id_siswa' => 'required',
            'tanggal'  => 'required|date',
            'status'   => ['required', 'in:Menunggu,Dijemput'],
            /* id_wali wajib jika status Dijemput */
            'id_wali'  => ['nullable', 'required_if:status,Dijemput'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK PENJEMPUTAN YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */
        $penjemputan = DB::table('penjemputan')
            ->where('id_siswa', $request->id_siswa)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | STATUS DIJEMPUT
        |--------------------------------------------------------------------------
        */
        if ($request->status === 'Dijemput') {
            /*
            |--------------------------------------------------------------------------
            | VALIDASI RELASI SISWA DAN WALI
            |--------------------------------------------------------------------------
            | Memastikan wali yang dipilih memang terhubung dengan siswa tersebut.
            */
            $relasi = DB::table('siswa_wali')
                ->where('id_siswa', $request->id_siswa)
                ->where('id_wali', $request->id_wali)
                ->first();

            if (!$relasi) {
                return back()->with('error', 'Penjemput tidak terdaftar sebagai wali siswa.');
            }

            /*
            |--------------------------------------------------------------------------
            | JIKA DATA SUDAH ADA
            |--------------------------------------------------------------------------
            */
            if ($penjemputan) {
                DB::table('penjemputan')
                    ->where('id', $penjemputan->id)
                    ->update([
                        'id_wali'    => $request->id_wali,
                        'status'     => 'Dijemput',
                        'jam_jemput' => now()->format('H:i:s'),
                    ]);
            }
            /*
            |--------------------------------------------------------------------------
            | JIKA DATA BELUM ADA
            |--------------------------------------------------------------------------
            */
            else {
                DB::table('penjemputan')->insert([
                    'id_siswa'   => $request->id_siswa,
                    'id_wali'    => $request->id_wali,
                    'tanggal'    => $request->tanggal,
                    'jam_jemput' => now()->format('H:i:s'),
                    'status'     => 'Dijemput',
                ]);
            }
        }
        /*
        |--------------------------------------------------------------------------
        | STATUS MENUNGGU
        |--------------------------------------------------------------------------
        */
        else {
            /*
            | Jika sebelumnya sudah ada data penjemputan,
            | ubah status menjadi Menunggu.
            */
            if ($penjemputan) {
                DB::table('penjemputan')
                    ->where('id', $penjemputan->id)
                    ->update([
                        'status'     => 'Menunggu',
                        'id_wali'    => null,
                        'jam_jemput' => null,
                    ]);
            }
        }

        return back()->with('success', 'Status penjemputan berhasil diperbarui.');
    }
}
