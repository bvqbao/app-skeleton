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
			$config = $pimple['config'];

		    $translator = new Translator($pimple['translation.loader'], 
		    	$config['app.locale']);

		    $translator->setFallback($config['app.fallback_locale']);

		    return $translator;    
		};		
	}

	protected function registerLoader(Container $pimple)
	{
		$pimple['translation.loader'] = function() use ($pimple) {
			return new FileLoader(new Filesystem(), $pimple['path.lang']);
		};
	}
}