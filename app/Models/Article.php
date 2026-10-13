<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    // Campi riempibili obbligatori per l'annuncio
    protected $fillable = ['title', 'price', 'description', 'category_id', 'user_id'];

    /**
     * Relazione ufficiale: l'annuncio appartiene a una categoria
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relazione ufficiale: l'annuncio appartiene a un utente
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
