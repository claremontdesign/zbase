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
	return zbase_route_name() == $name;
}

/**
 * Return the RouteName
 * @return string
 */
function zbase_route_name()
{
	return \Request::route()->getName();
}

/**
 * Retrieve a route-parameter value
 *
 * @param string $key
 * @return string
 */
function zbase_route_input($key, $default = null)
{
	$routing = \Route::current();
	if($routing instanceof \Illuminate\Routing\Route)
	{
		return $routing->parameter($key);
	}
	return $default;
}

/**
 * Return all route parameters
 * @return array
 */
function zbase_route_inputs()
{
	$routing = \Route::current();
	if($routing instanceof \Illuminate\Routing\Route)
	{
		return $routing->parameters();
	}
	return [];
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
		$adminKey = zbase_admin_key();
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
						$routes = array_merge($routes, $module->getRoutes());
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

						/**
						 * Module Defined Routes
						 */
						// $routes = array_merge($routes, $module->getRoutes());
					}
				}
			}
		}
	}
	if(!empty($routes))
	{
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
		$usernameRoute = zbase_route_username();
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
					'enable' => true,
				],
				'url' => $usernameRoutePrefix,
				'enable' => true
			];
			zbase_route_init($routeParameterName, $usernameroute);
			foreach ($routes as $name => $route)
			{
				$usernameRouteEnable = isset($route['usernameroute']) ? $route['usernameroute'] : true;
				if(!empty($route['url']) && !empty($usernameRouteEnable))
				{
					$route['url'] = str_replace('//', '/', $usernameRoutePrefix . '/' . $route['url']);
					$route['url'] = str_replace('{' . $routeParameterName . '?}/{' . $routeParameterName . '?}', '{' . $routeParameterName . '?}', $route['url']);
					$route['controller']['params'][$routeParameterName] = null;
					$routeName = $routeParameterName . $name;
					$routeName = str_replace($routeParameterName . $routeParameterName, $routeParameterName, $routeName);
					zbase_route_init($routeName, $route);
					if(!empty($route['children']))
					{
						$cRoutes = [];
						foreach ($route['children'] as $cName => $cRoute)
						{
							$cRoute['url'] = $route['url'] . '/' . (!empty($cRoute['url']) ? $cRoute['url'] : $cName);
							$cRoute['url'] = str_replace('{' . $routeParameterName . '?}/{' . $routeParameterName . '?}', '{' . $routeParameterName . '?}', $cRoute['url']);
							$cRoute['controller']['params'][$routeParameterName] = null;
							$cRouteName = $routeParameterName . $name . '.' . $cName;
							$cRoutes[$cRouteName] = $cRoute;
						}
						zbase_routes_init($cRoutes);
					}
				}
			}
		}
	}
}

/**
 * Check if we are using a slash-username URL
 * eg. http://domain.com/{username?}/../../
 * @return boolean
 */
function zbase_route_username()
{
	return zbase_config_get('routes.' . zbase_route_username_prefix(), false);
}

/**
 * The minimum role/access
 * when UsernameRoute is enabled in Admin
 *
 * @return string
 */
