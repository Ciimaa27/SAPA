<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wali;
use Illuminate\Validation\Rule;

class KelolaAkunController extends Controller
{
    // ========================
    // Tampilkan daftar akun
    // ========================
    public function index()
    {
        $users = DB::table('users')
            ->leftJoin('role', 'users.id_role', '=', 'role.id_role')
            ->select('users.*', 'role.nama_role')
            ->paginate(10);

        $total = DB::table('users')->count();

        return view('admin.kelola-akun', compact('users', 'total'));
    }

    // ========================
    // Form tambah akun
    // ========================
    public function create()
    {
        return view('admin.tambah-akun');
    }

    // ========================
    // Simpan akun
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'peran'        => ['required', Rule::in(['Admin', 'Orangtua/Wali', 'Kepala Sekolah'])],
            'password'     => 'nullable|string|min:6',
        ]);

        // ✅ Tentukan role
        $id_role = match($request->peran) {
            'Admin' => 1,
            'Guru' => 2,
            'Kepala Sekolah' => 3,
            'Orangtua/Wali' => 4,
            default => 4,
        };

        // ✅ Simpan user
        $user = User::create([
            'id_role'       => $id_role,
            'username'      => $request->username,
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'password'      => Hash::make($request->password ?? 'password123'),
            'status'        => 'aktif',
        ]);

        // ✅ Jika wali
        if ($id_role == 4) {
            Wali::create([
                'id_user'       => $user->id_user,
                'nama_wali'     => $request->nama_lengkap,
                'fingerprint_id'=> null,
                'no_wa'         => null,
                'no_hp'         => null,
                'is_active'     => 1
            ]);
        }

        return redirect()->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan!');
    }
}