<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Register a translation service into the container.
 */
class TranslationServiceProvider implements ServiceProviderInterface
{
    /**
     * Register any translation services.
     *
     * @param  \Pimple\Container $container
     */
    public function register(Container $container)
    {
        $this->registerLoader($container);

        $container['translator'] = function () use ($container) {
            $config = $container['config'];

            $translator = new Translator($container['translation.loader'],
                $config['app.locale']);

            $translator->setFallback($config['app.fallback_locale']);

            return $translator;
        };
    }

    /**
     * Register a language file loader used by the translation service.
     *
     * @param  \Pimple\Container $container
     */
    protected function registerLoader(Container $container)
    {
        $container['translation.loader'] = function () use ($container) {
            return new FileLoader(new Filesystem(), $container['path.lang']);
        };
    }
}
