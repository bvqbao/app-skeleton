<?php

/** The root-relative path to the public folder. */
define('DIR', '/');

/** Register autoloader. */
require '../vendor/autoload.php';

/** Start sessions. */
define('SESSION_PREFIX', '');
\Support\Session::init();

/** Create an application. */
$app = new \Core\Application(
	realpath('../').DIRECTORY_SEPARATOR,
	\Core\Container::getInstance()
);

/** Load defined routes here. */
require '../app/routes.php';

/** Run the application. */
$app->run();
