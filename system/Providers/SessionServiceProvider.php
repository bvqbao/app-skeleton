<?php

namespace Providers;

use Aura\Session\SessionFactory;
use Aura\Session\Session;
use Aura\Session\Segment;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class SessionServiceProvider implements ServiceProviderInterface
{
	/**
	 * Register any session services.
	 * 
	 * @param  \Pimple\Container $container
	 */		
	public function register(Container $container)
	{
		// Register session instance.
		$session = $this->newSession($container);		
		$container['sessionManager'] = function() use ($session) {
			return $session;
		};

		// Register segment instance.
		$config = $container['config'];
		$segment = $session->getSegment($config['session.segment']);
		$container['session'] = function() use ($segment) {
			return $segment;
		};
	}

	/**
	 * Create a new session instance.
	 * 
	 * @param  \Pimple\Container $container
	 * @return \Aura\Session\Session
	 */
	protected function newSession(Container $container)
	{
		$sessionFactory = new SessionFactory();

		$session = $sessionFactory->newInstance($_COOKIE);

		$config = $container['config'];
		$session->setCookieParams([
			'lifetime' => $config['session.lifetime']*60,
			'path' => $config['session.path'],
			'domain' => $config['session.domain'],
			'secure' => $config['session.secure'],
		]);	

		return $session;		
	}
}