<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Registered Services
    |--------------------------------------------------------------------------
    |
    | Register services which can be used by the application.
    | Each service class must implement the \Pimple\ServiceProviderInterface.
    |
    */
    \Core\Providers\HandlerServiceProvider::class,
	\Core\Providers\DatabaseServiceProvider::class,
	\Core\Providers\TranslationServiceProvider::class,
	\Core\Providers\ValidationServiceProvider::class
];
