<?php

/**
 * Controller configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file controller.php
 * @project Zbase
 * @package config
 */
return [
	'controller' => [
		'class' => [
			'backend' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\BackendController::class,
				'enable' => true
			],
			'backendModule' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\BackendModuleController::class,
				'enable' => true
			],
			'page' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\PageController::class,
				'enable' => true
			],
			'pageModule' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\PageModuleController::class,
				'enable' => true
			],
			'node' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\NodeController::class,
				'enable' => true
			],
			'auth' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\Auth\AuthController::class,
				'enable' => true
			],
			'password' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\Auth\PasswordController::class,
				'enable' => true
			],
		],
	],
];
