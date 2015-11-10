<?php
/**
 * Model - the base model
 *
 * @author David Carr - dave@daveismyname.com
 * @author Bui Vo Quoc Bao - bvqbao@gmail.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Sept 19, 2015
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
	 * @var array
	 */
	protected $rules = [];

	/**
	 * Store the context we are validating within.
	 * @var string
	 */
	protected $context = null;

	/**
	 * Store any custom messages for validation rules.
	 * @var array
	 */
	protected $messages = [];

	/**
	 * Store any validation errors generated.
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Disable timestamps.
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * Set the validation context.
	 *
	 * @param  string $context
	 * @return object
	 */
	public function setContext($context)
	{
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
			Arr::get($this->rules, self::DEFAULT_KEY) );
	}	

	/**
	 * Get the validaton rules within the validation context
	 *
	 * @return array
	 */
	protected function getRulesInContext()
	{
		if ( ! $this->hasContext()) {
			return $this->rules;
		}	

		$rulesInContext = Arr::get($this->rules, self::DEFAULT_KEY, array());
		if ( ! Arr::get($this->rules, $this->context)) {
			throw new \Exception(
				sprintf(
					"'%s' does not contain the validation context '%s'",
					get_called_class(),
					$this->context
				)
			);
		}
		$rulesInContext = array_merge($rulesInContext, $this->rules[$this->context]);
		return $rulesInContext;
	}

	/**
	 * Stub method that can be extended by child classes.
	 * Passes a validator object and allows for adding validation extensions.
	 *
	 * @param \Illuminate\Validation\Validator $validator
	 */
	protected function addExtensions($validator) {}	

	/**
	 * Create a validator for model attributes
	 * @param  array  $rules    rules used by the validator
	 * @return \Illuminate\Validation\Validator
	 */
	protected function createValidator(array $rules = []) 
	{
		$validator = Validator::make($this->attributes, $rules, $this->messages);

		// Enable database-dependent validations (e.g. unique)
		$validator->setPresenceVerifier(new DatabasePresenceVerifier(parent::$resolver));

		// Add validation extensions provided by child classes
		$this->addExtensions($validator);

		return $validator;	
	}

	/**
	 * Replace the placeholders in validation rules
	 * @param  string $placeholder 	the placeholder
	 * @param  string $value		the placeholder value
	 * @param  array  $rules 		the validation rules
	 * @return array 
	 */
	protected function replacePlaceholders($placeholder, $value, array $rules = [])
	{
        foreach ($rules as &$rule) {
            $rule = str_replace($placeholder, $value, $rule);
        }		

        return $rules;
	}	

	/**
	 * Validate model data
	 * @return boolean
	 */
	public function validate()
	{
		$rules = $this->replacePlaceholders(':id', 
			$this->attributes["{$this->primaryKey}"], 
			$this->getRulesInContext());

		$validator = $this->createValidator($rules);
		if ($validator->passes()) {
			return true;
		}

		$this->errors = $validator->messages();
		return false;
	}

	/**
	 * Return data validation errors
	 * @return \Illuminate\Support\MessageBag
	 */
	public function errors()
	{
		return $this->errors;
	}
}
