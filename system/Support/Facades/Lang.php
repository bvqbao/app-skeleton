<?php

namespace Support\Facades;

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
