<?php

namespace Core;

use Slim\Container as SlimContainer;

class Container
{
	/** 
	 * The global container instance used in the application.
	 * 
	 * @var \Slim\Container
	 */
	protected static $instance;

	protected function __construct()
	{

	}

	/** 
	 * Create the global container instance.
	 * 
	 * @return \Slim\Container
	 */
	public static function getInstance()
	{
		if (! static::$instance) {
			static::$instance = new SlimContainer;
		}

		return static::$instance;
	}

}