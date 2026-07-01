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
            ->leftJoin('wali', 'wali.id_user', '=', 'users.id')
            ->select('users.*', 'role.nama_role')
            ->where('users.status', 'aktif')
            ->where(function ($q) {
                $q->where('users.id_role', '!=', 4)
                  ->orWhere(function ($q2) {
                      $q2->where('users.id_role', 4)
                         ->where('wali.is_active', 1);
                  });
            });

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('users.username', 'like', '%' . $request->search . '%')
                  ->orWhere('users.nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        }

        // FILTER ROLE
        if ($request->filled('role')) {
            $query->where('role.nama_role', $request->role);
        }

        $total = $query->count();

        $users = $query
            ->orderBy('users.id', 'asc')
            ->paginate(10)
            ->withQueryString();

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
        $user = User::where('id', $id)->firstOrFail();

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
                'password'     => 'required|string|min:6|confirmed',
            ], [
                'password.required'  => 'Password wajib diisi.',
                'password.min'       => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
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
            'password' => Hash::make($request->password),
            'status'        => 'aktif',
        ]);

        // 🔥 FIX: pakai id_user
        if ($id_role == 4) {
            Wali::create([
                'id_user'        => $user->id,
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
        $user = User::where('id', $id)->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id . ',id',
            'email'    => 'required|email|unique:users,email,' . $user->id . ',id',
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
                'id_user'        => $user->id,
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
        $user = User::where('id', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
