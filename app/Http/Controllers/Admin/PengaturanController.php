<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSistem::first();

        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $pengaturan = PengaturanSistem::first();

        $pengaturan->status_sistem = $request->status_sistem;

        $pengaturan->save();

        return redirect()->back()->with('success', 'Status sistem berhasil diperbarui.');
    }
}