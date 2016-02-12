<?php

/**
 * Routes configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file routes.php
 * @project Zbase
 * @package config
 *
 * routes.home.controller.name = classname
 * routes.home.controller.method = class method
 * routes.home.controller.enable = true|false
 * routes.home.view.name = view name
 * routes.home.view.enable = true|false
 * routes.home.view.layout = true|false, to return with layout
 * routes.home.method = index
 * routes.home.form.enable = true|false
 * routes.home.url = /
 * routes.home.params = []
 * routes.home.auth = true|false, authenticated users only
 * routes.home.enable = true|false
 * routes.home.httpverb = [get,post, put, patch, delete, options]
 */
return [
	'routes' => [
		'index' => [
			'controller' => [
				'name' => 'page',
				'method' => 'index',
				'enable' => true
			],
			'url' => '/',
			'enable' => true
		],
		'home' => [
			'controller' => [
				'name' => 'page',
				'method' => 'home',
				'enable' => true
			],
			'url' => '/home',
			'middleware' => [
				'auth' => true
			],
			'enable' => true
		],
		'login' => [
			'controller' => [
				'name' => 'auth',
				'method' => 'login',
				'enable' => true
			],
			'form' => [
				'enable' => true
			],
			'url' => '/login',
			'middleware' => [
				'guestOnly' => true,
			],
			'enable' => true
		],
		'register' => [
			'controller' => [
				'name' => 'auth',
				'method' => 'register',
				'enable' => true
			],
			'form' => [
				'enable' => true
			],
			'url' => '/register',
			'middleware' => [
				'guestOnly' => true,
			],
			'enable' => true
		],
		'password' => [
			'controller' => [
				'name' => 'password',
				'method' => 'index',
				'enable' => true
			],
			'form' => [
				'enable' => true
			],
			'url' => '/password',
			'middleware' => [
				'guestOnly' => true,
			],
			'enable' => true
		],
		'password-reset' => [
			'controller' => [
				'name' => 'password',
				'method' => 'reset',
				'enable' => true
			],
			'form' => [
				'enable' => true
			],
			'url' => '/password/reset/{token?}',
			'middleware' => [
				'guestOnly' => true,
			],
			'enable' => true
		],
		'phpinfo' => [
			'command' => function(){
				return phpinfo();
			},
			'url' => '/phpinfo',
			'enable' => true
		],
		'testParams' => [
			'controller' => [
				'name' => 'page',
				'method' => 'index',
				'enable' => true
			],
			'url' => '/testparams/{paramOne?}/{paramTwo?}',
			'enable' => true
		],
		'testForm' => [
			'controller' => [
				'name' => 'page',
				'method' => 'form',
				'enable' => true
			],
			'form' => [
				'enable' => true
			],
			'url' => '/tests/form',
			'enable' => true
		],
		'viewroute' => [
			'view' => [
				'name' => 'viewroute',
				'enable' => true
			],
			'url' => '/tests/view-route',
			'enable' => true
		],
		'rout-auth' => [
			'view' => [
				'name' => 'route-auth',
				'enable' => true
			],
			'url' => '/tests/route-auth',
			'middleware' => [
				'auth' => true
			],
			'enable' => true
		],
	],
];
