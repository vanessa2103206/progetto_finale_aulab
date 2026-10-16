<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController; // Importazione del nuovo controller

// Homepage libera
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

// Rotta modificata secondo le indicazioni del docente Aulab
Route::get('/articolo/nuovo', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
