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
	\Core\DatabaseServiceProvider::class,
	\Core\HandlerServiceProvider::class,
	\Core\TranslationServiceProvider::class,
	\Core\ValidationServiceProvider::class,
];
