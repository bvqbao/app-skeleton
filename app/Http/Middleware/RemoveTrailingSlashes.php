<?php

namespace Http\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RemoveTrailingSlashes {

	/**
	 * Remove all trailing slashes from the request uri.
	 * 
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  callable $next
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, callable $next) {
	    $uri = $request->getUri();
	    $path = $uri->getPath();
	    if ($path != '/' && substr($path, -1) == '/') {
	        $uri = $uri->withPath(rtrim($path, '/'));
	        $request = $request->withUri($uri);
	    }

	    return $next($request, $response);
	}
}
