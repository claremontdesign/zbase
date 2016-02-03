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
			'auth' => false,
			'enable' => true
		],
		'home' => [
			'controller' => [
				'name' => 'page',
				'method' => 'index',
				'enable' => true
			],
			'url' => '/home',
			'auth' => false,
			'enable' => true
		],
		'login' => [
			'controller' => [
				'name' => 'auth',
				'method' => 'getLogin',
				'enable' => true
			],
			'url' => '/login',
			'auth' => false,
			'enable' => true
		],
	],
];