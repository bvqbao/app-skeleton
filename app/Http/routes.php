<?php
/**
 * We can use a Closure or a controller method (class:method) to handle a route.
 */

/*
|--------------------------------------------------------------------------
| Welcome Pages
|--------------------------------------------------------------------------
*/

$app->get('/', 'Http\Controllers\Welcome:welcome');
$app->get('/subpage', 'Http\Controllers\Welcome:subpage');
