<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RevisoreController;

/*
|--------------------------------------------------------------------------
| Web Routes - Presto.it
|--------------------------------------------------------------------------
*/

// Homepage del sito
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

// Pagina pubblica per vedere tutti gli annunci
Route::get('/articoli', [ArticleController::class, 'index'])->name('articles.index');

// Pagina di dettaglio del singolo articolo (User Story 2)
Route::get('/articolo/dettaglio/{article}', [ArticleController::class, 'show'])->name('articles.show');

// Pagina protetta per inserire un nuovo articolo (solo utenti loggati)
Route::get('/articolo/nuovo', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');

// ROTTE PUBBLICHE PER DIVENTARE REVISORE (User Story 3 - Solo utenti loggati possono candidarsi)
Route::middleware('auth')->group(function () {
    Route::get('/lavora-con-noi', [RevisoreController::class, 'becomeRevisoreForm'])->name('revisore.become_form');
    Route::post('/lavora-con-noi/invia', [RevisoreController::class, 'becomeRevisoreSubmit'])->name('revisore.become_submit');
});

// GRUPPO ROTTE REVISORE (User Story 3 - Solo chi è già revisore autorizzato)
Route::middleware(['auth', 'isRevisore'])->group(function () {
    Route::get('/revisore', [RevisoreController::class, 'index'])->name('revisore.index');
    Route::patch('/revisore/accetta/{article}', [RevisoreController::class, 'acceptArticle'])->name('revisore.accept_article');
    Route::patch('/revisore/rifiuta/{article}', [RevisoreController::class, 'rejectArticle'])->name('revisore.reject_article');
});

// NUOVA ROTTA USER STORY 4: Cambiamento della lingua del sito tramite il clic sulle bandierine
Route::get('/lingua/{locale}', function ($locale) {
    if (in_array($locale, ['it', 'en', 'es'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('set_locale');
