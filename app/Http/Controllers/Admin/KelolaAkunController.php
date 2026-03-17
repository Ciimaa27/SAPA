<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KelolaAkunController extends Controller
{
    public function index()
    {
        // Ambil data users beserta nama role dari tabel role
        $users = DB::table('users')
            ->leftJoin('role', 'users.id_role', '=', 'role.id_role')
            ->select('users.*', 'role.nama_role') // gunakan kolom 'nama_role'
            ->paginate(10);

        $total = DB::table('users')->count();

        return view('admin.kelola-akun', compact('users', 'total'));
    }
}