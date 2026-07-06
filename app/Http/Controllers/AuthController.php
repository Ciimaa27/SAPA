<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(
            [
                'username' => ['required'],
                'password' => ['required'],
            ],
            [
                'username.required' => 'Nama pengguna wajib diisi.',
                'password.required' => 'Kata sandi wajib diisi.',
            ]
        );

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            switch ($user->id_role) {
                case 1:
                    return redirect()->route('admin.dashboard');
                case 2:
                    return redirect()->route('guru.dashboard');
                case 3:
                    return redirect()->route('kepsek.dashboard');
                case 4:
                    return redirect()->route('wali.dashboard');
                default:
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()
                        ->route('login')
                        ->with('error', 'Role pengguna tidak dikenali.');
            }
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Nama pengguna atau kata sandi salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
