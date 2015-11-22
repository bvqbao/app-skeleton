<?php

/** SMVC directory. */
define('SMVC', realpath('../').DIRECTORY_SEPARATOR);

/** Load autoloader. */
require SMVC.'vendor/autoload.php';

/** Create an application. */
$app = new \Core\Application(new \Slim\Container([
	// THIS SETTING SHOULB BE USED FOR DEVELOPMENT ONLY
    'settings' => ['displayErrorDetails' => true]
]));

/** Load defined routes. */
require SMVC.'app/routes/welcome.php';

/* Run the application. */
$app->bootstrap()->run();
