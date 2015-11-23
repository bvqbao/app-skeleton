<?php

namespace Core;

use Illuminate\Database\Capsule\Manager;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class DatabaseServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		// We need to boot Eloquent before registering it 
		// into the container.
		$capsule = $this->bootEloquent($pimple);

		$pimple['db'] = function() use ($capsule) {			
			return $capsule->getDatabaseManager();
		};		
	}

	protected function bootEloquent(Container $pimple)
	{
		// Read database settings.
		$default = $pimple['config']['database.default'];
		$connections = $pimple['config']['database.connections'];

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