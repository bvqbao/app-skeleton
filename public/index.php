<?php

/** SMVC directory. */
define('SMVC', realpath('../').DIRECTORY_SEPARATOR);

/** Define the root-relative path. */
define('DIR', '/');

/** Register autoloader. */
require SMVC.'vendor/autoload.php';

/** Start sessions. */
define('SESSION_PREFIX', '');
\Helpers\Session::init();

/** Create an application. */
$app = new \Core\Application();

/** Load defined routes here. */
require SMVC.'app/routes/welcome.php';

/** Bootstrap and run the application. */
$app->bootstrap()->run();
