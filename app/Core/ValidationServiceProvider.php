<?php

namespace Core;

use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Register a validation service into the container.
 */
class ValidationServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		$this->registerPresenceVerifier($pimple);

		$pimple['validator'] = function() use ($pimple) {
			$validator = new Factory($pimple['translator']);	
					
			if(isset($pimple['validation.presence'])) {
				$validator->setPresenceVerifier($pimple['validation.presence']);	
			}

		    return $validator;
		};	
	}	

    protected function registerPresenceVerifier(Container $pimple)
    {
        $pimple['validation.presence'] = function() use ($pimple) {
            return new DatabasePresenceVerifier($pimple['db']);
        };
    }	
}