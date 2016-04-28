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
 * routes.home.method = defaultMethod
 * routes.home.method.post = postMethod
 * routes.home.method.get = getMethod
 * routes.home.form.enable = true|false
 * routes.home.url = /
 * routes.home.params = []
 * routes.home.middleware =
 * routes.home.navIndex = The Index name relative to nav.front.main or nav.main
 * routes.home.middleware.guest = true|false,
 * routes.home.middleware.guestOnly = true|false,
 * routes.home.middleware.auth = true|false,
 * routes.home.middleware.admin = Admin only
 * routes.home.middleware.access = If this !empty(), then, user will be check if he has this access/role. Specific access check zbase_auth_is($access)
 * routes.home.enable = true|false
 * routes.home.httpverb = [get,post, put, patch, delete, options]
 * routes.home.children = child routes.
 * routes.home.backend = true|false, if to be loaded on backend
 * routes.adminkey
 * routes.adminkey.enable = FALSE, should always be false, so system will not process this
 * routes.adminkey.key = admin base URL e.g. domain.com/admin or domain.com/zadamin; default is admin
 */
return [
	'routes' => [
		'adminkey' => [
			'enable' => false,
			'key' => 'admin'
		],
		'admin' => [
			'controller' => [
				'name' => 'backend',
				'method' => 'index',
				'enable' => true
			],
			'url' => '/admin',
			'middleware' => [
				'admin' => true,
			],
			'enable' => true,
			'backend' => true,
			'children' => [
				'login' => [
					'controller' => [
						'name' => 'auth',
						'method' => 'login',
						'enable' => true
					],
					'middleware' => [
						'guestOnly' => true,
					],
					'form' => [
						'enable' => true
					],
					'backend' => true,
					'enable' => true,
				],
				'logout' => [
					'controller' => [
						'name' => 'auth',
						'method' => 'logout',
						'enable' => true
					],
					'middleware' => [
						'auth' => true,
					],
					'backend' => true,
					'enable' => true,
				],
			],
		],
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
			'page' => [
				'title' => null,
				'headTitle' => 'Home',
				'subTitle' => null
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
			'page' => [
				'title' => 'Login',
				'headTitle' => 'Login',
				'subTitle' => null,
				'breadcrumbs' => [
					['label' => 'Login', 'link' => '#'],
				],
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
		'logout' => [
			'controller' => [
				'name' => 'auth',
				'method' => 'logout',
				'enable' => true
			],
			'url' => '/logout',
			'middleware' => [
				'auth' => true,
			],
			'enable' => true
		],
		'register' => [
			'controller' => [
				'name' => 'auth',
				'method' => 'register',
				'enable' => true
			],
			'page' => [
				'title' => 'Register',
				'headTitle' => 'Register',
				'subTitle' => null,
				'breadcrumbs' => [
					['label' => 'Register', 'link' => '#'],
				],
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
			'page' => [
				'title' => 'Reset Password',
				'headTitle' => 'Reset Password',
				'subTitle' => null,
				'breadcrumbs' => [
					['label' => 'Reset Password', 'link' => '#'],
				],
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
			'page' => [
				'title' => 'Reset Password',
				'headTitle' => 'Reset Password',
				'subTitle' => null,
				'breadcrumbs' => [
					['label' => 'Reset Password', 'link' => '#'],
				],
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
		'contact' => [
			'controller' => [
				'name' => 'page',
				'method' => 'contact',
				'enable' => true
			],
			'form' => [
				'enable' => true
			],
			'url' => '/contact-us',
			'enable' => true,
			'page' => [
				'title' => 'Contact Us',
				'headTitle' => 'Contact Us',
				'subTitle' => null,
				'breadcrumbs' => [
					['label' => 'Contact Us', 'link' => '#'],
				],
			],
		],
		'nodeImage' => [
			'controller' => [
				'name' => 'node',
				'method' => 'image',
				'enable' => true
			],
			'url' => '/img/{node?}/{id?}/{w?}/{h?}/{q?}',
			'enable' => true
		],
		'nodeCategoryImage' => [
			'controller' => [
				'name' => 'node',
				'method' => 'imageCategory',
				'enable' => true
			],
			'url' => '/img-category/{node?}/{id?}/{w?}/{h?}/{q?}',
			'enable' => true
		],
	],
];
