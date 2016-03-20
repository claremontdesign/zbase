<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 8, 2016 10:37:59 AM
 * @file widget.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 */
return [
	'type' => 'form',
	'enable' => true,
	'config' => [
		/**
		 * Model configuration
		 * The Current Data to manipulate
		 * entity
		 * entity.name
		 * entity.method = entity->method()
		 * entity.repo = entity->repository() method of an entity will be called
		 * entity.repo.byId = entity->repository()->byId(); ['request' => 'indexName']
		 * widget->controller(actionName)
		 */
		'entity' => [
			'name' => 'node_category',
			'repo' => [
				'byId' => [
					'route' => 'id'
				],
			],
		],
		'tabs' => [
			'general' => [
				'type' => 'tab',
				'label' => 'General',
				'id' => 'general',
				'group' => 'nodeTab',
				'enable' => true,
				'elements' => [
					'parent-category' => [
						'widget' => 'nodes-category',
					],
					'title' => [
						'type' => 'text',
						'id' => 'title',
						'label' => 'Name',
						'entity' => [
							'property' => 'title'
						],
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'Name is required.'
							],
						],
					],
					'status' => [
						'type' => 'radio',
						'id' => 'status',
						'label' => 'Status',
						'multiOptions' => 'publishStatus',
						'entity' => [
							'property' => 'status'
						],
					],
				],
			],
		],
	],
];
