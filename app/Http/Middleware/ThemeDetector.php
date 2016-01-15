<?php namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;

class ThemeDetector {

    /**
     * Create a new filter instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        $agent = new Agent();
        // Определяем устройство и устанавливаем пути к js контроллерам и видам
        if($agent->isMobile()) {
            \Config::set('js-controllers.path_controllers', 'dist/main/ng');
            \Config::set('js-controllers.path_js_files', 'dist/main/js');
            \View::addLocation(realpath(base_path('resources/views/mobile')));
        } else {
            \Config::set('js-controllers.path_controllers', 'dist/main/ng');
            \Config::set('js-controllers.path_js_files', 'dist/main/js');
            \View::addLocation(realpath(base_path('resources/views/desktop')));
        }

        return $next($request);
    }

}
