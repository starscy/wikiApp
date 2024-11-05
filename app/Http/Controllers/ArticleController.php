<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ArticleStoreRequest;
use App\Models\Article;
use App\Services\ArticleService;
use App\Services\ArticlesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Класс для управления статьями
 */
class ArticleController extends Controller
{
    /**
     * @param ArticlesService $articlesService - сервис по работе со статьями
     * @param ArticleService $articleService - сервис по работе со статьей
     */
    public function __construct(
        protected ArticlesService $articlesService,
        protected ArticleService  $articleService
    )
    {
    }

    /**
     * Получаем списки статей
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $articles = Article::paginate($request->query('per_page', 10));

            if ($articles->isEmpty()) {
                return response()->json(['message' => 'Статьи не найдены'], 404);
            }

            return response()->json($articles, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка при получении статей'], 500);
        }
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
     * @param ArticleStoreRequest $request
     * @return JsonResponse
     */
    public function store(ArticleStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        session(['save-to-db-progress' => '1']);
        // Создаем статью в БД
        $article = $this->articleService->createArticle($data);
        session(['save-to-db-progress' => '100']);

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
        $sortedArticles = $this->articlesService->searchArticlesByKeyword($request->query('keyword'));

        return response()->json($sortedArticles);
    }
}
