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
 * Check if current route is $name
 * @param string $name The name to test
 * @return boolean
 */
function zbase_route_name_is($name)
{
	return \Request::route()->getName() == $name;
}

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
					$routes = array_merge($routes, $module->getRoutes());
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
								'url' => $module->sectionUrl('backend'),
								'backend' => true,
								'enable' => true
							];
							$routes[$adminKey]['children'][$module->id()] = $adminRoute;
							if($module->isNode())
							{
								$nodes = $module->getNodesSupport();
								if(!empty($nodes))
								{
									foreach ($nodes as $n)
									{
										$adminRoute = [
											'controller' => [
												'name' => 'backendModule',
												'method' => 'index',
												'enable' => true,
												'params' => [
													'module' => $module,
													'node' => $n,
													'nodeNamespace' => $module->nodeNamespace()
												]
											],
											'form' => [
												'enable' => true
											],
											'url' => 'nodes/' . $n . '/' . $module->sectionUrl('backend'),
											'backend' => true,
											'enable' => true
										];
										// var_dump('Module', $module->id(), 'node_' . $module->nodeNamespace() . '_' . $n, 'nodes/' . $n . '/' . $module->sectionUrl('backend'), '===');
										$routes[$adminKey]['children']['node_' . $module->nodeNamespace() . '_' . $n] = $adminRoute;
									}
								}
							}
						}
						if($module->hasFrontend())
						{
							$frontRoute = [
								'controller' => [
									'name' => 'pageModule',
									'method' => 'index',
									'enable' => true,
									'params' => [
										'module' => $module
									]
								],
								'form' => [
									'enable' => true
								],
								'url' => $module->sectionUrl(),
								'enable' => true
							];
							$routes[$module->id()] = $frontRoute;
							if($module->isNode())
							{
								$nodes = $module->getNodesSupport();
								if(!empty($nodes))
								{
									foreach ($nodes as $n)
									{
										$frontRoute = [
											'controller' => [
												'name' => 'pageModule',
												'method' => 'index',
												'enable' => true,
												'params' => [
													'module' => $module,
													'node' => $n,
													'nodeNamespace' => $module->nodeNamespace()
												]
											],
											'form' => [
												'enable' => true
											],
											'url' => 'nodes/' . $n . '/' . $module->sectionUrl(),
											'enable' => true
										];
										$routes['node_' . $module->nodeNamespace() . '_' . $n] = $frontRoute;
									}
								}
							}
						}
					}
				}
			}
		}
	}
	if(!empty($routes))
	{
		$usernameRoute = false;
		if(!empty($routes['usernameroute']))
		{
			$usernameRoute = true;
		}
		foreach ($routes as $name => $route)
		{
			if(!empty($route['url']))
			{
				zbase_route_init($name, $route);
				if(!empty($route['children']))
				{
					$cRoutes = [];
					$uCRoutes = [];
					foreach ($route['children'] as $cName => $cRoute)
					{
						$cRoute['url'] = $route['url'] . '/' . (!empty($cRoute['url']) ? $cRoute['url'] : $cName);
						$cRoutes[$name . '.' . $cName] = $cRoute;
					}
					zbase_routes_init($cRoutes);
				}
			}
		}

		/**
		 * Using Username Route
		 */
		if(!empty($usernameRoute))
		{
			$routeParameterName = zbase_route_username_prefix();
			$usernameRoutePrefix = '/{' . $routeParameterName . '?}';
			$usernameroute = [
				'controller' => [
					'name' => 'user',
					'method' => 'username',
					'enable' => true
				],
				'url' => $usernameRoutePrefix,
				'enable' => true
			];
			zbase_route_init($routeParameterName, $usernameroute);
			foreach ($routes as $name => $route)
			{
				if(!empty($route['url']))
				{
					$route['url'] = str_replace('//', '/', $usernameRoutePrefix . '/' . $route['url']);
					zbase_route_init($routeParameterName . $name, $route);
					if(!empty($route['children']))
					{
						$uCRoutes = [];
						foreach ($route['children'] as $cName => $cRoute)
						{
							$cRoute['url'] = $route['url'] . '/' . (!empty($cRoute['url']) ? $cRoute['url'] : $cName);
							$cRoutes[$routeParameterName . $name . '.' . $cName] = $cRoute;
						}
						zbase_routes_init($cRoutes);
					}
				}
			}
		}
	}
}

/**
 * Return the UsernameRoutePrefix / Username Route Parameter Name
 *
 * @return string
 */
function zbase_route_username_prefix()
{
	return 'usernameroute';
}

/**
 * Check if Username route is valid
 *
 * @return boolean
 */
function zbase_route_username_get()
{
	$username = zbase_route_input(zbase_route_username_prefix(), false);
	if(!empty($username))
	{
		$username = strtolower($username);
		/**
		 * Check if valid username
		 */
		$user = zbase()->entity('user')->repo()->by('username', $username, ['username'])->first();
		if($user instanceof \Zbase\Interfaces\EntityInterface)
		{
			return $username;
		}
	}
	return false;
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
	$middleware = ['web'];
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
//	$defaultController = zbase_config_get('controller.class.default', null);
//	if(!is_null($defaultController))
//	{
//		$defaultRoute = [
//			'controller' => 'default',
//			'url' => zbase_url_path(),
//			'enable' => true
//		];
//		\Route::get(zbase_url_path(), ['as' => 'default', 'middleware' => 'guest', function() use ($defaultRoute, $name){
//				return zbase_route_response($name, $defaultRoute);
//					}]);
//	}
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
	$usernameRoute = zbase_route_username_get();
	$usernameRoutePrefix = zbase_route_username_prefix();
	zbase()->setCurrentRouteName($name);
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
			if(!empty($usernameRoute))
			{
				if((empty(zbase_auth_has()) || !zbase_auth_is('admin')) && $name != $usernameRoutePrefix . 'admin.login')
				{
					return redirect(zbase_url_from_route('admin.login'));
				}
			}
			else
			{
				if((empty(zbase_auth_has()) || !zbase_auth_is('admin')) && $name != 'admin.login')
				{
					return redirect(zbase_url_from_route('admin.login'));
				}
			}
		}
		else
		{
			if(!empty($guestOnly) && zbase_auth_has())
			{
				return redirect(zbase_url_from_route('home'));
			}
			if(!empty($usernameRoute))
			{
				if(!empty($authed) && !zbase_auth_has() && $name != $usernameRoutePrefix . 'login')
				{
					return redirect(zbase_url_from_route('login'));
				}
			}
			else
			{
				if(!empty($authed) && !zbase_auth_has() && $name != 'login')
				{
					return redirect(zbase_url_from_route('login'));
				}
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
		if($view['name'] == 'type.js')
		{
			zbase_response_format_set('javascript');
		}
		return zbase_response(zbase_view_render(zbase_view_file($view['name']), $params));
	}
}
