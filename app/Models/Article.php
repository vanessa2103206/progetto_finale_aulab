<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable; // Importiamo il motore di ricerca di Scout

class Article extends Model
{
    use HasFactory, Searchable; // Attiviamo la ricerca su questo modello

    protected $fillable = ['title', 'description', 'price', 'category_id', 'user_id', 'is_accepted'];

    /**
     * Relazione con la categoria dell'annuncio
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relazione con l'utente che ha creato l'annuncio
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scegliamo quali campi indicizzare per la ricerca Full-Text (Richiesto da US10!)
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category ? $this->category->name : '',
        ];
    }
}