function zbase_route_username_minimum_access()
{
	return 'user';
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
		$notAllowedUsernames = (array) require_once zbase_path_library('notallowedusernames.php');
		if(in_array($username, $notAllowedUsernames))
		{
			return false;
		}
		/**
		 * Check if valid username
		 */
		$user = zbase_user_by('username', $username);
		if($user instanceof \Zbase\Entity\Laravel\User\User)
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
	$redirect = zbase_value_get($route, 'redirect', false);
	if(!empty($redirect))
	{
		return redirect()->to($redirect);
	}
	/**
	 * If we are using username in routes,
	 * 	we have to check if the username exists in DB.
	 * 	This is checked in zbase_route_username_get()
	 * 	if the zbase_route_username_get() returns false, means
	 * 	that the route is not a username or username didn't exists.
	 * 	Here we check against all other Routes  if the prefix is in our
	 * 	list of routes, if not found, throw NotFoundHttpException
	 */
	$useUsernameRoute = zbase_route_username();
	$usernameRoute = zbase_route_username_get();
	$usernameRouteCheck = zbase_data_get($route, 'usernameRouteCheck', true);
	if(empty($usernameRouteCheck))
	{
		/**
		 * Will not check for username route
		 */
		$useUsernameRoute = false;
	}
	//if($usernameRoute === false && !empty($useUsernameRoute))
	if($usernameRoute === false && !empty($useUsernameRoute))
	{
		$uri = zbase_url_uri();
		$adminKey = zbase_admin_key();
		if(!empty($uri))
		{
			$uriEx = explode('/', $uri);
			if(!empty($uriEx))
			{
				foreach ($uriEx as $uriV)
				{
					if(!empty($uriV))
					{
						/**
						 * If it isn't an admin key, check it against given Routes
						 */
						if($uriV !== $adminKey)
						{
							$routes = zbase_config_get('routes', []);
							if(!empty($routes))
							{
								foreach ($routes as $rName => $r)
								{
									if(!empty($r['enable']) && !empty($r['url']))
									{
										$urlEx = explode('/', $r['url']);
										if(!empty($urlEx))
										{
											foreach ($urlEx as $urlExV)
											{
												if(!empty($urlExV))
												{
													if($uriV == $urlExV)
													{
														/**
														 * Found it, valid URL
														 */
														$validUrlPrefix = true;
													}
													/**
													 * Will deal only with the first not empty value so break it.
													 */
													break;
												}
											}
										}
									}
									if(!empty($validUrlPrefix))
									{
										/**
										 * Found it, break it
										 */
										$name = $rName;
										$route = $r;
										break;
									}
								}
							}
						}
						else
						{
							return redirect(zbase_url_from_route('home'));
						}
						/**
						 * Will deal only with the first not empty value so break it.
						 */
						break;
					}
				}
				if(empty($validUrlPrefix))
				{
					/**
					 * Only if routeName is not the index
					 */
					if($name != 'index')
					{
						// $response = new \Zbase\Exceptions\NotFoundHttpException();
						// return $response->render(zbase_request(), $response);
					}
				}
			}
		}
	}
	$usernameRoutePrefix = zbase_route_username_prefix();
	$originalRouteName = str_replace($usernameRoutePrefix, '', $name);
	zbase()->setCurrentRouteName($name);
	$guest = true;
	$authed = false;
	$guestOnly = false;
	$middleware = !empty($route['middleware']) ? $route['middleware'] : false;
	$backend = !empty($route['backend']) ? $route['backend'] : false;
	if($name == 'password-reset' && zbase_auth_has())
	{
		\Auth::guard()->logout();
		return redirect(zbase_url_from_current());
	}
	if(!empty($backend))
	{
//		zbase_in_back();
	}
	if(preg_match('/\?usernameroute/', zbase_url_uri()) > 0 && !empty($useUsernameRoute) && zbase_auth_has())
	{
		return redirect()->to('/' . zbase_auth_user()->username() . '/home');
	}
	if(!empty($useUsernameRoute) && zbase_auth_has() && $usernameRoute != zbase_auth_user()->username())
	{
		return redirect(zbase_url_from_route($originalRouteName, [$usernameRoutePrefix => zbase_auth_user()->username()]));
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
					zbase_session_set('__loginRedirect', zbase_url_from_current());
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
				/**
				 * If user is loggedIn and this is admin side and this is not logIn page,
				 * redirect to users dashboard.
				 * User can only access his own dashboard via /{usernameroute?}/admin
				 */
				if(zbase_auth_has() && zbase_auth_is(zbase_route_username_minimum_access()) && zbase_is_back() && $usernameRoute != zbase_auth_user()->username())
				{
					return redirect(zbase_url_from_route('admin', [$usernameRoutePrefix => zbase_auth_user()->username]));
				}
				if((empty(zbase_auth_has()) || !zbase_auth_is('user')) && $name != $usernameRoutePrefix . 'admin.login')
				{
					zbase_session_set('__loginRedirect', zbase_url_from_current());
					return redirect(zbase_url_from_route('admin.login'));
				}
			}
			else
			{
				if((empty(zbase_auth_has()) || !zbase_auth_is('admin')) && $name != 'admin.login')
				{
					zbase_session_set('__loginRedirect', zbase_url_from_current());
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
					zbase_session_set('__loginRedirect', zbase_url_from_current());
					return redirect(zbase_url_from_route('login'));
				}
			}
			else
			{
				if(!empty($authed) && !zbase_auth_has() && $name != 'login')
				{
					zbase_session_set('__loginRedirect', zbase_url_from_current());
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
