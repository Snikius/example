<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthByString
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
        // Получаем строку из запроса и если пользователь уже не залогинен продолжаем
        $auth_string = \Input::get('a');
        if(!$auth_string || $this->auth->check()) {
            return $next($request);
        }

        $authString = \App::make('AuthString');
        $authString->set($auth_string);

        // делаем попытку входа через строку
        if($authString->isValid()) {
            $this->auth->attempt(['auth_string'=>$auth_string]);
        }

        return $next($request);
    }
}