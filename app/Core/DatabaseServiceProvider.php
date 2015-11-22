<?php

namespace Core;

use Illuminate\Database\Capsule\Manager;
use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Helpers\Arr;

class DatabaseServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		// We need to boot Eloquent before registering it 
		// into the container.
		$capsule = $this->bootEloquent();

		$pimple['db'] = function() use ($capsule) {			
			return $capsule->getDatabaseManager();
		};		
	}

	protected function bootEloquent()
	{
		// Read database settings.
		$settings = require SMVC.'app/config/database.php';
		$default = Arr::get($settings, 'default');
		$connections = Arr::get($settings, 'connections');

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