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
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// FORM LOGIN
Route::get('/login', function () {
    return view('login');
})->name('login');

// PROSES LOGIN
Route::post('/login', [AuthController::class, 'login']);

// ADMIN
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// GURU
Route::get('/guru/dashboard', function () {
    return view('guru.dashboard');
})->name('guru.dashboard');

// WALI
Route::get('/wali/dashboard', function () {
    return view('wali.dashboard');
})->name('wali.dashboard');

// KEPSEK
Route::get('/kepsek/dashboard', function () {
    return view('kepsek.dashboard');
})->name('kepsek.dashboard');

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
    Route::get('/tambah-siswa', [DataSiswaController::class, 'create'])->name('tambah-siswa');
    Route::post('/tambah-siswa', [DataSiswaController::class, 'store'])->name('store-siswa');
    Route::get('/siswa-kelas/{id}', [GuruKelasController::class, 'siswaKelas'])->name('siswa-kelas');
    Route::post('/siswa-kelas/{id}/update-kehadiran', [GuruKelasController::class, 'updateKehadiranKelas'])->name('update-kehadiran-kelas');
    Route::get('/data-siswa/{id}', [DataSiswaController::class, 'show'])->name('data-siswa.show');
    Route::get('/edit-data-siswa/{id}', [DataSiswaController::class, 'edit'])->name('edit-siswa');
    Route::put('/update-data-siswa/{id}', [DataSiswaController::class, 'update'])->name('update-siswa');
    Route::get('/detail-siswa/{id}', [DataSiswaController::class, 'show']) ->name('detail-siswa');

    // ========================
    // DATA WALI
    // ========================
    Route::get('/data-wali', [DataWaliController::class, 'index'])->name('data-wali');
    Route::get('/tambah-data-wali', [DataWaliController::class, 'create'])->name('wali.create');
    Route::post('/tambah-data-wali', [DataWaliController::class, 'store'])->name('wali.store');
    Route::get('/edit-data-wali/{id}', [DataWaliController::class, 'edit'])->name('edit-data-wali');
    Route::put('/update-data-wali/{id}', [DataWaliController::class, 'update'])->name('update-wali');
    Route::delete('/data-wali/{id}', [DataWaliController::class, 'destroy'])->name('hapus-wali');


    // ========================
    // DATA GURU & KELAS
    // ========================
    Route::get('/guru', [GuruKelasController::class, 'guru'])->name('guru');
    Route::get('/kelas', [GuruKelasController::class, 'kelas'])->name('kelas');
    Route::get('/detail-guru/{id}', [GuruKelasController::class, 'detailGuru'])->name('detail-guru');
    Route::get('/edit-data-guru/{id}', [GuruKelasController::class, 'editGuru'])->name('edit-data-guru');
    Route::put('/update-guru/{id}', [GuruKelasController::class, 'updateGuru'])->name('update-guru');
    Route::delete('/hapus-guru/{id}', [GuruKelasController::class, 'destroyGuru'])->name('hapus-guru');
    Route::view('/tambah-data-guru', 'admin.tambah-data-guru')->name('tambah-data-guru');
    Route::view('/tambah-data-kelas', 'admin.tambah-data-kelas')->name('tambah-data-kelas');
    Route::post('/simpan-guru', [GuruKelasController::class, 'storeGuru'])->name('store-guru');


    // ========================
    // DATA RELASI
    // ========================
    Route::get('/relasi', [RelasiController::class, 'index'])->name('relasi.index');
    Route::get('/relasi/create', [RelasiController::class, 'create'])->name('relasi.tambah');
    Route::post('/relasi', [RelasiController::class, 'store'])->name('relasi.store');
    Route::delete('/relasi/{id_siswa}/{id_wali}', [RelasiController::class, 'destroy'])->name('relasi.destroy');

 Route::get('/edit-relasi', function () {
    return view('admin.edit-relasi');
})->name('edit-relasi');


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

    Route::view('/admin/tambah-data-rfid', 'admin.tambah-data-rfid')
    ->name('tambah-data-rfid');

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
