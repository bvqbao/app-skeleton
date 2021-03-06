<?php

namespace Core;

use Core\Config\Repository;
use Core\Container;
use Slim\App;
use Support\Facades\Facade;

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
    public function __construct($basePath)
    {
        if (is_null($basePath)) {
            throw new \InvalidArgumentException("The base path has not been set.");
        }

        parent::__construct();

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
        $container['config'] = function () use ($config) {
            return $config;
        };

        date_default_timezone_set($config['app.timezone']);
        mb_internal_encoding('UTF-8');

        // Register route handler strategy
        $container['foundHandler'] = function () {
            return new \Slim\Handlers\Strategies\RequestResponseArgs();
        };

        // Register a logger used in the application
        $container['logger'] = function () use ($container) {
            $logger = new \Monolog\Logger('logger');
            $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
            $logger->pushHandler(new \Monolog\Handler\StreamHandler(
                $container['path.storage'] . 'logs' . DIRECTORY_SEPARATOR . 'error.log'
            ));

            return $logger;
        };

        // Register 500 System Error handler
        $container['errorHandler'] = function () use ($container) {
            $errorHandler = new \Core\Handlers\Error($container['config']['app.debug']);
            if (isset($container['logger'])) {
                $errorHandler->setLogger($container['logger']);
            }

            return $errorHandler;
        };

        // Register 404 Not Found handler
        $container['notFoundHandler'] = function () {
            return new \Core\Handlers\NotFound();
        };

        // Register 405 Method Not Allowed handler
        $container['notAllowedHandler'] = function () {
            return new \Core\Handlers\NotAllowed();
        };

        // Register the specified providers.
        $providers = $config['app.providers'];
        foreach ($providers as $provider) {
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

        $files = glob($this->configPath() . '*.php');
        foreach ($files as $file) {
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

        foreach (['base', 'storage', 'config', 'lang'] as $path) {
            $container['path.' . $path] = $this->{$path . 'Path'}();
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
        return $this->basePath . 'app' . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the path to the application "storage" directory.
     *
     * @return string
     */
    protected function storagePath()
    {
        return $this->basePath . 'storage' . DIRECTORY_SEPARATOR;
    }    

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    protected function configPath()
    {
        return $this->basePath . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    protected function langPath()
    {
        return $this->basePath . 'resources' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR;
    }
}
