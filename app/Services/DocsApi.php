<?php namespace App\Services;

class DocsApi
{

    /**
     * Обертка для кэширования запросов
     * @param $cache_key
     * @param \Closure $query
     * @return mixed
     */
    public function cachedQuery($cache_key, \Closure $query)
    {
        $cache_time = \Config::get('docs-api.cache_time');
        return \Cache::remember($cache_key, $cache_time, function() use ($query)
        {
            return $query();
        });
    }

    /**
     * Формируем список продуктов разбитый по блокам
     * @param array $products
     * @return array
     */
    public function sortProducts(array $products)
    {
        $redis = \App::make('redis');
        $token = $redis->get('docs_token');
        $client = new \GuzzleHttp\Client();

        $response = $client->get(\Config::get('docs-api.server') . 'api/v1/hot/hot/products', [
            'query' => [
                'products' => $products,
                'cache' => 1,
                'blocks' => 1,
                'token' => $token
            ],
            'connect_timeout' => 500,
        ]);

        $json = $response->json();
        return isset($json['result']) ? $json['result'] : false;
    }

    /** Интелектуальный поиск
     * @param $query
     * @param array $products
     * @param bool|false $category
     * @param int $offset
     * @param int $limit
     * @return bool
     */
    public function searchDocuments($query, array $products, $category = false, $offset = 0, $limit = 20)
    {
        $redis = \App::make('redis');
        $token = $redis->get('docs_token');
        $client = new \GuzzleHttp\Client();
        $query = [
            'query' => $query,
            'site' => 'hotdocs',
            'output'=>'json',
            'token' => $token,
            'limit' => $limit,
            'offset' => $offset,
            'products' => $products,
        ];
        if($category !== false) {
            $query['category'] = $category;
        }
        $response = $client->get(\Config::get('docs-api.server') . 'api/v1/search/intellectual/get', [
            'query' => $query,
        ]);
        $json = $response->json();
        return isset($json['result']) ? $json['result'] : false;
    }

    /**
     * Поиск по категориям и продуктам
     * @param array $products
     * @param bool|false $category
     * @param int $offset
     * @param int $limit
     * @return bool
     */
    public function documentsByProducts(array $products, $category = false, $offset = 0, $limit = 20)
    {
        $redis = \App::make('redis');
        $token = $redis->get('docs_token');
        $client = new \GuzzleHttp\Client();
        $query = [
            'products' => $products,
            'output'=>'json',
            'token' => $token,
            'limit' => $limit,
            'offset' => $offset,
        ];
        if($category !== false) {
            $query['category'] = $category;
        }
        $response = $client->get(\Config::get('docs-api.server') . 'api/v1/search/hot/get', [
            'query' => $query,
        ]);
        $json = $response->json();
        return isset($json['result']) ? $json['result'] : false;
    }
}