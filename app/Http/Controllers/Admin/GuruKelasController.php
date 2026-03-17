<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GuruKelasController extends Controller
{
    // HALAMAN GURU
    public function guru()
    {
        $guru = DB::table('guru')->paginate(10);
        $total = DB::table('guru')->count();

        return view('admin.guru', compact('guru', 'total'));
    }

    // HALAMAN KELAS
    public function kelas()
    {
        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.id_guru', '=', 'guru.id_guru')
            ->select(
                'kelas.id_kelas',
                'kelas.nama_kelas',
                'guru.nama_guru'
            )
            ->paginate(10);

        $total = DB::table('kelas')->count();

        return view('admin.kelas', compact('kelas', 'total'));
    }
}