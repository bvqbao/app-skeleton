<?php

namespace Core;

use Slim\Container as SlimContainer;

/**
 * This class serves as a fake container. Its main purpose is 
 * to create a global Slim container (Pimple).
 *
 * Slim\App (hence Core\Application) expects a container that implements 
 * Interop\Container\ContainerInterface with these service keys configured and ready for use:
 *
 *  - settings: an array or instance of \ArrayAccess
 *  - environment: an instance of \Slim\Interfaces\Http\EnvironmentInterface
 *  - request: an instance of \Psr\Http\Message\ServerRequestInterface
 *  - response: an instance of \Psr\Http\Message\ResponseInterface
 *  - router: an instance of \Slim\Interfaces\RouterInterface
 *  - foundHandler: an instance of \Slim\Interfaces\InvocationStrategyInterface
 *  - errorHandler: a callable with the signature: function($request, $response, $exception)
 *  - notFoundHandler: a callable with the signature: function($request, $response)
 *  - notAllowedHandler: a callable with the signature: function($request, $response, $allowedHttpMethods)
 *  - callableResolver: an instance of \Slim\Interfaces\CallableResolverInterface
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
			// Create a container with default services.
			static::$instance = new SlimContainer;
		}

		return static::$instance;
	}

}