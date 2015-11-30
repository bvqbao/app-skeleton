<?php

namespace Core\Providers;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Register a validation service into the container.
 */
class ValidationServiceProvider implements ServiceProviderInterface
{
	/**
	 * Register any validation services.
	 * 
	 * @param  \Pimple\Container $container
	 */	
	public function register(Container $container)
	{
		$this->registerPresenceVerifier($container);

		$container['validator'] = function() use ($container) {
			$validator = new Factory($container['translator']);	
					
			if(isset($container['validation.presence'])) {
				$validator->setPresenceVerifier($container['validation.presence']);	
			}

		    return $validator;
		};	
	}	

	/**
	 * Register a presence verifier used by the validation service.
	 * 
	 * @param  \Pimple\Container $container
	 */	
    protected function registerPresenceVerifier(Container $container)
    {
    	if (isset($container['db']) and ($container['db'] instanceof ConnectionResolverInterface)) {
	        $container['validation.presence'] = function() use ($container) {
	            return new DatabasePresenceVerifier($container['db']);
	        };    		
    	}
    }	
}