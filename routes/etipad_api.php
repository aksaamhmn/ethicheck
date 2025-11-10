<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtipadController;

Route::get('/etipad/docs', [EtipadController::class, 'index']);
Route::get('/etipad/docs/{slug}', [EtipadController::class, 'show']);
