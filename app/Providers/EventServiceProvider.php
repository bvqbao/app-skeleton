<?php

namespace App\Providers;

use Illuminate\Events\Dispatcher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Register EventDispatcher into the container.
 */
class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        //
    ];

    /**
     * Register any event services.
     *
     * @param  \Pimple\Container $container
     */
    public function register(Container $container)
    {
        $events = $this->makeDispatcher();
        $container['events'] = function () use ($events) {
            return $events;
        };
    }

    /**
     * Setup and boot EventDispatcher.
     *
     * @return  \Illuminate\Contracts\Events\Dispatcher
     */
    protected function makeDispatcher()
    {
        $events = new Dispatcher();

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }

        return $events;
    }
}
