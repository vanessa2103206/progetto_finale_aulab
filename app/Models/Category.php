<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relazione ufficiale: una categoria ha molti annunci
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
