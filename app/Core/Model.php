<?php
/**
 * Model - the base model.
 */

namespace Core;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Support\Facades\Validator;
use Support\Arr;

/**
 * Base model class all other models will extend from this base.
 */
abstract class Model extends Eloquent
{
	/**
	 * Name of the default validation rule set
	 */
	const DEFAULT_KEY = 'default';

	/**
	 * Store the validation rules.
	 * 
	 * @var array
	 */
	protected static $rules = [];

	/**
	 * Store any custom messages for validation rules.
	 * 
	 * @var array
	 */
	protected static $messages = [];	

	/**
	 * Store the context we are validating within.
	 * 
	 * @var string
	 */
	protected $context = null;

	/**
	 * Store any validation errors generated.
	 * 
	 * @var \Illuminate\Support\MessageBag
	 */
	protected $errors;

	/**
	 * Set the validation context.
	 *
	 * @param  string $context
	 * @return object
	 */
	public function withinContext($context)
	{
		if (! is_string($context)) {
			throw new \InvalidArgumentException('The validation context must be a string.');
		}
		$this->context = trim($context);
		return $this;
	}

	/**
	 * Retrieve the valiation context.
	 *
	 * @return string
	 */
	public function getContext()
	{
		return $this->context;
	}	

	/**
	 * Check if the current model has a context.
	 *
	 * @return boolean
	 */
	protected function hasContext()
	{
		return ( (strlen($this->context) > 0) or 
			Arr::get(static::$rules, self::DEFAULT_KEY) );
	}	

	/**
	 * Get the validaton rules within the validation context.
	 *
	 * @return array
	 */
	protected function getRulesInContext()
	{
		if ( ! $this->hasContext()) {
			return static::$rules;
		}	

		$rulesInContext = Arr::get(static::$rules, self::DEFAULT_KEY, array());
		if ( ! Arr::get(static::$rules, $this->context)) {
			throw new \Exception(
				sprintf(
					"'%s' does not contain the validation context '%s'",
					get_called_class(),
					$this->context
				)
			);
		}
		$rulesInContext = array_merge($rulesInContext, static::$rules[$this->context]);
		return $rulesInContext;
	}

	/**
	 * Stub method that can be extended by child classes.
	 * Passes a validator object and allows for other configurations.
	 *
	 * @param \Illuminate\Validation\Validator $validator
	 */
	protected static function configValidator($validator) {}

	/**
	 * Validate current attributes against rules.
	 * 
	 * @return boolean
	 */
	public function validate(array $customMessages = [])
	{
		$rules = $this->getRulesInContext();
		foreach ($rules as &$rule) {
			$rule = str_replace(':id', $this->attributes["{$this->primaryKey}"], $rule);
		}

		$customMessages = (empty($customMessages)) ? static::$messages : $customMessages;

		$validator = Validator::make($this->attributes, $rules, $customMessages);
		static::configValidator($validator);

		if ($validator->passes()) {
			return true;
		}

		$this->errors = $validator->messages();
		return false;
	}

	/**
	 * Return validation errors.
	 * 
	 * @return \Illuminate\Support\MessageBag
	 */
	public function errors()
	{
		return $this->errors;
	}
}
