<?php

namespace Core\Handlers;

use Core\Handlers\Error;
use Core\Handlers\NotAllowed;
use Core\Handlers\NotFound;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Register application handlers. These handlers will override the default ones
 * provided by Slim framework.
 */
class HandlerServiceProvider implements ServiceProviderInterface
{
	/**
	 * Register any handlers for the application.
	 * 
	 * @param  \Pimple\Container $container
	 */
	public function register(Container $container)
	{
		$this->registerNotFoundHandler($container);
		$this->registerNotAllowedHandler($container);
		$this->registerErrorHandler($container);
	}

	/**
	 * Register 404 Not Found handler.
	 * 
	 * @param  \Pimple\Container $container
	 */
	protected function registerNotFoundHandler($container)
	{
		$container['notFoundHandler'] = function() {
    		return new NotFound();
		};		
	}

	/**
	 * Register 405 Method Not Allowed handler.
	 * 
	 * @param  \Pimple\Container $container
	 */
	protected function registerNotAllowedHandler($container)
	{
		$container['notAllowedHandler'] = function() {
    		return new NotAllowed();
		};		
	}	

	/**
	 * Register 500 System Error handler.
	 * 
	 * @param  \Pimple\Container $container
	 */
	protected function registerErrorHandler($container)
	{
		$this->registerLogger($container);

		$container['errorHandler'] = function() use ($container) {
			$errorHandler = new Error($container['config']['app.debug']);
			$errorHandler->setLogger($container['logger']);

    		return $errorHandler;
		};		
	}	

	/**
	 * Register a logger used in the application.
	 * 
	 * @param  \Pimple\Container $container
	 */
	protected function registerLogger($container)
	{
		$container['logger'] = function() use ($container) {
		    $logger = new \Monolog\Logger('logger');
		    $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
		    $logger->pushHandler(new \Monolog\Handler\StreamHandler(
		    	$container['path'].'logs/error.log', 
		    	\Monolog\Logger::DEBUG));

		    return $logger;
		};
	}
}