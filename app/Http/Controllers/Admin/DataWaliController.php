<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataWaliController extends Controller
{
    public function index()
    {
        // Ambil semua data wali dari tabel wali, beserta nama lengkap jika ada di tabel users
        $wali = DB::table('wali')
            ->leftJoin('users', 'wali.id_user', '=', 'users.id_user')
            ->select(
                'wali.id_wali',
                DB::raw('COALESCE(users.nama_lengkap, wali.nama_wali) as nama_wali'),
                'wali.no_hp',
                'wali.jenis_kelamin'
            )
            ->paginate(10);

        // Hitung total wali berdasarkan tabel wali
        $total = DB::table('wali')->count();

        return view('admin.data-wali', compact('wali', 'total'));
    }

    public function destroy($id)
    {
        DB::table('wali')->where('id_wali', $id)->delete();

        return redirect()->back()->with('success', 'Data wali berhasil dihapus');
    }
}