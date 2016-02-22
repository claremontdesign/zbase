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
		$middleware = 'web';
		$children = !empty($route['children']) ? $route['children'] : false;
		$httpVerb = !empty($route['httpVerb']) ? $route['httpVerb'] : ['get'];
		if(!empty($route['form']['enable']))
		{
			$httpVerb[] = 'post';
		}
		foreach ($httpVerb as $verb)
		{
			switch ($verb)
			{
				case 'post':
					\Route::post($url, ['as' => $name, 'middleware' => $middleware, function() use ($route, $name){
							return zbase_route_response($name, $route);
					}]);
					break;
				default;
					\Route::get($url, ['as' => $name, 'middleware' => $middleware, function() use ($route, $name){
							return zbase_route_response($name, $route);
					}]);
			}
		}
	}
}