<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'article_id'];

    /**
     * Relazione inversa: L'immagine appartiene a un articolo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
