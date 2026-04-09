<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wali;

class DataWaliController extends Controller
{
    public function index()
    {
        $wali = DB::table('wali')
            ->leftJoin('users', 'wali.id_user', '=', 'users.id_user')
            ->select(
                'wali.id_wali',
                'wali.nama_wali', // 🔥 FIX DI SINI
                'wali.no_hp',
                'wali.jenis_kelamin'
            )
            ->orderByDesc('wali.id_wali')
            ->paginate(10);

        $total = DB::table('wali')->count();

        return view('admin.data-wali', compact('wali', 'total'));
    }

    // ========================
    // FORM TAMBAH
    // ========================
    public function create()
    {
        return view('admin.tambah-data-wali');
    }

    // ========================
    // SIMPAN DATA
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_wali' => 'required|unique:wali,nama_wali',
            'no_hp' => 'required|unique:wali,no_hp',
            'jenis_kelamin' => 'required',
        ]);

        Wali::create([
            'id_user' => auth()->user()->id_user, // 🔥 FIX
            'nama_wali' => $request->nama_wali,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('data-wali')->with('success', 'Data wali berhasil ditambahkan'); 
    }
    // ========================
    // HAPUS
    // ========================
    public function destroy($id)
    {
        DB::table('wali')->where('id_wali', $id)->delete();

        return redirect()->back()->with('success', 'Data wali berhasil dihapus');
    }
}