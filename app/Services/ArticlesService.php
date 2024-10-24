<?php

declare(strict_types=1);

namespace  App\Services;

use App\Models\Article;

/**
 * Сервис по работе со статьями
 */
class ArticlesService
{
    /**
     * Поиск статей по словам-атомам
     *
     * @param string $keyword
     * @return \Illuminate\Support\Collection
     */
    public function searchArticlesByKeyword(string $keyword)
    {
        $articles = Article::whereHas('words', function ($query) use ($keyword) {
            $query->where('word', $keyword);
        })->with(['words' => function ($query) use ($keyword) {
            $query->where('word', $keyword);
        }])->get();

        // Формируем результат с количеством вхождений
        return $articles->map(function ($article) use ($keyword) {
            $wordEntry = $article->words()->firstWhere('word', $keyword);
            $count = $wordEntry ? $wordEntry->pivot->count : 0;

            return [
                'id' => $article->id,
                'title' => $article->title,
                'url' => $article->url,
                'text' => $article->text,
                'word_count' => $count,
            ];
        })->sortByDesc('word_count');
    }
}


