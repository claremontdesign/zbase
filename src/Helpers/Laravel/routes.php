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
 * Run Routing
 * @return void
 */
function zbase_routes_init($routes = null)
{
	if(empty($routes))
	{
		$routes = zbase_config_get('routes');
		/**
		 * Add dynamic routes
		 * for modules
		 */
		$adminKey = zbase_config_get('routes.adminkey.key', 'admin');
		$modules = zbase()->modules();
		if(!empty($modules))
		{
			foreach ($modules as $moduleName => $module)
			{
				$module = zbase()->module($moduleName);
				if($module instanceof \Zbase\Module\ModuleInterface)
				{
					if(empty($routes[$adminKey]['children']))
					{
						$routes[$adminKey]['children'] = [];
					}
					if($module->isEnable())
					{
						if($module->hasBackend())
						{
							$adminRoute = [
								'controller' => [
									'name' => 'backendModule',
									'method' => 'index',
									'enable' => true,
									'params' => [
										'module' => $module
									]
								],
								'form' => [
									'enable' => true
								],
								'url' => $module->url('back'),
								'backend' => true,
								'enable' => true
							];
							$routes[$adminKey]['children'][$module->id()] = $adminRoute;
						}
						if($module->hasFrontend())
						{
							$frontRoute = [
								'controller' => [
									'name' => 'modulePage',
									'method' => 'index',
									'enable' => true,
									'params' => [
										'module' => $module
									]
								],
								'form' => [
									'enable' => true
								],
								'url' => $module->url('front'),
								'enable' => true
							];
							$routes[$module->id()] = $frontRoute;
						}
					}
				}
			}
		}
	}
	if(!empty($routes))
	{
		foreach ($routes as $name => $route)
		{
			zbase_route_init($name, $route);
			if(!empty($route['children']))
			{
				$cRoutes = [];
				foreach ($route['children'] as $cName => $cRoute)
				{
					$cRoute['url'] = $route['url'] . '/' . (!empty($cRoute['url']) ? $cRoute['url'] : $cName);
					$cRoutes[$name . '.' . $cName] = $cRoute;
				}
				zbase_routes_init($cRoutes);
			}
		}
	}
}

/**
 * Initialize a Route
 * @param type $name
 * @param type $route
 * @return type
 */
function zbase_route_init($name, $route)
{
	$url = !empty($route['url']) ? $route['url'] : null;
	if(empty($url))
	{
		return null;
	}
	$middleware = 'web';
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
	$backend = !empty($route['backend']) ? $route['backend'] : false;
	if(!empty($backend))
	{
		zbase_in_back();
	}
	if(!empty($middleware))
	{
		if(is_array($middleware))
		{
			$access = isset($middleware['access']) ? $middleware['access'] : false;
			if(!empty($access) && is_array($access))
			{
				if(!zbase_auth_has())
				{
					return redirect(zbase_url_from_route('login'));
				}
				if(zbase_auth_has() && !zbase_auth_is($access))
				{
					return zbase_abort(401, ucfirst($access) . ' is needed to access the page.');
				}
			}
			else
			{
				$guest = isset($middleware['guest']) ? $middleware['guest'] : false;
				$authed = isset($middleware['auth']) ? $middleware['auth'] : false;
				$adminAuthed = isset($middleware['admin']) ? $middleware['admin'] : false;
				if($adminAuthed)
				{
					$authed = true;
				}
				$guestOnly = isset($middleware['guestOnly']) ? $middleware['guestOnly'] : false;
			}
		}
	}
	if(empty($access))
	{
		if(!empty($backend))
		{
			if((empty(zbase_auth_has()) || !zbase_auth_is('admin')) && $name != 'admin.login')
			{
				return redirect(zbase_url_from_route('admin.login'));
			}
		}
		else
		{
			if(!empty($guestOnly) && zbase_auth_has())
			{
				return redirect(zbase_url_from_route('home'));
			}
			if(!empty($authed) && !zbase_auth_has() && $name != 'login')
			{
				return redirect(zbase_url_from_route('login'));
			}
		}
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
				$controllerObject = zbase_object_factory($controllerClass, !empty($route['controller']['params']) ? $route['controller']['params'] : []);
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
		if(!empty($route['view']['content']))
		{
			$params['content'] = zbase_data_get($route['view']['content'], null);
		}
		return zbase_response(zbase_view_render($view['name'], $params));
	}
}
