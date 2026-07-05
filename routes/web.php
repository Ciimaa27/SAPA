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
use App\Http\Controllers\Admin\ArsipSiswaController;
use App\Http\Controllers\Admin\JadwalPulangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\KepsekController;
use App\Models\Wali;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\LupaPasswordController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;


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
Route::get('/lupa-password', function () {
    return view('lupasandi');
})->name('password.request');

Route::post('/lupa-password', [LupaPasswordController::class, 'verifikasi'])
    ->name('password.verifikasi');

Route::post('/reset-password', [LupaPasswordController::class, 'updatePassword'])
    ->name('password.update');
Route::get('/cek-fonnte', function () {

    $token = env('FONNTE_TOKEN');

    return response()->json([
        'token' => $token,
        'length' => strlen($token),
    ]);
});

Route::get('/verifikasi-otp', function () {
    return view('verifikasi-otp');
})->name('otp.form');

Route::post('/verifikasi-otp', [LupaPasswordController::class, 'cekOtp'])
    ->name('otp.cek');

Route::get('/test-email', function () {

    Mail::raw('Halo, ini adalah email percobaan dari Sistem SAPA.', function ($message)  {
        $message->to('noormaulida11062005@gmail.com')
                ->subject('Test Email SAPA');
    });

    return 'Email terkirim';
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth'])->group(function () {

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
    Route::post('/kenaikan-kelas', [DataSiswaController::class, 'kenaikanKelas'])->name('kenaikan-kelas');
    Route::post('/arsip-siswa/{id}', [DataSiswaController::class, 'arsipkan'])->name('arsip-siswa.store');

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
    Route::post('/simpan-kelas', [GuruKelasController::class, 'storeKelas'])->name('store-kelas');
    Route::post('/simpan-guru', [GuruKelasController::class, 'storeGuru'])->name('store-guru');

    // RELASI
    Route::get('/relasi', [RelasiController::class, 'index'])->name('relasi.index');
    Route::get('/relasi/create', [RelasiController::class, 'create'])->name('relasi.tambah');
    Route::post('/relasi', [RelasiController::class, 'store'])->name('relasi.store');
    Route::delete('/relasi/{id_siswa}/{id_wali}', [RelasiController::class, 'destroy'])->name('relasi.destroy');
    Route::get('/relasi/{id_siswa}/{id_wali}/edit', [RelasiController::class, 'edit'])->name('relasi.edit');
    Route::put('/relasi/{id_siswa}/{id_wali}', [RelasiController::class, 'update'])->name('relasi.update');

    // IOT
    Route::get('/status-perangkat', [IoTController::class, 'statusPerangkat'])->name('status-perangkat');
    Route::get('/iot/{tab?}', [RFIDController::class, 'index'])->name('iot.index');
    Route::delete('/iot/{tab}/{id}', [RFIDController::class, 'destroy'])->name('iot.destroy');
    Route::view('/tambah-data-rfid', 'admin.tambah-data-rfid')->name('tambah-data-rfid');
    Route::get('/latest-rfid', [RFIDController::class, 'latestRFID']);
    Route::get('/latest-fingerprint', [RFIDController::class, 'latestFingerprint']);

    // JADWAL & PENJEMPUTAN
    Route::get('/jadwal-pulang', [JadwalPulangController::class, 'index'])->name('jadwal-pulang');
    Route::get('/jadwal-pulang/edit', [JadwalPulangController::class, 'edit'])->name('jadwal-pulang.edit');
    Route::post('/jadwal-pulang/{kelas}/update', [JadwalPulangController::class, 'update'])->name('jadwal-pulang.update');
    Route::get('/admin/jadwal-pulang/edit',[JadwalPulangController::class, 'edit'])->name('jadwal-pulang.edit');
    Route::post('/admin/jadwal-pulang/update-satu',[JadwalPulangController::class, 'updateSatu'])->name('jadwal-pulang.update-satu');
    Route::get('/data-penjemputan', [DataPenjemputanController::class, 'index'])->name('data-penjemputan');
    Route::get('/data-penjemputan/status/{id_kelas}', [DataPenjemputanController::class, 'status'])->name('data-penjemputan.status');
    Route::post('/data-penjemputan/update-status', [DataPenjemputanController::class, 'updateStatus'])->name('data-penjemputan.update-status');

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/kehadiran/{id_siswa}', [LaporanController::class, 'downloadKehadiran'])->name('laporan.kehadiran.download');
    Route::get('/laporan/penjemputan/{id}', [LaporanController::class, 'downloadPenjemputan'])->name('laporan.penjemputan.download');
    Route::get('/laporan/kehadiran/export/{id_kelas}',[LaporanController::class, 'exportKehadiran'])->name('laporan.kehadiran.export');
    Route::get('/laporan/penjemputan/export/{id_kelas}',[LaporanController::class, 'exportPenjemputan'])->name('laporan.penjemputan.export');

   // Arsip Siswa
   Route::get('/arsip-siswa', [ArsipSiswaController::class, 'index'])->name('arsip-siswa');
   Route::get('/admin/arsip-siswa/{tahun}/{status}/export',[ArsipSiswaController::class, 'exportByYear'])->name('arsip-siswa.export');
   Route::get('/arsip-siswa/{tahun}/{status}', [ArsipSiswaController::class, 'showByYear'])->name('arsip-siswa.tahun');
});


