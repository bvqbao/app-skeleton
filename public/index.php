<?php

/** Register autoloader. */
require '../vendor/autoload.php';

/** Create an application. */
$app = new \Core\Application(
	realpath('../').DIRECTORY_SEPARATOR
);

/** Load defined routes here. */
require '../app/routes.php';

/** Run the application. */
$app->run();
