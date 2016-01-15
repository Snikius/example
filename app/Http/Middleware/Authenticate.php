<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

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
	public function __construct()
	{
		$this->auth = \App::make('SSOBroker');
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// Получаем сессию sso
		$this->auth->attach();
		// Если пользователь не авторизован делаем редирект (если не ajax)
		if (!\Auth::check()) {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				$protocol   = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
				$returnUrl  = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$url 		= \Config::get('sso.login_url') . "?return_url=" . $returnUrl;
				return redirect($url);
			}
		}
		return $next($request);
	}

}
