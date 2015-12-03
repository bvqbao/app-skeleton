<?php

namespace Core;

use Core\Handlers\RouteHandler;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * The base middleware.
 */
abstract class Middleware extends RouteHandler
{
	/**
	 * __invoke function. Slim requires that a middleware must be a callable.
	 * 
	 * @param  \Psr\Http\Message\RequestInterface  $request
	 * @param  \Psr\Http\Message\ResponseInterface $response
	 * @param  callable $next
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public final function __invoke(Request $request, Response $response, callable $next)
	{
		return $this->handle($request, $response, $next);
	}

	/**
	 * The main logic of the middleware.
	 * 
	 * @param  \Psr\Http\Message\RequestInterface  $request
	 * @param  \Psr\Http\Message\ResponseInterface $response
	 * @param  callable $next
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public abstract function handle(Request $request, Response $response, callable $next);
}