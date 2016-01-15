<?php namespace App\Services;

use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return \Validator::make($data, [
			'email' => 'required|email',
			'password' => 'required',
			'phone' => 'required|min:10',
			'fio' => 'required',
			'region' => 'required',
			'profession' => 'required',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$result = \CAS::reg($data['email'], $data);
		if($result) {
			$response = \CAS::getData();
			Email::sendConfirmation($data['email'], $response['confirmation_code'], $data['fio']);
		}
		return $result;
	}

}
