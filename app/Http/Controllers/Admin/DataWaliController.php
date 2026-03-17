<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataWaliController extends Controller
{
    public function index()
    {
        // Ambil data wali + pagination
        $wali = DB::table('wali')->paginate(10);

        // Hitung total
        $total = DB::table('wali')->count();

        return view('admin.data-wali', compact('wali', 'total'));
    }

    public function destroy($id)
    {
        DB::table('wali')->where('id_wali', $id)->delete();

        return redirect()->back()->with('success', 'Data wali berhasil dihapus');
    }
}