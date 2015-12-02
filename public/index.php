<?php

/** The root-relative path to the public folder. */
define('DIR', '/');

/** Register autoloader. */
require '../vendor/autoload.php';

/** Create an application. */
$app = new \Core\Application(
	realpath('../').DIRECTORY_SEPARATOR
);

/** Load defined routes here. */
require '../app/Http/routes.php';

/** Run the application. */
$app->run();
