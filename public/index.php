<?php

/** Define the root-relative path. */
define('DIR', '/');

/** Register autoloader. */
require '../vendor/autoload.php';

/** Start sessions. */
define('SESSION_PREFIX', '');
\Helpers\Session::init();

/** Create an application. */
$app = new \Core\Application(
	realpath('../').DIRECTORY_SEPARATOR
);

/** Load defined routes here. */
require '../app/routes/welcome.php';

/** Bootstrap and run the application. */
$app->run();
