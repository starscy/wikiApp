<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Модель статьи
 *
 */
class Article extends Model
{
    protected $fillable = ['title', 'url', 'text', 'words', 'size', 'words_count'];


    /**
     * Метод свзяь многие ко многим между Article и Word,
     * также использует вспомогательную таблицу 'article_word', чтобы отслеживать количество вхождений
     *
     * @return BelongsToMany
     */
    public function words(): BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'article_word')
            ->withPivot('count'); // Получаем количество вхождений
    }
}
