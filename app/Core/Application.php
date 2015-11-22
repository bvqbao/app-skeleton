<?php

namespace Core;

use Slim\App;
use Helpers\Facades\Facade;

class Application extends App
{
	/**
	 * Create a new application.
	 * 
	 * @param ContainerInterface|array $container
	 */
	public function __construct($container = [])
	{
		parent::__construct($container);

		/** Load app config. */
		require SMVC.'app/config/appstart.php';
	}

	/**
	 * Bootstrap the application.
	 * 
	 * @return \Core\Application
	 */
	public function bootstrap()
	{
		$this->registerServices();
		$this->registerFacades();

		return $this;
	}

	/**
	 * Register facades which are shortcuts for accessing
	 * registered services.
	 */
	protected function registerFacades()
	{
		Facade::setContainer($this->getContainer());
	}

	/**
	 * Register the specified services.
	 */
	protected function registerServices()
	{
		$services = require SMVC.'app/config/services.php';

		$container = $this->getContainer();
		foreach($services as $service) {
			$container->register(new $service);
		}
	}
}