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
 * Simulate Laravel's Validator facade.
 */
class Validator
{
	/**
	 * Store the validator instance.
	 * 
	 * @var \Illuminate\Validation\Factory
	 */
	protected static $instance = null;

	/**
	 * Create a new factory instance (if necessary).
	 * 
	 * @return \Illuminate\Validation\Factory
	 */
	protected static function getInstance()
	{
		if (! static::$instance) {
			static::$instance = new Factory(new Translator('en_US'));
		}
		return static::$instance;
	}

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }    	
}