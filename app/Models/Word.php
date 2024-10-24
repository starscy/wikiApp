<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Модель Слова
 *
 */
class Word extends Model
{
    protected $fillable = ['word'];

    /**
     * Метод свзяь многие ко многим между Article и Word,
     * также использует вспомогательную таблицу 'article_word', чтобы отслеживать количество вхождений
     *
     * @return BelongsToMany
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_word')
            ->withPivot('count');
    }
}
