<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;

    // Abilitiamo il riempimento di tutti i campi di Google Vision richiesti dalla guida
    protected $fillable = ['path', 'article_id', 'labels', 'adult', 'spoof', 'racy', 'medical', 'violence'];

    /**
     * CASTING DELLE LABELS IN ARRAY SECONDO LA PAGINA 117 DEL PDF DEL PROFESSORE
     */
    protected function casts(): array
    {
        return [
            'labels' => 'array',
        ];
    }

    /**
     * Relazione inversa: L'immagine appartiene a un articolo
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
