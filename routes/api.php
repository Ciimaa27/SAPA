<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbsensiRfidController;
use App\Http\Controllers\Api\PenjemputanFingerprintController;
use App\Http\Controllers\Api\FingerprintEnrollController;
use App\Http\Controllers\Api\ResetDataController;


// ABSENSI RFID
Route::post('/absensi/rfid', [
    AbsensiRfidController::class,
    'store'
]);


// PENJEMPUTAN FINGERPRINT
Route::post('/penjemputan/fingerprint', [
    PenjemputanFingerprintController::class,
    'store'
]);


// HASIL ENROLL FINGERPRINT
Route::post('/fingerprint/enroll-result', [
    FingerprintEnrollController::class,
    'store'
]);

Route::post('/reset/fingerprint', [ResetDataController::class, 'resetFingerprint']);
Route::post('/reset/siswa', [ResetDataController::class, 'resetSiswa']);