/*
|--------------------------------------------------------------------------
| GURU
|--------------------------------------------------------------------------
*/

Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
Route::get('/guru/kehadiran', [GuruController::class, 'kehadiran'])->name('guru.kehadiran');
Route::get('/guru/detail-kehadiran/{id_kelas}', [GuruController::class, 'detailKehadiran'])->name('guru.detail-kehadiran');
Route::post('/guru/detail-kehadiran/{id_kelas}', [GuruController::class, 'updateDetailKehadiran'])->name('guru.detail-kehadiran.save');
Route::get('/guru/data-penjemputan', [GuruController::class, 'dataPenjemputan'])->name('guru.data-penjemputan');
Route::get('/guru/penjemputan', [GuruController::class, 'penjemputan'])->name('guru.penjemputan.index');
Route::get('/guru/riwayat-penjemputan', [GuruController::class, 'riwayatPenjemputan'])->name('guru.riwayat');
Route::post('/guru/penjemputan/update-status', [GuruController::class, 'updateStatusPenjemputan'])->name('guru.penjemputan.update-status');
Route::get('/guru/penjemputan/{id_kelas}', [GuruController::class, 'daftarPenjemputan'])->name('guru.penjemputan');
Route::get('/guru/riwayat-penjemputan/{id}', [GuruController::class, 'showDetail'])->name('guru.detail-penjemputan');

/*
|--------------------------------------------------------------------------
| WALI
|--------------------------------------------------------------------------
*/

Route::get('/wali/dashboard', [App\Http\Controllers\wali\DashboardController::class, 'index'])->name('wali.dashboard');
Route::get('/wali/kehadiran', [App\Http\Controllers\wali\DashboardController::class, 'kehadiran'])->name('wali.kehadiran');
Route::get('/wali/status-penjemputan', [App\Http\Controllers\wali\DashboardController::class, 'statusPenjemputan'])->name('wali.status-penjemputan');
Route::get('/wali/notifikasi', [App\Http\Controllers\wali\DashboardController::class, 'notifikasi'])->name('wali.notifikasi');
Route::get('/wali/jadwal-pulang', [App\Http\Controllers\wali\DashboardController::class, 'jadwalPulang'])->name('wali.jadwal-pulang');
Route::get('/wali/laporan', [App\Http\Controllers\Wali\DashboardController::class, 'laporan'])
    ->name('wali.laporan');

Route::get('/wali/laporan/kehadiran/{bulan}/{tahun}',
    [App\Http\Controllers\Wali\DashboardController::class, 'downloadLaporan'])
    ->name('wali.laporan.download');

Route::get('/wali/laporan/export-pdf/{bulan}/{tahun}',
    [WaliDashboardController::class, 'exportPdf'])
    ->name('wali.export.pdf');

Route::get('/wali/export-excel/{bulan}/{tahun}',
    [WaliDashboardController::class, 'exportExcel'])
    ->name('wali.export.excel');

Route::get(
    '/wali/laporan/export-pdf-penjemputan/{bulan}/{tahun}',
    [App\Http\Controllers\Wali\DashboardController::class, 'exportPdfPenjemputan']
)->name('wali.export.pdf.penjemputan');

Route::get(
    '/wali/laporan/export-excel-penjemputan/{bulan}/{tahun}',
    [App\Http\Controllers\Wali\DashboardController::class, 'exportExcelPenjemputan']
)->name('wali.export.excel.penjemputan');


/*
|--------------------------------------------------------------------------
| KEPSEK
|--------------------------------------------------------------------------
*/

Route::prefix('kepsek')->group(function () {

    Route::get('/dashboard', [KepsekController::class, 'dashboard'])->name('kepsek.dashboard');
    Route::get('/statistik', [KepsekController::class, 'statistik'])->name('kepsek.statistik');

    // 1. Route ini HARUS ADA untuk menampilkan halaman laporan.blade.php
    Route::get('/laporan', function () {
        return view('kepsek.laporan');
    })->name('kepsek.laporan');

    // 2. Route proses download Excel (Sudah benar di sini jabatannya)
    Route::get('/laporan/download/{bulan}', [KepsekController::class, 'unduhLaporanKepsekGlobal'])->name('kepsek.laporan.download');

    // Route Dashboard Guru & Fitur-fiturnya
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('kepsek.guru.dashboard');
    Route::get('/guru/kehadiran', [GuruController::class, 'kehadiran'])->name('kepsek.guru.kehadiran');
    Route::get('/guru/detail-kehadiran/{id_kelas}', [GuruController::class, 'detailKehadiran'])->name('kepsek.guru.detail-kehadiran');
    Route::get('/guru/data-penjemputan', [GuruController::class, 'dataPenjemputan'])->name('kepsek.guru.data-penjemputan');

    Route::get('/guru/riwayat-penjemputan', [GuruController::class, 'riwayatPenjemputan'])->name('kepsek.guru.riwayat-penjemputan');
});