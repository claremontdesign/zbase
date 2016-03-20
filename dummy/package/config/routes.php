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
 */
return [
	'viewFile' => [
		'pageByViewFile' => [
			'view' => [
				'name' => zbase_view_file('index.viewfile'),
				'enable' => true,
				'page' => [
					'title' => 'Nodes',
					'headTitle' => 'Nodes',
					'subTitle' => 'Manage nodes',
					'breadcrumbs' => [
						['label' => 'Nodes', 'link' => '#'],
					],
				],
			],
			'url' => '/view',
			'enable' => true
		],
		'controllerMethod' => [
			'controller' => [
				'name' => 'page',
				'method' => 'index',
				'enable' => true
			],
			'url' => '/controller',
			'enable' => false
		],
	],
];
