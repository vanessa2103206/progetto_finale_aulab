<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage()
    {
        return view('welcome');
    }

    // Funzione ufficiale Aulab per la pagina di inserimento dell'annuncio
    public function create()
    {
        return view('articles.create');
    }
}
