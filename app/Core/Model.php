<?php
/**
 * Model - the base model
 *
 * @author David Carr - dave@daveismyname.com
 * @author Bui Vo Quoc Bao - bvqbao@gmail.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated November 12, 2015
 */

namespace Core;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Validation\DatabasePresenceVerifier;
use Core\Validator;
use Helpers\Arr;

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
	 * Indicate if the model should be timestamped.
	 * 
	 * @var boolean
	 */
	public $timestamps = false;

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
	 * Passes a validator object and allows for adding custom rules.
	 *
	 * @param \Illuminate\Validation\Validator $validator
	 */
	protected static function addCustomRules($validator) {}	

	/**
	 * Create a validator for current attributes.
	 * 
	 * @param  array  $rules    rules used by the validator
	 * @param  array  $messages validation messages
	 * @return \Illuminate\Validation\Validator
	 */
	protected function makeValidator(array $rules, array $messages = []) 
	{
		$validator = Validator::make($this->attributes, $rules, $messages);

		// Enable database-dependent validations (e.g. unique)
		$validator->setPresenceVerifier(new DatabasePresenceVerifier(parent::$resolver));

		// Add custom rules provided by child classes
		static::addCustomRules($validator);

		return $validator;	
	}	

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

		$validator = $this->makeValidator($rules, $customMessages);
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
