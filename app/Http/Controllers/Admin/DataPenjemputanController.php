<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPenjemputanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $kelas = $request->input('kelas');
        $cari = $request->input('cari');

        $query = DB::table('penjemputan')
        ->join('siswa', 'penjemputan.id_siswa', '=', 'siswa.id_siswa')
        ->join('wali', 'penjemputan.id_wali', '=', 'wali.id_wali')
        ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas') // join ke tabel kelas
        ->select(
            'penjemputan.id_jemput',
            'siswa.nama_siswa',
            'kelas.nama_kelas as kelas', // alias 'kelas' agar sesuai dengan Blade
            'wali.nama_wali',
            'penjemputan.tanggal',
            'penjemputan.jam_jemput'
        );

        if ($tanggal) {
            $query->whereDate('penjemputan.tanggal', $tanggal);
        }

        if ($kelas && $kelas !== 'Kelas') {
            $query->where('kelas.nama_kelas', $kelas);
        }

        if ($cari) {
            $query->where('siswa.nama_siswa', 'like', "%{$cari}%");
        }

        $data = $query->orderBy('penjemputan.tanggal', 'desc')->get();

        return view('admin.data-penjemputan', compact('data', 'tanggal', 'kelas', 'cari'));
    }
}