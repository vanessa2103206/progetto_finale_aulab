<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Mostra la pagina ufficiale per creare un nuovo annuncio
     */
    public function create()
    {
        return view('articles.create');
    }
}
