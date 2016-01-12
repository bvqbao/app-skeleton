<?php

namespace Support\Facades;

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
