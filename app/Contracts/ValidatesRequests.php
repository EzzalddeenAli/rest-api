<?php namespace App\Contracts;

use App;
use Response;
use Validator;

trait ValidatesRequests {

	/**
	 * Validate the given request with the given rules.
	 *
	 * @param  array  $data
	 * @param  array  $rules
	 * @param  array  $messages
	 * @return void
	 */
	public function validate(array $data, array $rules, array $messages = array(), $connection)
	{
                Validator::extend('mongo_id', function($attribute, $value, $parameters)
                {
			return true === \MongoId::isValid($value);
                });

		$validator = Validator::make($data, $rules, $messages);
		
		if(null !== $connection)
		{
			$verifier = App::make('validation.presence');
			$verifier->setConnection($connection);
			$validator->setPresenceVerifier($verifier);
		}

		if ($validator->fails())
		{
			$this->throwValidationException($validator);
		}
	}

	/**
	 * Throw the failed validation exception.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Contracts\Validation\Validator  $validator
	 * @return void
	 */
	protected function throwValidationException($validator)
	{
		Response::send(400, null, $this->formatValidationErrors($validator));
	}

	/**
	 * Format the validation errors to be returned.
	 *
	 * @param  \Illuminate\Validation\Validator  $validator
	 * @return array
	 */
	protected function formatValidationErrors($validator)
	{
		return $validator->errors()->getMessages();
	}

}
