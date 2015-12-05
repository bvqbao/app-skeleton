<?php

namespace Core;

use Interop\Container\ContainerInterface;

/**
 * This class serves as a fake container. Its main purpose is to 
 * provide access to the container used by the application.
 */
final class Container
{
	/** 
	 * The current globally available container (if any).
	 * 
	 * @var \Interop\Container\ContainerInterface
	 */
	private static $instance;

	/**
	 * Prevent others from instanciating this class.
	 */
	private function __construct()
	{

	}

	/** 
	 * Get the globally available instance of the container.
	 * 
	 * @return \Interop\Container\ContainerInterface
	 */
	public static function getInstance()
	{
		return static::$instance;
	}

    /**
     * Set the shared instance of the container.
     *
     * @param  \Interop\Container\ContainerInterface  $container
     */
	public static function setInstance(ContainerInterface $container)
	{
		static::$instance = $container;
	}
}
