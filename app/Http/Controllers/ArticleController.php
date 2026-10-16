<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Mostra la pagina con tutti gli annunci pubblicati
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('articles.index', compact('articles'));
    }

    /**
     * Mostra il dettaglio di un singolo annuncio
     */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /**
     * Mostra la pagina ufficiale per creare un nuovo annuncio
     */
    public function create()
    {
        return view('articles.create');
    }
}
