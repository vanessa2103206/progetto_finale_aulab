<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Mail; // Importante per l'invio postale
use App\Mail\BecomeRevisoreMail;     // Importiamo la mail appena creata
use Illuminate\Support\Facades\Auth;

class RevisoreController extends Controller
{
    /**
     * Mostra la dashboard del revisore
     */
    public function index()
    {
        $article_to_check = Article::where('is_accepted', null)->oldest()->first();
        return view('revisore.index', compact('article_to_check'));
    }

    /**
     * Bottone Accetta
     */
    public function acceptArticle(Article $article)
    {
        $article->is_accepted = true;
        $article->save();
        return redirect()->route('revisore.index')->with('successMessage', "L'annuncio '{$article->title}' è stato approvato con successo!");
    }

    /**
     * Bottone Rifiuta
     */
    public function rejectArticle(Article $article)
    {
        $article->is_accepted = false;
        $article->save();
        return redirect()->route('revisore.index')->with('successMessage', "L'annuncio '{$article->title}' è stato rifiutato con successo.");
    }

    /**
     * Mostra la pagina visiva "Lavora con noi" (Form di candidatura)
     */
    public function becomeRevisoreForm()
    {
        return view('revisore.become');
    }

    /**
     * Invia la mail di richiesta collaborazione all'amministratore
     */
    public function becomeRevisoreSubmit()
    {
        $user = Auth::user();

        // Facciamo partire la mail indirizzandola all'admin del sito (es. admin@presto.it)
        Mail::to('admin@presto.it')->send(new BecomeRevisoreMail($user));

        return redirect()->route('homepage')->with('successMessage', "Complimenti! La tua richiesta è stata inviata con successo. L'amministratore la riceverà a breve.");
    }
}
