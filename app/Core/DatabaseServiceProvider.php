<?php

namespace Core;

use Illuminate\Database\Capsule\Manager;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Register Eloquent into the container.
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
	/**
	 * Register services into the given container.
	 * 
	 * @param  \Pimple\Container $container
	 */		
	public function register(Container $container)
	{
		// We need to boot Eloquent before registering it 
		// into the container.
		$capsule = $this->bootEloquent($container);

		$container['db'] = function() use ($capsule) {			
			return $capsule->getDatabaseManager();
		};		
	}

	/**
	 * Setup and boot Eloquent.
	 * 
	 * @param  Container $container
	 * @return \Illuminate\Database\Capsule\Manager
	 */
	protected function bootEloquent(Container $container)
	{
		// Read database settings.
		$default = $container['config']['database.default'];
		$connections = $container['config']['database.connections'];

		// Setup database manager.
		$capsule = new Manager();
		foreach ($connections as $name => $connection) {
			$name = ($name !== $default) ? $name : 'default';
			$capsule->addConnection($connection, $name);
		}
		$capsule->setAsGlobal();
		$capsule->bootEloquent();
		
		return $capsule;
	}
}