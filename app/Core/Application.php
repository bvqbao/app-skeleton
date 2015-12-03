<?php

namespace Core;

use Core\Container;
use Core\Config\Repository;
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
	 * @param array|\Interop\Container\ContainerInterface $container
	 */
	public function __construct($basePath, $container = [])
	{
		if (! $basePath) {
			throw new \InvalidArgumentException(
				"The application path has not been set.");
		}

		parent::__construct($container);

		// Make the container instance used by this application
		// globally available.
		Container::setInstance($this->getContainer());
		
		$this->setBasePath($basePath);	

		$this->bootstrap();
	}

    /**
     * Bootstrap the application.
     */
    protected function bootstrap()
    {
		$config = $this->getAppConfig();

		date_default_timezone_set($config['app.timezone']);
		mb_internal_encoding('UTF-8');    

		$this->registerServices($config['app.providers']);
    }

    /**
     * Get the configurations for the application.
     * 
     * @return \Core\Config\Repository
     */
    protected function getAppConfig()
    {
		$config = new Repository($this->loadConfigFiles());

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
	 *
	 * @param array
	 */
	protected function registerServices(array $services)
	{
		$container = $this->getContainer();
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
	 *
	 * @return string
	 */
	public function basePath()
	{
		return $this->basePath;
	}

	/**
	 * Get the path to the application "app" directory.
	 * 
	 * @return string
	 */
	protected function path()
	{
		return $this->basePath.'app'.DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the path to the application configuration files.
	 * 
	 * @return string
	 */
	protected function configPath()
	{
		return $this->basePath.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;
	}

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    protected function langPath()
    {
        return $this->basePath.'app'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR;
    }
}
