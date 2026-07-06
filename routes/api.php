<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbsensiRfidController;
use App\Http\Controllers\Api\PenjemputanFingerprintController;
use App\Http\Controllers\Api\FingerprintEnrollController;


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