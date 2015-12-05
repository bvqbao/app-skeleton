<?php

namespace App\Http\Controllers;

use Core\Controller;
use Core\View;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Welcome extends Controller
{
	/**
	 * Render the welcome page.
	 * 
	 * @param  \Psr\Http\Message\ServerRequestInterface $request
	 * @param  \Psr\Http\Message\ResponseInterface $response
	 * @param  array $args
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function welcome(Request $request, Response $response, array $args)
	{
		$translator = $this->get('translator');

	    $data = [
	        'title' => $translator->get('welcome.welcome_text'),
	        'welcomeMessage' => $translator->get('welcome.welcome_message')
	    ];
	    $view = View::make('layouts::default', $data);
	    $view->nest('body', 'welcome.welcome', $data);

	    return $response->write($view);
	}

	/**
	 * Render the subpage page.
	 * 
	 * @param  \Psr\Http\Message\ServerRequestInterface $request
	 * @param  \Psr\Http\Message\ResponseInterface $response
	 * @param  array $args
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function subpage(Request $request, Response $response, array $args)
	{
		$translator = $this->get('translator');

	    $data = [
	        'title' => $translator->get('welcome.subpage_text'),
	        'welcomeMessage' => $translator->get('welcome.subpage_message')
	    ];
	    $view = View::make('layouts::default', $data);
	    $view->nest('body', 'welcome.subpage', $data);

	    return $response->write($view);		
	}
}
