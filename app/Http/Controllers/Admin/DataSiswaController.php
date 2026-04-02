<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class DataSiswaController extends Controller
{
    public function index()
    {
        // UBAH DI SINI
        $siswa = Siswa::all();
        $total = $siswa->count();

        return view('admin.data-siswa', compact('siswa', 'total'));
    }

    public function destroy($id)
    {
        $data = Siswa::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus');
    }
}
