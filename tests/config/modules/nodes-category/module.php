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
	'id' => 'nodes-category',
	'enable' => true,
	'access' => 'admin',
	'class' => null,
	'backend' => true,
	'frontend' => true,
	'url' => [
		'backend' => 'nodes-category/{action?}/{id?}/{task?}',
		'frontend' => 'nodes-category/{task?}',
	],
	'controller' => [
		'back' => [
			'action' => [
				'index' => [
					'page' => [
						'title' => 'Nodes Category',
						'headTitle' => 'Nodes Category',
						'subTitle' => 'Manage nodes category',
						'breadcrumbs' => [
							['label' => 'Nodes Category', 'link' => '#'],
						]
					]
				],
				'create' => [
					'page' => [
						'title' => 'Create',
						'headTitle' => 'Create Category',
						'subTitle' => 'Create a category',
						'breadcrumbs' => [
							['label' => 'Nodes Category', 'route' => ['name' => 'admin.nodes-category']],
							['label' => 'Create', 'link' => '#'],
						]
					]
				],
				'update' => [
					'page' => [
						'title' => 'Update',
						'headTitle' => 'Update Category',
						'subTitle' => 'Update a category',
						'breadcrumbs' => [
							['label' => 'Nodes Category', 'route' => ['name' => 'admin.nodes-category','params' => ['action' => 'index']]],
							['label' => 'Update', 'link' => '#'],
						]
					]
				],
				'delete' => [],
				'restore' => [],
				'ddelete' => [],
				'view' => [],
				'move' => [],
			]
		],
	],
	'widgets' => [
		'controller' => [
			'index' => [
				'nodes-category' => null
			],
			'create' => [
				'node-category' => null,
			],
			'update' => [
				'node-category' => null
			],
			'delete' => [
				'node-category' => null
			],
			'restore' => [
				'node-category' => null
			],
			'ddelete' => [
				'node-category' => null
			],
			'view' => [
				'node-category' => null
			],
			'move' => [
				'node-category' => null
			],
		],
	],
];
