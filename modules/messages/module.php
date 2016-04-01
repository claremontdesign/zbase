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
 * @file module.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 */
return [
	'id' => 'messages',
	'enable' => true,
	'access' => 'user',
	'class' => null,
	'backend' => true,
	'frontend' => true,
	'url' => [
		'frontend' => 'dashboard/messages/{action?}/{id?}/{task?}',
	],
	'controller' => [
		'front' => [
			'action' => [
				'index' => [
					'page' => [
						'title' => 'Messages',
						'headTitle' => 'Messages',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Dashboard', 'route' => ['name' => 'dashboard-front']],
							['label' => 'Messages'],
						],
					],
				],
				'read' => [
					'page' => [
						'title' => 'Messages',
						'headTitle' => 'Messages',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Dashboard', 'route' => ['name' => 'dashboard-front']],
							['label' => 'Messages', 'route' => ['name' => 'messages']],
							['label' => 'Read'],
						],
					],
				],
			]
		],
	],
	'widgets' => [
		'front' => [
			'controller' => [
				'action' => [
					'index' => [
						'messages-list' => null
					],
					'read' => [
						'message-read' => null,
						'message-reply' => null,
					],
					'json-trash' => [
						'message-trash' => null,
					],
				],
			]
		]
	],
];
