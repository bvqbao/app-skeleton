<?php

namespace Core;

use Interop\Container\ContainerInterface;

/**
 * The base controller.
 */
abstract class Controller
{
    /**
     * Container.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * Create a new controller instance.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get a service from the container.
     *
     * @param  string $name
     * @return mixed
     */
    protected function get($name)
    {
        return $this->container->get($name);
    }

    /**
     * Get a service from the container.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->container->get($name);
    }

    /**
     * Check if a service is present in the container.
     *
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->container->has($name);
    }
}
