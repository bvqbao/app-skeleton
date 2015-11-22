<?php
/**
 * Validator - The validation service.
 */

namespace Helpers\Facades;

/**
 * Simulate Laravel's Validator facade.
 */
class Validator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'validator';
    }  
}    	