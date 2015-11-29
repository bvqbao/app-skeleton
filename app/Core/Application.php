<?php

namespace Core;

use Core\Configuration;
use Core\Container;
use Support\Facades\Facade;
use Slim\App;

/**
 * The main application.
 */
class Application extends App
{
	/**
	 * The application's base path.
	 * 
	 * @var string
	 */
	protected $basePath;

	/**
	 * Create a new application.
	 *
	 * @param string $basePath
	 */
	public function __construct($basePath = null)
	{
		if (! $basePath) {
			throw new \InvalidArgumentException(
				"The application path has not been set.");
		}

		// Create an application with default services.
		parent::__construct(Container::getInstance());
		
		// Setup paths.
		$this->setBasePath($basePath);	

		// Load all config files and apply these configs
		// to the application.
		$this->applyUserConfig();
	}

    /**
     * Apply user configurations to the application.
     */
    protected function applyUserConfig()
    {
		$config = $this->getAppConfig();

		date_default_timezone_set($config['app.timezone']);
		mb_internal_encoding('UTF-8');    

		$this->registerServices();
    }

    /**
     * Get the configurations for the application.
     * 
     * @return \Core\Configuration
     */
    protected function getAppConfig()
    {
		$config = new Configuration($this->loadConfigFiles());

		// Register the configuration instance for later use.
		// Note that Slim framework already used a service key named 'settings' for 
		// accessing the framework settings. Here, we will use a separate key for 
		// the application configurations. If you want to reuse the service key,
		// you should merge the framework settings with the application configurations.
		$container = $this->getContainer();
		$container['config'] = function() use ($config) {
			return $config;
		};  
		
		return $config;
    }

    /**
     * Load the configuration files.
     * 
     * @return  array
     */
    protected function loadConfigFiles()
    {
    	$items = [];

        $files = glob($this->configPath().'*.php');
    	foreach($files as $file) {
            $name = basename($file, '.php');
            $items[$name] = require $file;
    	}

    	return $items;
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

		// Register facades which are shortcuts 
		// for accessing registered services.
		Facade::setContainer($container);
	}	

	/**
	 * Set the path to the framework installation.
	 *
	 * @return \Core\Application
	 */
	public function setBasePath($basePath)
	{
		$this->basePath = $basePath;

		$this->bindPathsInContainer();

		return $this;
	}	

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
    	$container = $this->getContainer();

        $container['path'] = $this->path();

        foreach (['base', 'config', 'lang'] as $path) {
            $container['path.'.$path] = $this->{$path.'Path'}();
        }
    }	

	/**
	 * Get the path to the framework installation.
	 */
	public function basePath($path = '')
	{
		return $this->basePath.ltrim($path, '/');
	}

	/**
	 * Get the path to the application "app" directory.
	 * 
	 * @return string
	 */
	protected function path()
	{
		return $this->basePath.'app/';
	}

	/**
	 * Get the path to the application configuration files.
	 * 
	 * @return string
	 */
	protected function configPath()
	{
		return $this->basePath.'app/config/';
	}

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    protected function langPath()
    {
        return $this->basePath.'app/lang/';
    }	
}