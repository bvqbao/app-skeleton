<?php
/**
 * Routes - all standard routes are defined here.
 * We can route an URL to a Closure or to a controller method (class:method).
 */

$app->get('/', 'Controllers\Welcome:welcome');
$app->get('/subpage', 'Controllers\Welcome:subpage');
