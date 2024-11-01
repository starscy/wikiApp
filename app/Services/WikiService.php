<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Сервис по работе с Wikipedia
 */
class WikiService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * Выполняет запрос к API Wikipedia с текущими параметрами.
     *
     * @param array $queryParams
     * @return array
     * @throws Exception
     */
    public function makeApiRequest(array $queryParams): array
    {

        try {
            $response = $this->client->request('GET', 'https://ru.wikipedia.org/w/api.php', [
                'query' => $queryParams,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new \Exception('Ошибка при запросе к API: ' . $e->getMessage());
        }
    }

    /**
     * Добавляем параметры для парсинга
     *
     * @param array $requestParams
     * @return array
     */
    public function buildQueryParamsForParse(array $requestParams): array
    {
        return array_merge($requestParams, [
            'action' => 'parse',
            'format' => 'json',
            'prop' => 'text',
            'section' => 0
        ]);
    }
}
