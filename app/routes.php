<?php
/**
 * We can use a Closure or a controller method (class:method) to handle a route.
 */

/*
|--------------------------------------------------------------------------
| Welcome Pages
|--------------------------------------------------------------------------
*/

$app->get('/', 'Controllers\Welcome:welcome');
$app->get('/subpage', 'Controllers\Welcome:subpage');
