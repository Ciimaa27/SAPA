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
            ->leftJoin('users', 'wali.id_user', '=', 'users.id')
            ->select(
                'wali.id_wali',
                'wali.nama_wali',
                'wali.no_hp',
                'wali.jenis_kelamin',
                'users.username',
                'users.email'
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
            'id_user' => auth()->user()->id,
            'nama_wali' => $request->nama_wali,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('data-wali')->with('success', 'Data wali berhasil ditambahkan'); 
    }

    // ========================
    // FORM EDIT
    // ========================
    public function edit($id)
    {
        $wali = Wali::findOrFail($id);
        return view('admin.edit-data-wali', compact('wali'));
    }

    // ========================
    // UPDATE DATA
    // ========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_wali' => 'required|unique:wali,nama_wali,'.$id.',id_wali',
            'no_hp' => 'required|unique:wali,no_hp,'.$id.',id_wali',
            'jenis_kelamin' => 'required',
        ]);

        $wali = Wali::findOrFail($id);
        $wali->update([
            'nama_wali' => $request->nama_wali,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('data-wali')->with('success', 'Data wali berhasil diupdate');
    }

    // ========================
    // HAPUS
    // ========================
    public function destroy($id)
    {
        $wali = Wali::findOrFail($id);
        $wali->delete();

        return redirect()->back()->with('success', 'Data wali berhasil dihapus');
    }
}