<?php

namespace Core;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Core\View;

/**
 * NotFound handler.
 */
class NotFound {
    /**
     * Invoke not found handler
     *
     * @param  ServerRequestInterface $request  The most recent Request object
     * @param  ResponseInterface      $response The most recent Response object
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = ['title' => '404', 'error' => 'Oops! Page not found.'];
        $view = View::make('layouts::default', $data);
        $view->nest('body', 'errors.404', $data);    	

        $response->getBody()->write($view);
        
        return $response->withStatus(404);
    }
}