<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KelolaAkunController;
use App\Http\Controllers\Admin\DataSiswaController;
use App\Http\Controllers\Admin\DataWaliController;
use App\Http\Controllers\Admin\GuruKelasController;
use App\Http\Controllers\Admin\RelasiController;
use App\Http\Controllers\Admin\RFIDController;
use App\Http\Controllers\Admin\IoTController;
use App\Http\Controllers\Admin\DataPenjemputanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\JadwalPulangController;

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
    return redirect()->route('admin.dashboard');
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

    // ========================
    // DASHBOARD
    // ========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // ========================
    // KELOLA AKUN
    // ========================
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola-akun.index');
    Route::get('/kelola-akun/create', [KelolaAkunController::class, 'create'])->name('kelola-akun.create');
    Route::post('/kelola-akun', [KelolaAkunController::class, 'store'])->name('kelola-akun.store');
    Route::get('/kelola-akun/{id}/edit', [KelolaAkunController::class, 'edit'])->name('kelola-akun.edit');
    Route::put('/kelola-akun/{id}', [KelolaAkunController::class, 'update'])->name('kelola-akun.update');
    Route::delete('/kelola-akun/{id}', [KelolaAkunController::class, 'destroy'])->name('kelola-akun.destroy');

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
    // DATA WALI
    // ========================
    Route::get('/data-wali', [DataWaliController::class, 'index'])->name('data-wali');
    Route::delete('/data-wali/{id}', [DataWaliController::class, 'destroy'])->name('hapus-wali');

    // ========================
    // DATA GURU & KELAS
    // ========================
    Route::get('/guru', [GuruKelasController::class, 'guru'])->name('guru');
    Route::get('/kelas', [GuruKelasController::class, 'kelas'])->name('kelas');

    // ========================
    // DATA RELASI
    // ========================
    Route::get('/relasi', [RelasiController::class, 'index'])->name('relasi.index');
    Route::get('/relasi/create', [RelasiController::class, 'create'])->name('relasi.create');
    Route::post('/relasi', [RelasiController::class, 'store'])->name('relasi.store');
    Route::delete('/relasi/{id_siswa}/{id_wali}', [RelasiController::class, 'destroy'])->name('relasi.destroy');

    // ========================
    // PERANGKAT (IOT)
    // ========================
    Route::get('/status-perangkat', [IoTController::class, 'statusPerangkat'])
        ->name('status-perangkat');

    // RFID
    Route::get('/iot/{tab?}', [RFIDController::class, 'index'])
        ->name('iot.index');

    Route::delete('/iot/{tab}/{id}', [RFIDController::class, 'destroy'])
        ->name('iot.destroy');

    // ========================
    // JADWAL & PENJEMPUTAN
    // ========================
    Route::get('/jadwal-pulang', [JadwalPulangController::class, 'index'])
        ->name('jadwal-pulang');
    Route::get('/jadwal-pulang/edit', [JadwalPulangController::class, 'edit'])
        ->name('jadwal-pulang.edit');
    Route::post('/jadwal-pulang/{kelas}/update', [JadwalPulangController::class, 'update'])
        ->name('jadwal-pulang.update');

    // DATA PENJEMPUTAN
    Route::get('/data-penjemputan', [DataPenjemputanController::class, 'index'])->name('data-penjemputan');

  // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

});

    



/*
|--------------------------------------------------------------------------
| Orang Tua Routes
|--------------------------------------------------------------------------
*/

Route::prefix('orangtua')->group(function () {

    Route::get('/dashboard', function () {
        return view('orangtua.dashboard');
    })->name('dashboard-orangtua');

    Route::get('/profil-anak', function () {
        return view('orangtua.profil-anak');
    })->name('profil-anak');

    Route::get('/kehadiran-anak', function () {
        return view('orangtua.kehadiran-anak');
    })->name('kehadiran-anak');

    Route::get('/laporan', function () {
        return view('orangtua.laporan');
    })->name('laporan');

});
