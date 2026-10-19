<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeUserRevisore extends Command
{
    /**
     * Il nome del comando che scriveremo nel terminale
     */
    protected $signature = 'presto:make-user-revisore {email}';

    /**
     * La descrizione che compare nella lista dei comandi di Artisan
     */
    protected $description = 'Rende un utente registrato un revisore del sito tramite la sua email';

    /**
     * La logica che viene eseguita quando lanciamo il comando
     */
    public function handle()
    {
        // Recuperiamo l'email inserita dall'utente nel terminale
        $email = $this->argument('email');
        
        // Cerchiamo l'utente nel database
        $user = User::where('email', $email)->first();

        // Se l'utente non esiste, mostriamo un errore nel terminale
        if (!$user) {
            $this->error("Errore: Non esiste alcun utente registrato con l'email: {$email}");
            return;
        }

        // Altrimenti modifichiamo il flag sul database portandolo a true (1)
        $user->is_revisore = true;
        $user->save();

        // Mostriamo un bellissimo messaggio di successo nel terminale
        $this->info("Grandioso! L'utente {$user->name} ({$email}) è ora ufficialmente un Revisore di Presto.it!");
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class RevisoreController extends Controller
{
    /**
     * Mostra la dashboard del revisore con un annuncio alla volta (dal meno recente)
     */
    public function index()
    {
        // Prendiamo il primo articolo che ha ancora is_accepted impostato su null, partendo dal meno recente (oldest)
        $article_to_check = Article::where('is_accepted', null)->oldest()->first();
        
        return view('revisore.index', compact('article_to_check'));
    }

    /**
     * Bottone Accetta: Imposta l'articolo come approvato (true)
     */
    public function acceptArticle(Article $article)
    {
        $article->is_accepted = true;
        $article->save();

        return redirect()->route('revisore.index')->with('successMessage', "L'annuncio '{$article->title}' è stato approvato con successo!");
    }

    /**
     * Bottone Rifiuta: Imposta l'articolo l'articolo come scartato (false)
     */
    public function rejectArticle(Article $article)
    {
        $article->is_accepted = false;
        $article->save();

        return redirect()->route('revisore.index')->with('successMessage', "L'annuncio '{$article->title}' è stato rifiutato con successo.");
    }
}
