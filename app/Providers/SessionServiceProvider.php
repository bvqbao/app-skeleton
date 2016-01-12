<?php

namespace App\Providers;

use Aura\Session\Session;
use Aura\Session\SessionFactory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SessionServiceProvider implements ServiceProviderInterface
{
    /**
     * Register any session services.
     *
     * @param  \Pimple\Container $container
     */
    public function register(Container $container)
    {
        $this->registerSessionManager($container);

        $container['session'] = function () use ($container){
            $config = $container['config'];
            $sessionManager = $container['sessionManager'];
            $segment = $sessionManager->getSegment($config['session.segment']);

            return $segment;
        };
    }

    /**
     * Register session manager.
     *
     * @param  \Pimple\Container $container
     * @return \Aura\Session\Session
     */
    protected function registerSessionManager($container)
    {
        $container['sessionManager'] = function () use ($container) {
            $sessionFactory = new SessionFactory();

            $session = $sessionFactory->newInstance($_COOKIE);

            $config = $container['config'];
            $session->setCookieParams([
                'lifetime' => $config['session.lifetime'] * 60,
                'path' => $config['session.path'],
                'domain' => $config['session.domain'],
                'secure' => $config['session.secure'],
            ]);

            return $session;
        };
    }
}
