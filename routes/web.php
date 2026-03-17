<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KelolaAkunController;
use App\Http\Controllers\Admin\DataSiswaController;
use App\Http\Controllers\Admin\DataWaliController;
use App\Http\Controllers\Admin\GuruKelasController;
use App\Http\Controllers\RelasiController;
use App\Http\Controllers\Admin\IoTController;
use App\Http\Controllers\Admin\DataPenjemputanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('dashboard');
});

// Lupa Sandi
Route::get('/lupasandi', function () {
    return view('lupasandi');
})->name('lupasandi');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ========================
    // KELOLA AKUN
    // ========================
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola-akun');
    Route::delete('/kelola-akun/{id}', [KelolaAkunController::class, 'destroy'])->name('hapus-akun');

    Route::get('/tambah-akun', function () {
        return view('admin.tambah-akun');
    })->name('tambah-akun');

    // ========================
    // DATA SISWA
    // ========================
    Route::get('/data-siswa', [DataSiswaController::class, 'index'])->name('data-siswa');
    Route::delete('/data-siswa/{id}', [DataSiswaController::class, 'destroy'])->name('hapus-siswa');

    Route::get('/tambah-siswa', function () {
        return view('admin.tambah-siswa');
    })->name('tambah-siswa');

    Route::get('/siswa-kelas', function () {
        return view('admin.siswa-kelas');
    })->name('siswa-kelas');

    // ========================
    // DATA Wali
    // ========================
    Route::get('/data-wali', [DataWaliController::class, 'index'])->name('data-wali');
    Route::delete('/data-wali/{id}', [DataWaliController::class, 'destroy'])->name('hapus-wali');

    // ========================
    // DATA Guru & Kelas
    // ========================
    Route::get('/guru', [GuruKelasController::class, 'guru'])->name('guru');
    Route::get('/kelas', [GuruKelasController::class, 'kelas'])->name('kelas');

    // ========================
    // DATA Relasi
    // ========================
    Route::get('/admin/relasi', [RelasiController::class, 'index'])->name('relasi.index');
    Route::get('/admin/relasi/create', [RelasiController::class, 'create'])->name('relasi.create');
    Route::post('/admin/relasi', [RelasiController::class, 'store'])->name('relasi.store');
    Route::delete('/admin/relasi/{id_siswa}/{id_wali}', [RelasiController::class, 'destroy'])->name('relasi.destroy');

    // ========================
    // PERANGKAT
    // ========================

    // Halaman RFID / Sidik-jari
    Route::get('/iot/{tab?}', [IoTController::class, 'index'])->name('iot.index');

    // Tambah UID
    Route::post('/iot/{tab}', [IoTController::class, 'store'])->name('iot.store');

    // Hapus UID
    Route::delete('/iot/{tab}/{id}', [IoTController::class, 'destroy'])->name('iot.destroy');

    // Status perangkat
    Route::get('/iot/status-perangkat', [IoTController::class, 'statusPerangkat'])->name('status-perangkat');

    // ========================
    // PENJEMPUTAN
    // ========================
   Route::get('/data-penjemputan', [DataPenjemputanController::class, 'index'])->name('data-penjemputan');
});