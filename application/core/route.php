<?php

namespace Application\Core;

use Application\Core\Utils\Request;
use Application\Controllers as Controllers;
use Api\Controllers as ApiControllers;

/**
 * 
 * Class Router, routing controllers.
 *
*/
class Route
{
    /**
	 * 
     * Start routing requested uri. 
	 * Taking controller for the future work.
	 * 
     */
	public function start()
	{
		// Setting up main variables.
        $controller['name'] = (!empty(Request::current()->getController())) 
		    ? Request::current()->getController()
			: 'main';

		$action = (!empty(Request::current()->getAction())) 
		    ? Request::current()->getAction()
			: 'index';

		if (ucfirst($controller['name']) == 'Api')
		{
			self::routeAPI($action, Request::current()->getMethod());
			return;
		}

		self::routeWeb($controller, $action);
	}

	public function routeWeb($controller, $action)
	{
		// Paths to files.
		$controller['path'] = dirname(__DIR__) . "/controllers/" . strtolower($controller['name']) . 'Controller.php';

		// Check that file with controller exists. 
		// If not - return a error code 404.
		if (http_response_code() != 200)
		{
		    (new Controllers\ErrorController)->abort(http_response_code());
		}
		else if(is_file($controller['path']))
		{	
			include $controller['path'];

			// Initialize a new class.
			$class = 'Application\\Controllers\\' . ucfirst($controller['name']) . 'Controller';
			$controllerClass = new $class();
			
			// Make an action if method exists and it's callable.
			if(method_exists($controllerClass, $action) && is_callable([$controllerClass, $action]))
				$controllerClass->$action();
			else
				(new Controllers\ErrorController)->abort(404);
		}
		else
			(new Controllers\ErrorController)->abort(404);
	}

	public function routeAPI($controller, $action)
	{
		// Paths to files.
		$controllerPath = Request::current()->server()->value('DOCUMENT_ROOT') . "/api/controllers/" . strtolower($controller) . 'ApiController.php';

		// Check that file with controller exists. 
		// If not - return a error code 404.
		if (http_response_code() != 200)
		{
		    (new ApiControllers\ErrorApiController)->abort(http_response_code());
		}
		else if(is_file($controllerPath))
		{	
			include $controllerPath;

			// Initialize a new class.
			$class = '\\Api\\Controllers\\' . ucfirst($controller) . 'ApiController';
			$controllerClass = new $class();

			$action = (!empty($action)) 
		    ? $action 
			: 'index';
			
			// Make an action if method exists and it's callable.
			if(method_exists($controllerClass, $action) && is_callable([$controllerClass, $action]))
				$controllerClass->$action();
			else
				(new ApiControllers\ErrorApiController)->abort(404);
		}
		else
			(new ApiControllers\ErrorApiController)->abort(404);
	}
}