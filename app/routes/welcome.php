<?php
/**
 * Welcome routes.
 */

use Core\View;
use Helpers\Facades\Lang;

$app->get('/', function($request, $response, $args) {
    $data = [
        'title' => Lang::get('welcome.welcome_text'),
        'welcomeMessage' => Lang::get('welcome.welcome_message')
    ];
    $view = View::make('layouts::default', $data);
    $view->nest('body', 'welcome.welcome', $data);

    return $response->write($view);
});

$app->get('/subpage', function($request, $response, $args) {
    $data = [
        'title' => Lang::get('welcome.subpage_text'),
        'welcomeMessage' => Lang::get('welcome.subpage_message')
    ];
    $view = View::make('layouts::default', $data);
    $view->nest('body', 'welcome.subpage', $data);

    return $response->write($view);
});
