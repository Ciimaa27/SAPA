<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wali;

class DataWaliController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $wali = DB::table('wali')
        ->leftJoin('users', 'wali.id_user', '=', 'users.id')
        ->select(
            'wali.id_wali',
            'wali.nama_wali',
            'wali.no_hp',
            'wali.jenis_kelamin',
            'wali.fingerprint_id',
            'users.username',
            'users.email'
        )
        ->where('wali.is_active', 1)

        // PENCARIAN SELURUH DATA
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('wali.nama_wali', 'like', '%' . $search . '%')
                  ->orWhere('wali.no_hp', 'like', '%' . $search . '%')
                  ->orWhere('wali.jenis_kelamin', 'like', '%' . $search . '%')
                  ->orWhere('wali.fingerprint_id', 'like', '%' . $search . '%')
                  ->orWhere('users.username', 'like', '%' . $search . '%')
                  ->orWhere('users.email', 'like', '%' . $search . '%');
            });
        })

        ->orderByDesc('wali.id_wali')
        ->paginate(10)
        ->withQueryString();

    $total = DB::table('wali')
        ->where('is_active', 1)
        ->count();

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
                    'fingerprint_id' => 'required|string|unique:wali,fingerprint_id',
                    'nama_wali' => 'required',
                    'no_hp' => 'required|unique:wali,no_hp',
                    'jenis_kelamin' => 'required',
                ]);

                Wali::create([
                    'fingerprint_id' => $request->fingerprint_id,
                    'nama_wali' => $request->nama_wali,
                    'no_hp' => $request->no_hp,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'is_active' => 1,
                ]);

                return redirect()->route('data-wali')
                    ->with('success', 'Data wali berhasil ditambahkan');
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
            'fingerprint_id' => 'required|string|unique:wali,fingerprint_id,'.$id.',id_wali',
            'nama_wali' => 'required',
            'no_hp' => 'required|unique:wali,no_hp,'.$id.',id_wali',
            'jenis_kelamin' => 'required',
        ]);

        $wali = Wali::findOrFail($id);
        $wali->update([
            'fingerprint_id' => $request->fingerprint_id,
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
