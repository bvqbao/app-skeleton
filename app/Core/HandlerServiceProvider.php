<?php

namespace Core;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

class HandlerServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		$this->registerNotFoundHandler($pimple);
	}

	protected function registerNotFoundHandler($pimple)
	{
		$pimple['notFoundHandler'] = function() {
    		return new NotFound();
		};		
	}
}