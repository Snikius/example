<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Http\Request;

abstract class Controller extends BaseController {

	use DispatchesCommands;

	/**
	 * Валидация для всех контроллеров работающих через ajax
	 * @param Request $request
	 * @param array $rules
	 * @param array $messages
	 */
	protected function validate(Request $request, array $rules, array $messages = array())
	{
		$validator = $this->getValidationFactory()->make($request->all(), $rules, $messages);

		if ($validator->fails())
		{
			$this->throwValidationException($request, $validator);
		}
	}

	/**
	 * Исключение валидации в контроллере
	 * @param Request $request
	 * @param $validator
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function throwValidationException(Request $request, $validator)
	{
		return response()->json([ 'success' => false,
			'errors'  => $validator->messages() ], 400);
	}

	/**
	 * Get a validation factory instance.
	 *
	 * @return \Illuminate\Contracts\Validation\Factory
	 */
	protected function getValidationFactory()
	{
		return app('Illuminate\Contracts\Validation\Factory');
	}


}
