<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArsipSiswa;

class ArsipSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipSiswa::query();

        if ($request->search) {
            $query->where('nis', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_siswa', 'like', '%' . $request->search . '%');
        }

       $arsip = $query->orderBy('id_arsip', 'desc')
               ->paginate(10)
               ->withQueryString();

        $total = ArsipSiswa::count();

        return view('admin.arsip-siswa', compact('arsip', 'total'));
    }
}