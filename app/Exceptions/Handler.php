<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if ($this->isHttpException($e)) {
			return $this->renderHttpException($e);
		}
		// Обработка исключения "нет ключей"
		if ($e instanceof NoKeysException) {
			if ($request->ajax()) {
				return response()->view('errors.no_keys', [], 401);
			}
			return response()->view('errors.no_keys', [], 401);
		}
		return parent::render($request, $e);
	}

}
