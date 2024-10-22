<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Класс для управления статьями
 */
class ArticleController extends Controller
{
    /**
     * Получаем списки статей
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = Article::all();

        return response()->json($articles, 200);
    }

    /**
     * Получаем статью по id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->query('id');
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article, 200);
    }

    /**
     * Запись в БД
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:500',
            'url' => 'required|string|max:500',
            'text' => 'required|string',
            'words' => 'required|array',
            'size' => 'required|numeric',
            'words_count' => 'required|integer',
        ]);

        // Создаем статью
        $article = Article::create([
            'title' => $request->title,
            'url' => $request->url,
            'text' => $request->text,
            'words' => json_encode($request->words),
            'size' => $request->size,
            'words_count' => $request->words_count,
        ]);

        $text = "Привет, как дела? Как ты?";
        $word = "как";
        $textLower = mb_strtolower($text);
        $wordLower = mb_strtolower($word);
        $textCleaned = preg_replace('/[^\w\s]/u', ' ', $textLower);
        $count = substr_count($textCleaned, $wordLower);


        // Обрабатываем слова
        foreach ($request->words as $word) {
            // Находим или создаем слово
            $wordModel = Word::firstOrCreate(['word' => $word]);

            // Подсчитываем количество вхождений слова в тексте статьи без учета регистра
            $textLower = mb_strtolower($request->text);
            $wordLower = mb_strtolower($word);
            $textCleaned = preg_replace('/[^\w\s]/u', ' ', $textLower);
            $count = substr_count($textCleaned, $wordLower);

            // Проверяем, существует ли связь со статьей
            $existingEntry = $article->words()->where('word_id', $wordModel->id)->first();

            if ($existingEntry) {
                // Если связь существует, обновляем количество вхождений
                $article->words()->updateExistingPivot($wordModel->id, ['count' => $existingEntry->pivot->count + $count]);
            } else {
                // Если связи нет, создаем новую
                $article->words()->attach($wordModel->id, ['count' => $count]);
            }
        }

        return response()->json($article->load('words'), 201);
    }

    /**
     * Поиск статей по ключевым словам
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = $request->query('keyword');

        // Поиск статей по словам-атомам
        $articles = Article::whereHas('words', function ($query) use ($keyword) {
            $query->where('word', $keyword);
        })->with(['words' => function ($query) use ($keyword) {
            $query->where('word', $keyword);
        }])->get();

        // Формируем результат с количеством вхождений
        $articlesWithCounts = $articles->map(function ($article) use ($keyword) {
            // Получаем количество вхождений из pivot таблицы
            $wordEntry = $article->words()->firstWhere('word', $keyword);
            $count = $wordEntry ? $wordEntry->pivot->count : 0;

            return [
                'id' => $article->id,
                'title' => $article->title,
                'url' => $article->url,
                'text' => $article->text,
                'word_count' => $count, // Добавляем количество вхождений
            ];
        });

        $sortedArticles = $articlesWithCounts->sortByDesc('word_count');

        return response()->json($sortedArticles);
    }
}
