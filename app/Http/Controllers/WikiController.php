<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\WikiService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\Error;

/**
 *  Класс работает с wiki Api
 */
class WikiController extends Controller
{
    /**
     * @param WikiService $wikiService - вспомогательный сервис для запросов и работы с wikipedia
     */
    public function __construct(
        protected WikiService $wikiService,
    )
    {
    }

    /**
     * Отображение найденных статей по запросу значения.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->wikiService->makeApiRequest($request->query()));
    }

    /**
     * Отображение спарсенных статей по запросу значения.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function parse(Request $request): JsonResponse
    {
        try {
            $queryParams = $this->wikiService->buildQueryParamsForParse($request->query()); //формируем параметры из запроса для парсинга
            return response()->json($this->wikiService->makeApiRequest($queryParams));
        } catch (\Exception $e) {
            return response()->json(['message' => "Ошибка при парсинге статьи: $e"], 500);
        }

    }
}
