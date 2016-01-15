<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class DocsOAuth
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        // Проверяем есть ли ключ oauth
        $redis = \App::make('redis');
        if ($redis->exists('docs_token')) {
            return $next($request);
        }
        try {
            // Если ключа нет, пробуем его получить
            $client = new \GuzzleHttp\Client();
            $response = $client->post(\Config::get('docs-api.server') . 'api/oauth/access_token', [
                'body' => [
                    'client_secret' => \Config::get('docs-api.secret'),
                    'client_id' => 'hotdocs',
                ]
            ]);
            $json = $response->json();
            if (isset($json['access_token']) && isset($json['expires_in'])) {
                // Сохраняем ключ и успешно завершаем фильтр
                $redis->set('docs_token', $json['access_token']);
                $redis->expire('docs_token', $json['expires_in']-1);
                return $next($request);
            } else {
                throw new \Exception("Response error");
            }
        } catch (\Exception $e) {
            // Если что-то не так выбрасываем на страницу ошибки
            abort(503);
        }
    }
}