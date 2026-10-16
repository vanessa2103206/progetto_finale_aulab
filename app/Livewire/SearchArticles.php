<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;

class SearchArticles extends Component
{
    // Proprietà pubblica legata al campo di testo
    public $search = '';

    public function render()
    {
        // Se la barra non è vuota, filtra gli articoli per titolo o descrizione
        if ($this->search !== '') {
            $articles = Article::where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Altrimenti prendi tutti gli articoli normalmente
            $articles = Article::orderBy('created_at', 'desc')->get();
        }

        return view('livewire.search-articles', compact('articles'));
    }
}
