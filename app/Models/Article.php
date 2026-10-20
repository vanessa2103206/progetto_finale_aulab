<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable; // Motore di ricerca di Scout (User Story 10)

class Article extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'description', 'price', 'category_id', 'user_id', 'is_accepted'];

    /**
     * Relazione: Un articolo può avere molte immagini (User Story 5!)
     */
    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }

    /**
     * Relazione: L'annuncio appartiene a una categoria
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relazione: L'annuncio appartiene a un utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Campi indicizzati per la ricerca globale Full-Text (User Story 10)
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
