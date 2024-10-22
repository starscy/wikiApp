<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *  Класс работает с wiki Api
 */
class WikiController extends Controller
{
    /**
     * @var Client
     */
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Обрабатывает запросы к API Wikipedia.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->makeApiRequest($request);
    }

    /**
     * Метод парсинга контента
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function parse(Request $request)
    {
        $queryParams = array_merge($request->query(), [
            'action' => 'parse',
            'format' => 'json',
            'prop' => 'text',
            'section' => 0
        ]);
        return $this->makeApiRequest($request, $queryParams);
    }

    /**
     * Выполняет запрос к API Wikipedia.
     *
     * @param Request $request
     * @param array $queryParams
     * @return JsonResponse
     * @throws GuzzleException
     */
    protected function makeApiRequest(Request $request, array $queryParams = []): JsonResponse
    {
        try {
            $response = $this->client->request('GET', 'https://ru.wikipedia.org/w/api.php', [
                'query' => $queryParams ?: $request->query(),
            ]);
            return response()->json(json_decode($response->getBody()->getContents()));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при запросе к API: ' . $e->getMessage()], 500);
        }
    }
}
