<?php

namespace Support\Facades;

/**
 * The base facade.
 */
abstract class Facade
{
    /**
     * The container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected static $container;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * Resolve the root instance of the facade.
     *
     * @param  string|object  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (!isset(static::$instances[$name])) {
            static::$instances[$name] = static::$container->get($name);
        }

        return static::$instances[$name];
    }

    /**
     * Set the container instance.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public static function setContainer($container)
    {
        static::$container = $container;
    }

    /**
     * Get the container instance.
     *
     * @return \Interop\Container\ContainerInterface
     */
    public static function getContainer()
    {
        return static::$container;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}
