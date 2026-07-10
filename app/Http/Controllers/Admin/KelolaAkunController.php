<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guru;
use App\Models\Wali;
use Illuminate\Validation\Rule;

class KelolaAkunController extends Controller
{
    // ========================
    // TAMPILKAN DAFTAR AKUN
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
        if ($request->filled('search')) {
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

        $total = (clone $query)->count();

        $users = $query->orderBy('users.id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kelola-akun', compact('users', 'total'));
    }

    // ========================
    // FORM TAMBAH AKUN
    // ========================
    public function create()
    {
        // Guru yang belum mempunyai akun
        $guru = Guru::whereNull('id_user')
            ->orderBy('nama_guru', 'asc')
            ->get();

        // Wali aktif yang belum mempunyai akun
        $wali = Wali::whereNull('id_user')
            ->where('is_active', 1)
            ->orderBy('nama_wali', 'asc')
            ->get();

        return view('admin.tambah-akun', compact('guru', 'wali'));
    }

    // ========================
    // FORM EDIT AKUN
    // ========================
    public function edit($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        return view('admin.edit-kelola-akun', compact('user'));
    }

    // ========================
    // SIMPAN AKUN
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'peran' => ['required', Rule::in(['Admin', 'Guru', 'Kepala Sekolah', 'Orangtua/Wali'])],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'data_user' => ['nullable', 'integer'],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // TENTUKAN ROLE
        $id_role = match ($request->peran) {
            'Admin' => 1,
            'Guru' => 2,
            'Kepala Sekolah' => 3,
            'Orangtua/Wali' => 4,
        };

        // VALIDASI PILIHAN GURU
        if ($id_role == 2 && !$request->filled('data_user')) {
            return back()->withInput()->withErrors(['data_user' => 'Silakan pilih guru.']);
        }

        // VALIDASI PILIHAN WALI
        if ($id_role == 4 && !$request->filled('data_user')) {
            return back()->withInput()->withErrors(['data_user' => 'Silakan pilih wali.']);
        }

        // CEK GURU
        if ($id_role == 2) {
            $guru = Guru::where('id_guru', $request->data_user)
                ->whereNull('id_user')
                ->first();

            if (!$guru) {
                return back()->withInput()->withErrors([
                    'data_user' => 'Data guru tidak ditemukan atau sudah memiliki akun.'
                ]);
            }
        }

        // CEK WALI
        if ($id_role == 4) {
            $wali = Wali::where('id_wali', $request->data_user)
                ->whereNull('id_user')
                ->where('is_active', 1)
                ->first();

            if (!$wali) {
                return back()->withInput()->withErrors([
                    'data_user' => 'Data wali tidak ditemukan atau sudah memiliki akun.'
                ]);
            }
        }

        // TRANSACTION
        DB::transaction(function () use ($request, $id_role) {
            // BUAT USER
            $user = User::create([
                'id_role' => $id_role,
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'aktif',
            ]);

            // HUBUNGKAN KE GURU
            if ($id_role == 2) {
                Guru::where('id_guru', $request->data_user)
                    ->whereNull('id_user')
                    ->update(['id_user' => $user->id]);
            }

            // HUBUNGKAN KE WALI
            if ($id_role == 4) {
                Wali::where('id_wali', $request->data_user)
                    ->whereNull('id_user')
                    ->where('is_active', 1)
                    ->update(['id_user' => $user->id]);
            }
        });

        return redirect()
            ->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan!');
    }

    // ========================
    // UPDATE AKUN
    // ========================
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id, 'id')],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id, 'id')],
            'peran' => ['required', Rule::in(['Admin', 'Guru', 'Kepala Sekolah', 'Orangtua/Wali'])],
        ]);

        $id_role = match ($request->peran) {
            'Admin' => 1,
            'Guru' => 2,
            'Kepala Sekolah' => 3,
            'Orangtua/Wali' => 4,
        };

        $user->update([
            'id_role' => $id_role,
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil diperbarui!');
    }

    // ========================
    // HAPUS AKUN
    // ========================
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            // LEPASKAN AKUN DARI GURU
            DB::table('guru')
                ->where('id_user', $id)
                ->update(['id_user' => null]);

            // LEPASKAN AKUN DARI WALI
            DB::table('wali')
                ->where('id_user', $id)
                ->update(['id_user' => null]);

            // HAPUS USER
            DB::table('users')
                ->where('id', $id)
                ->delete();
        });

        return redirect()
            ->route('kelola-akun.index')
            ->with('success', 'Akun pengguna berhasil dihapus!');
    }
}
