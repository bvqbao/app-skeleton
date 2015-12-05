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
    	// Read all config files
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

		date_default_timezone_set($config['app.timezone']);
		mb_internal_encoding('UTF-8');   

		// Register a logger used in the application
		$container['logger'] = function() use ($container) {
		    $logger = new \Monolog\Logger('logger');
		    $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
		    $logger->pushHandler(new \Monolog\Handler\StreamHandler(
		    	$container['path.system'].'logs/error.log', 
		    	\Monolog\Logger::DEBUG));

		    return $logger;
		};		

		// Register 500 System Error handler
		$container['errorHandler'] = function() use ($container) {
			$errorHandler = new \Core\Handlers\Error($container['config']['app.debug']);
			$errorHandler->setLogger($container['logger']);

    		return $errorHandler;
		};

    	// Register 404 Not Found handler
 		$container['notFoundHandler'] = function() {
    		return new \Core\Handlers\NotFound();
		};	   	

		// Register 405 Method Not Allowed handler
		$container['notAllowedHandler'] = function() {
    		return new \Core\Handlers\NotAllowed();
		};		 

		// Register the specified providers.		
		$providers = $config['app.providers'];
		foreach($providers as $provider) {
			$container->register(new $provider);
		}

		// Register facades which are shortcuts 
		// for accessing registered services.
		Facade::setContainer($container);
    }

    /**
     * Shortcut for registering services into the container.
     * 
     * @param  string $key
     * @param  mixed $value
     */
    public function register($key, $value)
    {
    	$this->getContainer()[$key] = $value;
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

        foreach (['base', 'system', 'config', 'lang'] as $path) {
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
	 * Get the path to the application "system" directory.
	 * 
	 * @return string
	 */
	protected function systemPath()
	{
		return $this->basePath.'system'.DIRECTORY_SEPARATOR;
	}	

	/**
	 * Get the path to the application configuration files.
	 * 
	 * @return string
	 */
	protected function configPath()
	{
		return $this->basePath.'system'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR;
	}

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    protected function langPath()
    {
        return $this->basePath.'system'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR;
    }
}
