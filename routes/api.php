<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbsensiRfidController;
use App\Http\Controllers\Api\PenjemputanFingerprintController;

Route::post('/absensi/rfid', [
    AbsensiRfidController::class,
    'store'
]);

Route::post('/penjemputan/fingerprint', [
    PenjemputanFingerprintController::class,
    'store'
]);