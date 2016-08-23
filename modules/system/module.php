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
					],
					[
						'route' => [
							'name' => 'admin.system',
							'params' => [
								'action' => 'telegram'
							]
						],
						'icon' => 'fa fa-flash',
						'label' => 'Telegram',
						'title' => 'Telegram'
					],
				],
			]
		],
	],
	'controller' => [
		'back' => [
			'action' => [
				'telegram' => [
					'page' => [
						'title' => 'Telegram Integration',
						'headTitle' => 'Telegram Integration',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Telegram Integration'],
						],
					],
				],
				'maintenance' => [
					'page' => [
						'title' => 'System Maintenance',
						'headTitle' => 'System Maintenance',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'System Maintenance'],
						],
					],
				],
				'maintenance-mode-on' => [
					'page' => [
						'title' => 'System Maintenance - WEBSITE IN MAINTENANCE MODE!',
						'headTitle' => 'System Maintenance',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'System Maintenance'],
						],
					],
				],
				'maintenance-mode-off' => [
					'page' => [
						'title' => 'System Maintenance',
						'headTitle' => 'System Maintenance',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'System Maintenance'],
						],
					],
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
					'maintenance' => [
						'system-maintenance-form' => null,
					],
					'telegram' => [
						'telegram-settings-form' => null,
					],
					'post-telegram' => function(){
						zbase()->telegram()->saveSettings(zbase_request_inputs());
						return ['telegram-settings-form' => null];
					},
					'post-maintenance' => function(){
						zbase()->system()->scheduleDowntime(zbase_request_inputs());
						return ['system-maintenance-form' => null];
					},
					'maintenance-mode-on' => function(){
						zbase()->system()->startMaintenance();
						return redirect()->to(zbase_url_previous());
					},
					'maintenance-mode-off' => function(){
						zbase()->system()->stopMaintenance();
						return redirect()->to(zbase_url_previous());
					},
				],
			],
		],
	],
];
