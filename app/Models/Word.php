<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = ['word'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_word')
            ->withPivot('count'); // Получаем количество вхождений
    }
}
