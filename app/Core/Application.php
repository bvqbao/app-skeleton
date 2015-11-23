<?php

namespace Core;

use Core\Configuration;
use Helpers\Facades\Facade;
use Slim\App;

class Application extends App
{
	/**
	 * The application's base path.
	 * 
	 * @var string
	 */
	protected $basePath = SMVC;

	/**
	 * Create a new application.
	 * 
	 * @param ContainerInterface|array $container
	 */
	public function __construct($container = [])
	{
		// Load all the config files.
		$items = $this->loadConfigurationFiles();
		$config = new Configuration($items);

		// Apply config for the application.
		$settings = isset($container['settings']) ? $container['settings'] : [];
		$settings = array_merge($settings, ['displayErrorDetails' => $config['app.debug']]);
		$container['settings'] = $settings;

		parent::__construct($container);

		// Store the config instance for latter use.
		$container = $this->getContainer();		
		$container['config'] = function() use ($config) {
			return $config;
		};

		date_default_timezone_set($config['app.timezone']);

		mb_internal_encoding('UTF-8');
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
	 * Register the specified services.
	 */
	protected function registerServices()
	{
		$container = $this->getContainer();

		$services = $container['config']['services'];
		foreach($services as $service) {
			$container->register(new $service);
		}
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
	 * Get/Set the base path of the application.
	 */
	public function basePath($basePath = null)
	{
		if (is_string($basePath)) {
			$this->basePath = $basePath;
		}

		return $this->basePath;
	}

	/**
	 * Get the config path of the application.
	 * 
	 * @return string
	 */
	public function configPath()
	{
		return $this->basePath().'app/config/';
	}

    /**
     * Load the configuration files.
     * 
     * @return  array
     */
    protected function loadConfigurationFiles()
    {
    	$items = [];

        $files = glob($this->configPath().'*.php');
    	foreach($files as $file) {
            $name = basename($file, '.php');
            $items[$name] = require $file;
    	}

    	return $items;
    }	
}