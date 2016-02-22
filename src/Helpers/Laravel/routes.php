<?php

/**
 * Zbase-Laravel Helpers-Routes
 *
 * Functions and Helpers for Accessing Routes
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file routes.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Retrieve a route-parameter value
 *
 * @param string $key
 * @return string
 */
function zbase_route_input($key)
{
	return \Route::current()->parameter($key);
}

/**
 * Return all route parameters
 * @return array
 */
function zbase_route_inputs()
{
	return \Route::current()->parameters();
}

/**
 * Create a route
 * @param string $name The Route Name
 * @param array $route The Route configuration
 * @return Response
 */
function zbase_route_response($name, $route)
{
	if(!empty(zbase_is_maintenance()))
	{
		return zbase_response(view(zbase_view_file('maintenance')));
	}
	$guest = true;
	$authed = false;
	$guestOnly = false;
	$middleware = !empty($route['middleware']) ? $route['middleware'] : false;
	if(!empty($middleware))
	{
		if(is_array($middleware))
		{
			$guest = isset($middleware['guest']) ? $middleware['guest'] : false;
			$authed = isset($middleware['auth']) ? $middleware['auth'] : false;
			$guestOnly = isset($middleware['guestOnly']) ? $middleware['guestOnly'] : false;
		}
	}
	if(!empty($guestOnly) && zbase_auth_has())
	{
		return redirect(zbase_url_from_route('home'));
	}
	if(!empty($authed) && !zbase_auth_has())
	{
		return redirect(zbase_url_from_route('login'));
	}
	$params = zbase_route_inputs();
	$requestMethod = zbase_request_method();
	$controller = !empty($route['controller']) ? $route['controller'] : null;
	$command = !empty($route['command']) ? $route['command'] : false;
	if(!empty($command) && $command instanceof \Closure)
	{
		$command();
		exit();
	}
	if(!empty($controller) && !empty($controller['name']) && !empty($route['controller']['enable']))
	{
		$controllerName = !empty($route['controller']['name']) ? $route['controller']['name'] : null;
		$controllerMethod = !empty($route['controller']['method'][$requestMethod]) ? $route['controller']['method'][$requestMethod] : (!empty($route['controller']['method']) ? $route['controller']['method'] : 'index');
		if(!empty($controllerName))
		{
			$controllerConfig = zbase_config_get('controller.class.' . $controllerName, null);
			if(!empty($controllerConfig) && !empty($controllerConfig['enable']))
			{
				$controllerClass = zbase_controller_create_name(zbase_config_get('controller.class.' . $controllerName . '.name', Zbase\Http\Controllers\__FRAMEWORK__\PageController::class));
				$controllerObject = zbase_object_factory($controllerClass, !empty($route['params']) ? $route['params'] : []);
				zbase()->setController($controllerObject->setName($controllerName)->setActionName($controllerMethod)->setRouteParameters($params));
				zbase()->setCurrentRouteName($name);
				zbase_view_page_details($route);
				return zbase_response($controllerObject->$controllerMethod());
			}
		}
	}
	$view = !empty($route['view']) ? $route['view'] : null;
	if(!empty($view) && !empty($view['name']) && !empty($route['view']['enable']))
	{
		zbase_view_page_details($route);
		return zbase_response(zbase_view_render($view['name'], $params));
	}
}
