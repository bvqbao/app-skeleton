<?php

namespace Core;

use Slim\Container as SlimContainer;

/**
 * This class serves as a fake container. Its main purpose is 
 * to create a global Slim container.
 */
class Container
{
	/** 
	 * The global container instance used in the application.
	 * 
	 * @var \Slim\Container
	 */
	protected static $instance;

	/**
	 * Prevent others from instanciating this class.
	 */
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