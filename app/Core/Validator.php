<?php
/**
 * Validator - the validator facade
 *
 * @author Bui Vo Quoc Bao - bvqbao@gmail.com
 * @version 2.2
 * @date Nov 10, 2015
 */

namespace Core;

use Illuminate\Validation\Factory;
use Symfony\Component\Translation\Translator;

/**
 * Make it a little bit easier to use \Illuminate\Validation\Validator class inside the framework.
 */
class Validator
{
	/**
	 * Store the validator factory.
	 * @var \Illuminate\Validation\Factory
	 */
	protected static $factory = null;

	/**
	 * Create a new factory instance (if necessary).
	 * @return \Illuminate\Validation\Factory
	 */
	protected static function getFactory()
	{
		if (! static::$factory) {
			static::$factory = new Factory(new Translator('en_US'));
		}
		return static::$factory;
	}

	/**
	 * Create a new validator instance.
	 * @param  array  $data
	 * @param  array  $rules
	 * @param  array  $messages
	 * @param  array  $customAttributes
	 * @return \Illuminate\Validation\Validator
	 */
	public static function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
	{
		return static::getFactory()->make($data, $rules, $messages, $customAttributes);
	}
}