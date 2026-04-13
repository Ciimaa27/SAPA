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
    public function index(Request $request)
    {
        $query = DB::table('users')
            ->leftJoin('role', 'users.id_role', '=', 'role.id_role')
            ->select('users.*', 'role.nama_role');

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('users.username', 'like', '%' . $request->search . '%')
                  ->orWhere('users.nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }

        // 🔥 FIX DI SINI (pakai id_user)
        $users = $query
            ->orderBy('users.id_user', 'asc')
            ->paginate(10)
            ->withQueryString();

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
    // Form edit akun
    // ========================
    public function edit($id)
    {
        // 🔥 FIX: pakai id_user
        $user = User::where('id_user', $id)->firstOrFail();

        return view('admin.edit-kelola-akun', compact('user'));
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
            'peran'        => ['required', Rule::in(['Admin', 'Guru', 'Kepala Sekolah', 'Orangtua/Wali'])],
            'password'     => 'nullable|string|min:6',
        ]);

        $id_role = match($request->peran) {
            'Admin' => 1,
            'Guru' => 2,
            'Kepala Sekolah' => 3,
            'Orangtua/Wali' => 4,
            default => 4,
        };

        $user = User::create([
            'id_role'       => $id_role,
            'username'      => $request->username,
            'nama_lengkap'  => $request->nama_lengkap,
            'email'         => $request->email,
            'password'      => Hash::make($request->password ?? 'password123'),
            'status'        => 'aktif',
        ]);

        // 🔥 FIX: pakai id_user
        if ($id_role == 4) {
            Wali::create([
                'id_user'        => $user->id_user,
                'nama_wali'      => $request->nama_lengkap,
                'fingerprint_id' => null,
                'no_wa'          => null,
                'no_hp'          => null,
                'is_active'      => 1
            ]);
        }

        return redirect()->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan!');
    }

    // ========================
    // Update akun
    // ========================
    public function update(Request $request, $id)
    {
        // 🔥 FIX: pakai id_user
        $user = User::where('id_user', $id)->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id_user . ',id_user',
            'email'    => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'peran'    => ['required', Rule::in(['Admin', 'Guru', 'Kepala Sekolah', 'Orangtua/Wali'])],
        ]);

        $id_role = match($request->peran) {
            'Admin' => 1,
            'Guru' => 2,
            'Kepala Sekolah' => 3,
            'Orangtua/Wali' => 4,
            default => 4,
        };

        $user->update([
            'id_role'      => $id_role,
            'username'     => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
        ]);

        if ($id_role == 4 && !$user->wali) {
            Wali::create([
                'id_user'        => $user->id_user,
                'nama_wali'      => $user->nama_lengkap,
                'fingerprint_id' => null,
                'no_wa'          => null,
                'no_hp'          => null,
                'is_active'      => 1
            ]);
        }

        return redirect()->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil diperbarui!');
    }

    // ========================
    // Hapus akun
    // ========================
    public function destroy($id)
    {
        // 🔥 FIX: pakai id_user
        $user = User::where('id_user', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
