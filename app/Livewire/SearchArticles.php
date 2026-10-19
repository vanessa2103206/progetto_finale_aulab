<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;

class SearchArticles extends Component
{
    public $search = '';

    public function render()
    {
        // Se la barra non è vuota, usa la vera ricerca Full-Text indicizzata di Scout!
        if ($this->search !== '') {
            $articles = Article::search($this->search)->get();
        } else {
            // Altrimenti prendi tutti gli articoli normalmente
            $articles = Article::orderBy('created_at', 'desc')->get();
        }

        return view('livewire.search-articles', compact('articles'));
    }
}
