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
			'data' => [
				'events' => [
					'post' => function(){
						for ($x = 0; $x <= 15; $x++)
						{
							$entity = new \Zbase\Entity\Laravel\Node\Node(\Zbase\Entity\Laravel\Node\Node::fakeValue());
							$entity->save();
						}
					}
				],
			],
			'relations' => [
				'user' => [
					'entity' => 'user',
					'type' => 'belongsto',
					'class' => [
						'method' => 'user'
					],
					'keys' => [
						'local' => 'user_id',
						'foreign' => 'user_id'
					],
				],
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
				'files' => [
					'entity' => 'nodes_files',
					'type' => 'onetomany',
					'class' => [
						'method' => 'files'
					],
					'keys' => [
						'local' => 'node_id',
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
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Category::class,
			'data' => [
				'events' => [
					'post' => function(){
						\Zbase\Entity\Laravel\Node\Category::fakeValues();
					}
				],
			],
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
				'alphaId' => true,
				'optionable' => true,
				'nodeable' => true,
				'nesteable' => true,
				'softDelete' => true,
				'sluggable' => true,
				'columns' => []
			]
		],
		'nodes_category_pivot' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Category::class,
			'table' => [
				'name' => 'nodes_category_pivot',
				'description' => 'Nodes-Categories Pivot Table',
				'pivotable' => ['entity' => 'node', 'nested' => 'node_category'],
				'orderable' => true,
				'columns' => []
			]
		],
		'nodes_files' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\File::class,
			'relations' => [
				'node' => [
					'entity' => 'nodes',
					'type' => 'belongsto',
					'class' => [
						'method' => 'node'
					],
					'keys' => [
						'local' => 'node_id',
						'foreign' => 'node_id'
					],
				],
			],
			'table' => [
				'name' => 'nodes_files',
				'description' => 'Nodes Files Table',
				'primaryKey' => 'file_id',
				'timestamp' => true,
				'alphaId' => true,
				'optionable' => true,
				'columns' => function(){
					$className = zbase_class_name('Zbase\Entity\__FRAMEWORK__\Node\File');
					return $className::columns();
				}
			]
		],
	],
];
