<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SikataController;

Route::get('/sikata/topics', [SikataController::class, 'topics']);
Route::post('/sikata/start', [SikataController::class, 'start']);
Route::post('/sikata/identify', [SikataController::class, 'identify']);
Route::post('/sikata/corrections', [SikataController::class, 'corrections']);
Route::post('/sikata/correct', [SikataController::class, 'correct']);
