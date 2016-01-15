<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Exceptions\NoKeysException;

class ContentAccess
{
    public function handle($request, Closure $next)
    {
        // Проверяем наличие ключей у пользователя
        $crmUser = \App::make('CRMUser');
        try {
            $keys = $crmUser->getRegs(true);
        } catch(\Exception $e) {
            // Если произошел сбой получения ключей
            throw new NoKeysException();
        }
        // если нет, отправляем на страницу *нет ключей* или 401 ошибку (обработчик App\Exceptions\Handler.php)
        if(empty($keys)) {
            throw new NoKeysException();
        }
        return $next($request);
    }
}