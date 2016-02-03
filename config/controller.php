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
			'page' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\PageController::class,
				'enable' => true
			],
			'auth' => [
				'name' => Zbase\Http\Controllers\__FRAMEWORK__\Auth\AuthController::class,
				'enable' => true
			],
		],
	],
];
