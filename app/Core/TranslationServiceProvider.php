<?php

namespace Core;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class TranslationServiceProvider implements ServiceProviderInterface
{
	public function register(Container $pimple)
	{
		$this->registerLoader($pimple);
		
		$pimple['translator'] = function() use ($pimple) {
		    $loader = $pimple['translation.loader'];
		    $translator = new Translator($loader, DEFAULT_LOCALE);
		    $translator->setFallback('en');

		    return $translator;    
		};		
	}

	protected function registerLoader(Container $pimple)
	{
		$pimple['translation.loader'] = function() {
			return new FileLoader(new Filesystem(), SMVC.'app/lang');
		};
	}
}