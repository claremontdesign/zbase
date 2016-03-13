<?php

/**
 * Entities configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file entity.php
 * @project Zbase
 * @package config
 *
 */
return [
	'entity' => [
		'node' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Node::class,
			'relations' => [
				'categories' => [
					'entity' => 'node_category',
					'type' => 'manytomany',
					'class' => [
						'method' => 'categories'
					],
					'pivot' => 'nodes_category_pivot',
					'keys' => [
						'local' => 'category_id',
						'foreign' => 'node_id'
					],
				],
			],
			'table' => [
				'name' => 'nodes',
				'primaryKey' => 'node_id',
				'description' => 'Node',
				'timestamp' => true,
				'softDelete' => true,
				'alphaId' => true,
				'optionable' => true,
				'nodeable' => true,
				'sluggable' => true,
				'columns' => []
			]
		],
		'node_category' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Nested::class,
			'relations' => [
				'nodes' => [
					'entity' => 'nodes',
					'type' => 'manytomany',
					'class' => [
						'method' => 'nodes'
					],
					'pivot' => 'nodes_category_pivot',
					'keys' => [
						'local' => 'node_id',
						'foreign' => 'category_id'
					],
				],
			],
			'table' => [
				'name' => 'nodes_category',
				'primaryKey' => 'category_id',
				'description' => 'Node Categories',
				'timestamp' => true,
				'softDelete' => true,
				'alphaId' => true,
				'optionable' => true,
				'nodeable' => true,
				'nesteable' => true,
				'sluggable' => true,
				'columns' => []
			]
		],
		'nodes_category_pivot' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Nested::class,
			'table' => [
				'name' => 'nodes_category_pivot',
				'description' => 'Nodes-Categories Pivot Table',
				'pivotable' => ['entity' => 'node', 'nested' => 'node_category'],
				'orderable' => true,
				'columns' => []
			]
		],
	],
];
