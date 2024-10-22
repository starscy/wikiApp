<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'url', 'text', 'words', 'size', 'words_count'];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'article_word')
            ->withPivot('count'); // Получаем количество вхождений
    }
}
