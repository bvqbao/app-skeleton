<?php

/*
|--------------------------------------------------------------------------
| Application Middleware
|--------------------------------------------------------------------------
| 
| The application's global HTTP middleware stack.
| 
*/

$app->add(new App\Http\Middleware\RemoveTrailingSlashes());

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| You can use a Closure or a controller method (class:method) to handle a route.
|
*/

$app->get('/', 'App\Http\Controllers\Welcome:welcome');
$app->get('/subpage', 'App\Http\Controllers\Welcome:subpage');
