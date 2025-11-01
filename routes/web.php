<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'ethicheck')->name('ethicheck');
Route::view('/sikata', 'sikata')->name('sikata');
Route::view('/etipad', 'etipad')->name('etipad');
