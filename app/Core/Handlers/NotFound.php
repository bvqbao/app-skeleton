<?php

namespace Core\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Body;

/**
 * Application not found handler.
 *
 * It outputs a simple message in either JSON, XML or HTML based on the
 * Accept header.
 */
class NotFound
{
    /**
     * Known handled content types
     *
     * @var array
     */
    protected $knownContentTypes = [
        'application/json',
        'application/xml',
        'text/xml',
        'text/html',
    ];

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

        $contentType = $this->determineContentType($request);
        switch ($contentType) {
            case 'application/json':
                $output = '{"message":"Not found"}';
                break;

            case 'text/xml':
            case 'application/xml':
                $output = '<root><message>Not found</message></root>';
                break;

            case 'text/html':
                $contentType = 'text/html';
                $output = <<<END
<!DOCTYPE html>
<html>
    <head>
        <title>Page Not Found</title>
        <style>
            body{
                margin:0;
                padding:30px;
                font:12px/1.5 Helvetica,Arial,Verdana,sans-serif;
            }
            h1{
                margin:0;
                font-size:36px;
                font-weight:normal;
                line-height:36px;
            }
            strong{
                display:inline-block;
                width:65px;
            }
        </style>
    </head>
    <body>
        <h1>Page Not Found</h1>
        <p>
            The page you are looking for could not be found. Check the address bar
            to ensure your URL is spelled correctly. If all else fails, you can
            go to our website's home page, and navigate to the content in question.
        </p>
    </body>
</html>
END;
                break;
        }

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $response->withStatus(404)
                        ->withHeader('Content-Type', $contentType)
                        ->withBody($body);
    }

    /**
     * Determine which content type we know about is wanted using Accept header
     *
     * @param ServerRequestInterface $request
     * @return string
     */
    private function determineContentType(ServerRequestInterface $request)
    {
        $acceptHeader = $request->getHeaderLine('Accept');
        $selectedContentTypes = array_intersect(explode(',', $acceptHeader), $this->knownContentTypes);

        if (count($selectedContentTypes)) {
            return $selectedContentTypes[0];
        }

        return 'text/html';
    }
}
