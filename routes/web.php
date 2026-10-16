<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;

// Homepage del sito
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

// Pagina pubblica per vedere tutti gli annunci
Route::get('/articoli', [ArticleController::class, 'index'])->name('articles.index');

// ROTTA DI DETTAGLIO MANCANTE USER STORY 2 (Ecco il pezzo che mancava!)
Route::get('/articolo/dettaglio/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Pagina protetta per inserire un nuovo articolo
Route::get('/articolo/nuovo', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
