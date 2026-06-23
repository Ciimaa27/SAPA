<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LupaPasswordController extends Controller
{
    public function verifikasi(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::where('username', $request->username)
                    ->where('email', $request->email)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Username atau email tidak sesuai.');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Simpan ke session
        session([
            'reset_otp' => $otp,
            'reset_user_id' => $user->id,
        ]);

        // Kirim email
        Mail::raw(
            "Kode OTP reset password SAPA Anda adalah: $otp\n\nKode berlaku selama 5 menit.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode OTP Reset Password SAPA');
            }
        );

        return redirect()->route('otp.form');
    }

    public function cekOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        if ($request->otp != session('reset_otp')) {
            return back()->with('error', 'Kode OTP salah.');
        }

        $user = User::find(session('reset_user_id'));

        return view('reset-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->user_id);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')
            ->with('success', 'Password berhasil diubah.');
    }
}
