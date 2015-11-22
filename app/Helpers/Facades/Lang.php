<?php
/**
 * Lang - The translation service.
 */

namespace Helpers\Facades;

/**
 * Simulate Laravel's Lang facade.
 */
class Lang extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'translator';
    }  
}
