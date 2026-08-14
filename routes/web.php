<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MobilController;

// Home
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Halaman pencarian
Route::get('/cari', [MobilController::class, 'index'])
    ->name('cari');

// Proses rekomendasi
Route::post('/rekomendasi', [MobilController::class, 'rekomendasi'])
    ->name('rekomendasi');

// Redirect jika akses GET /rekomendasi
Route::get('/rekomendasi', function () {

    return redirect()
        ->route('cari')
        ->with(
            'warning',
            'Silakan isi kriteria pencarian terlebih dahulu.'
        );
});
