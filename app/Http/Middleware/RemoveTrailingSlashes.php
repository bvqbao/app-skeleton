<?php

namespace Http\Middleware;

use Core\Middleware;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RemoveTrailingSlashes extends Middleware 
{
	/**
	 * Remove all trailing slashes from the request uri.
	 * 
	 * @param  \Psr\Http\Message\RequestInterface  $request
	 * @param  \Psr\Http\Message\ResponseInterface $response
	 * @param  callable $next
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function handle(Request $request, Response $response, callable $next) {
	    $uri = $request->getUri();
	    $path = $uri->getPath();
	    if ($path != '/' and substr($path, -1) == '/') {
	        $uri = $uri->withPath(rtrim($path, '/'));
	        $request = $request->withUri($uri);
	    }

	    return $next($request, $response);
	}
}
