<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class PublicController extends Controller
{
    public function homepage()
    {
        // CORREZIONE PROFESSORE: Mostriamo in Home solo i 3 articoli più recenti già accettati
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->take(3)->get();
        
        return view('welcome', compact('articles'));
    }
}
