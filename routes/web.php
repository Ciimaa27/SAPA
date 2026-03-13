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
