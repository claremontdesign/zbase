<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 5, 2016 11:51:42 PM
 * @file dsstore/module.php
 *
 */
return [
	'id' => 'system',
	'enable' => true,
	'access' => 'only::sudo',
	'class' => null,
	'backend' => true,
	'frontend' => false,
	'url' => [
		'backend' => 'system/{action?}/{id?}/{task?}',
	],
	'navigation' => [
		'back' => [
			'enable' => true,
			'nav' => [
				'order' => 9998,
				'route' => [
					'name' => 'admin.dsorders'
				],
				'icon' => 'fa fa-gears',
				'label' => 'System',
				'title' => 'System',
				'children' => [
					[
						'route' => [
							'name' => 'admin.system',
							'params' => [
								'action' => 'maintenance'
							]
						],
						'icon' => 'fa fa-flash',
						'label' => 'Maintenance',
						'title' => 'Maintenance'
					]
				],
			]
		],
	],
	'routes' => [
	],
	'widgets' => [
		'back' => [
			'controller' => [
				'action' => [
					'index' => [
						'system-maintenance' => null,
					],
				],
			],
		],
	],
];
