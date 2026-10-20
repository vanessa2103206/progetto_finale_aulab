<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Mostra la lista di tutti gli articoli approvati
     */
    public function index()
    {
        // CORREZIONE PROFESSORE: Filtriamo per mostrare solo gli articoli accettati dal revisore
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Mostra la pagina di creazione di un nuovo articolo
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Mostra il dettaglio di un singolo articolo
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }
}
