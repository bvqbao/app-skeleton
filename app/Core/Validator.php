<?php
/**
 * Validator - the base validator
 *
 * @author Bui Vo Quoc Bao - bvqbao@gmail.com
 * @version 2.2
 * @date Nov 10, 2015
 */

namespace Core;

use Illuminate\Validation\Validator as IlluminateValidator;
use Symfony\Component\Translation\Translator;

/**
 * Make it a little bit easier to use \Illuminate\Validation\Validator class inside the framework
 */
class Validator extends IlluminateValidator
{
	/**
	 * Create a new Validator instance.
	 * @param  array  $data
	 * @param  array  $rules
	 * @param  array  $messages
	 * @param  array  $customAttributes
	 * @return \Core\Validator
	 */
	public static function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
	{
		$translator = new Translator('en_US'); 
		return new static($translator, $data, $rules, $messages, $customAttributes);
	}
}