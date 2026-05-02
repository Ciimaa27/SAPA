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
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KepsekController;

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

Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
})->name('logout');

// Lupa Sandi
Route::get('/lupasandi', function () {
    return view('lupasandi');
})->name('lupasandi');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/data-absen', [DashboardController::class, 'dataAbsen']);

    // KELOLA AKUN
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola-akun.index');
    Route::get('/kelola-akun/create', [KelolaAkunController::class, 'create'])->name('kelola-akun.create');
    Route::post('/kelola-akun', [KelolaAkunController::class, 'store'])->name('kelola-akun.store');
    Route::get('/kelola-akun/{id}/edit', [KelolaAkunController::class, 'edit'])->name('kelola-akun.edit');
    Route::put('/kelola-akun/{id}', [KelolaAkunController::class, 'update'])->name('kelola-akun.update');
    Route::delete('/kelola-akun/{id}', [KelolaAkunController::class, 'destroy'])->name('kelola-akun.destroy');

    // DATA SISWA
    Route::get('/data-siswa', [DataSiswaController::class, 'index'])->name('data-siswa');
    Route::delete('/data-siswa/{id}', [DataSiswaController::class, 'destroy'])->name('hapus-siswa');
    Route::get('/tambah-siswa', [DataSiswaController::class, 'create'])->name('tambah-siswa');
    Route::post('/tambah-siswa', [DataSiswaController::class, 'store'])->name('store-siswa');
    Route::get('/siswa-kelas/{id}', [GuruKelasController::class, 'siswaKelas'])->name('siswa-kelas');
    Route::post('/siswa-kelas/{id}/update-kehadiran', [GuruKelasController::class, 'updateKehadiranKelas'])->name('update-kehadiran-kelas');
    Route::get('/data-siswa/{id}', [DataSiswaController::class, 'show'])->name('data-siswa.show');
    Route::get('/edit-data-siswa/{id}', [DataSiswaController::class, 'edit'])->name('edit-siswa');
    Route::put('/update-data-siswa/{id}', [DataSiswaController::class, 'update'])->name('update-siswa');
    Route::get('/detail-siswa/{id}', [DataSiswaController::class, 'show'])->name('detail-siswa');

    // DATA WALI
    Route::get('/data-wali', [DataWaliController::class, 'index'])->name('data-wali');
    Route::get('/tambah-data-wali', [DataWaliController::class, 'create'])->name('wali.create');
    Route::post('/tambah-data-wali', [DataWaliController::class, 'store'])->name('wali.store');
    Route::get('/edit-data-wali/{id}', [DataWaliController::class, 'edit'])->name('edit-data-wali');
    Route::put('/update-data-wali/{id}', [DataWaliController::class, 'update'])->name('update-wali');
    Route::delete('/data-wali/{id}', [DataWaliController::class, 'destroy'])->name('hapus-wali');

    // DATA GURU & KELAS
    Route::get('/guru', [GuruKelasController::class, 'guru'])->name('guru');
    Route::get('/kelas', [GuruKelasController::class, 'kelas'])->name('kelas');
    Route::get('/detail-guru/{id}', [GuruKelasController::class, 'detailGuru'])->name('detail-guru');
    Route::get('/edit-data-guru/{id}', [GuruKelasController::class, 'editGuru'])->name('edit-data-guru');
    Route::put('/update-guru/{id}', [GuruKelasController::class, 'updateGuru'])->name('update-guru');
    Route::delete('/hapus-guru/{id}', [GuruKelasController::class, 'destroyGuru'])->name('hapus-guru');
    Route::view('/tambah-data-guru', 'admin.tambah-data-guru')->name('tambah-data-guru');
    Route::get('/tambah-data-kelas', [GuruKelasController::class, 'createKelas'])->name('tambah-data-kelas');
    Route::post('/simpan-guru', [GuruKelasController::class, 'storeGuru'])->name('store-guru');

    // RELASI
    Route::get('/relasi', [RelasiController::class, 'index'])->name('relasi.index');
    Route::get('/relasi/create', [RelasiController::class, 'create'])->name('relasi.tambah');
    Route::post('/relasi', [RelasiController::class, 'store'])->name('relasi.store');
    Route::delete('/relasi/{id_siswa}/{id_wali}', [RelasiController::class, 'destroy'])->name('relasi.destroy');
    Route::get('/edit-relasi', function () {
        return view('admin.edit-relasi');
    })->name('edit-relasi');

    // IOT
    Route::get('/status-perangkat', [IoTController::class, 'statusPerangkat'])->name('status-perangkat');
    Route::get('/iot/{tab?}', [RFIDController::class, 'index'])->name('iot.index');
    Route::delete('/iot/{tab}/{id}', [RFIDController::class, 'destroy'])->name('iot.destroy');
    Route::view('/admin/tambah-data-rfid', 'admin.tambah-data-rfid')->name('tambah-data-rfid');

    // JADWAL & PENJEMPUTAN
    Route::get('/jadwal-pulang', [JadwalPulangController::class, 'index'])->name('jadwal-pulang');
    Route::get('/jadwal-pulang/edit', [JadwalPulangController::class, 'edit'])->name('jadwal-pulang.edit');
    Route::post('/jadwal-pulang/{kelas}/update', [JadwalPulangController::class, 'update'])->name('jadwal-pulang.update');
    Route::get('/data-penjemputan', [DataPenjemputanController::class, 'index'])->name('data-penjemputan');

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

});


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/

Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
Route::get('/guru/kehadiran', [GuruController::class, 'kehadiran'])->name('guru.kehadiran');
Route::get('/guru/detail-kehadiran/{id_kelas}', [GuruController::class, 'detailKehadiran'])->name('guru.detail-kehadiran');
Route::get('/guru/data-penjemputan', [GuruController::class, 'dataPenjemputan'])->name('guru.data-penjemputan');
Route::get('/guru/riwayat-penjemputan', [GuruController::class, 'riwayatPenjemputan'])->name('guru.riwayat');


/*
|--------------------------------------------------------------------------
| WALI
|--------------------------------------------------------------------------
*/

Route::get('/wali/dashboard', [App\Http\Controllers\wali\DashboardController::class, 'index'])->name('wali.dashboard');


/*
|--------------------------------------------------------------------------
| KEPSEK
|--------------------------------------------------------------------------
*/

Route::prefix('kepsek')->group(function () {

    Route::get('/dashboard', [KepsekController::class, 'dashboard'])->name('kepsek.dashboard');
    Route::get('/statistik', [KepsekController::class, 'statistik'])->name('kepsek.statistik');

    Route::get('/laporan', function () {
        return view('kepsek.laporan');
    })->name('kepsek.laporan');

    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('kepsek.guru.dashboard');
    Route::get('/guru/kehadiran', [GuruController::class, 'kehadiran'])->name('kepsek.guru.kehadiran');
    Route::get('/guru/detail-kehadiran/{id_kelas}', [GuruController::class, 'detailKehadiran'])->name('kepsek.guru.detail-kehadiran');
    Route::get('/guru/data-penjemputan', [GuruController::class, 'dataPenjemputan'])->name('kepsek.guru.data-penjemputan');

    Route::get('/guru/riwayat-penjemputan', [GuruController::class, 'riwayatPenjemputan'])->name('guru.riwayat');

});
