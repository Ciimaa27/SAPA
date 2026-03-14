<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

// Route Halaman Login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Route Halaman Lupa Sandi
Route::get('/lupasandi', function () {
    return view('lupasandi');
})->name('lupasandi');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('/kelola-akun', function () {
    return view('admin.kelola-akun');
})->name('kelola-akun');

Route::get('/tambah-akun', function () {
    return view('admin.tambah-akun');
})->name('tambah-akun');

Route::get('/data-siswa', function () {
    return view('admin.data-siswa');
})->name('data-siswa');

Route::get('/data-wali', function () {
    return view('admin.data-wali');
})->name('data-wali');

Route::get('/guru', function () {
    return view('admin.guru');
})->name('guru');

Route::get('/kelas', function () {
    return view('admin.kelas');
})->name('kelas');

Route::get('/relasi', function () {
    return view('admin.relasi');
})->name('relasi');

Route::get('/rfid', function () {
    return view('admin.rfid');
})->name('rfid');

Route::get('/sidik-jari', function () {
    return view('admin.sidik-jari');
})->name('sidik-jari');

Route::get('/data-penjemputan', function () {
    return view('admin.data-penjemputan');
})->name('data-penjemputan');

Route::get('/status-perangkat', function () {
    return view('admin.status-perangkat');
})->name('status-perangkat');

Route::get('/tambah-siswa', function () {
    return view('admin.tambah-siswa');
})->name('tambah-siswa');

Route::get('/siswa-kelas', function () {
    return view('admin.siswa-kelas');
})->name('siswa-kelas');
