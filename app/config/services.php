<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Services to Be Registered
    |--------------------------------------------------------------------------
    |
    | Register services used by the application.
    | Each service class must implement the \Pimple\ServiceProviderInterface.
    |
    */
    \Core\Handlers\HandlerServiceProvider::class,
	\Core\Providers\DatabaseServiceProvider::class,
	\Core\Providers\TranslationServiceProvider::class,
	\Core\Providers\ValidationServiceProvider::class
];
