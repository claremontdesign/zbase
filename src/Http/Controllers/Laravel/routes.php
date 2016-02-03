<?php

/**
 * Zbase-Laravel Routes
 *
 * Laravel Routes
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file routes.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */
$routes = zbase_config_get(strtolower('routes'));
if(!empty($routes))
{
	foreach ($routes as $name => $route)
	{
		$url = !empty($route['url']) ? $route['url'] : null;
		if(empty($url))
		{
			continue;
		}
		$authOnly = !empty($route['auth']) ? $route['auth'] : false;
		$groups = [];
		$middleware = 'guest';
		if(!empty($authOnly))
		{
			$middleware = ['auth'];
		}
		$groups['middleware'] = $middleware;
		\Route::group($groups, function() use ($route, $name, $url) {
			$httpVerb = !empty($route['httpVerb']) ? $route['httpVerb'] : ['get'];
			if(!empty($route['form']['enable']))
			{
				$httpVerb[] = 'post';
			}
			\Route::match($httpVerb, $url, function() use ($route, $name){
				$params = zbase_route_inputs();
				$controller = !empty($route['controller']) ? $route['controller'] : null;
				if(!empty($controller) && !empty($controller['name']) && !empty($route['controller']['enable']))
				{
					$controllerName = !empty($route['controller']['name']) ? $route['controller']['name'] : null;
					$controllerMethod = !empty($route['controller']['method']) ? $route['controller']['method'] : 'index';
					if(!empty($controllerName))
					{
						$controllerConfig = zbase_config_get('controller.class.' . $controllerName, null);
						if(!empty($controllerConfig) && !empty($controllerConfig['enable']))
						{
							$controllerClass = zbase_controller_create_name(zbase_config_get('controller.class.' . $controllerName . '.name', Zbase\Http\Controllers\__FRAMEWORK__\PageController::class));
							$controllerObject = zbase_object_factory($controllerClass, !empty($route['params']) ? $route['params'] : []);
							zbase()->setController($controllerObject->setName($controllerName)->setActionName($controllerMethod)->setRouteParameters($params));
							zbase()->setCurrentRouteName($name);
							return zbase_response($controllerObject->$controllerMethod());
						}
					}
				}
				$view = !empty($route['view']) ? $route['view'] : null;
				if(!empty($view) && !empty($view['name']) && !empty($route['view']['enable']))
				{
					return zbase_response(zbase_view_render($view['name'], $params));
				}
			})->name($name);
		});
	}
}