<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Word;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Класс для управления статьями
 */
class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService
    )
    {
    }

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
     * @param ArticleRequest $request
     * @return JsonResponse
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Создаем статью в БД
        $article = $this->articleService->createArticle($data);

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
