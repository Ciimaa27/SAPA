<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // 🔥 PAKAI USERNAME
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // 🔥 PAKAI id_role (karena DB kamu begitu)
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
                    return redirect()->route('login')->with('error', 'Role tidak dikenali');
            }
        }

        return back()->with('error', 'Username atau password salah');
    }
}