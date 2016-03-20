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
	'id' => 'nodes',
	'enable' => true,
	'access' => 'admin',
	'class' => null,
	'backend' => true,
	'frontend' => true,
	'url' => [
		'backend' => 'nodes/{action?}/{id?}/{task?}',
		'frontend' => 'nodes/{task?}',
	],
	'controller' => [
		'back' => [
			'action' => [
				'index' => [
					'page' => [
						'title' => 'Nodes',
						'headTitle' => 'Nodes',
						'subTitle' => 'Manage nodes',
						'breadcrumbs' => [
							['label' => 'Nodes', 'link' => '#'],
						],
					],
				],
				'create' => [
					'page' => [
						'title' => 'Create Node',
						'headTitle' => 'Create Node',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Nodes', 'route' => ['name' => 'admin.nodes']],
							['label' => 'Nodes', 'link' => '#'],
						],
					],
				],
				'update' => [
					'page' => [
						'title' => 'Update Node',
						'headTitle' => 'Update Node',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Nodes', 'route' => ['name' => 'admin.nodes']],
							['label' => 'Nodes', 'link' => '#'],
						],
					],
				],
			]
		],
	],
	'widgets' => [
		'controller' => [
			'index' => [
				'nodes' => null
			],
			'create' => [
				'node' => null
			],
			'update' => [
				'node' => null
			],
			'delete' => [
				'node' => null
			],
			'restore' => [
				'node' => null
			],
			'ddelete' => [
				'node' => null
			],
			'view' => [
				'node' => null
			],
		],
	],
];
