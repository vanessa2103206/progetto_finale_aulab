<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;

// Homepage libera
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

// Rotta ufficiale protetta per inserire un nuovo articolo
Route::get('/articolo/nuovo', [PublicController::class, 'create'])->name('articles.create')->middleware('auth');
