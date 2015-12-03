<?php

/*
|--------------------------------------------------------------------------
| Application Middleware
|--------------------------------------------------------------------------
| 
| The application's global HTTP middleware stack.
| 
*/

$app->add(new Http\Middleware\RemoveTrailingSlashes());

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| You can use a Closure or a controller method (class:method) to handle a route.
|
*/

$app->get('/', 'Http\Controllers\Welcome:welcome');
$app->get('/subpage', 'Http\Controllers\Welcome:subpage');
