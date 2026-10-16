<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article; // Importiamo il modello degli articoli

class PublicController extends Controller
{
    public function homepage()
    {
        // Prendiamo gli ultimi 3 articoli inseriti, ordinati dal più recente
        $articles = Article::orderBy('created_at', 'desc')->take(3)->get();
        
        return view('welcome', compact('articles'));
    }
}
