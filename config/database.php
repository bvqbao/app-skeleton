<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work.
    |
     */
    'default' => 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    |
     */
    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'slimvc',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],

    ],
];
