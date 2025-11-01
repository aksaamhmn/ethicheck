<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EthicheckController; // <-- Pastikan ini ada

// Halaman utama
// BENAR: Gunakan 'Route::get' untuk memanggil method 'show' di controller Anda
Route::get('/', [EthicheckController::class, 'show'])->name('ethicheck');

// Rute lain
Route::view('/sikata', 'sikata')->name('sikata');
Route::view('/etipad', 'etipad')->name('etipad');

// Rute untuk AI (Kode Anda di sini sudah benar)
Route::post('/analyze', [EthicheckController::class, 'analyze'])->name('ethicheck.analyze');
