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
		'access' => [
			'enable' => true,
			'role' => 'admin',
			'noauth' => [
				'route' => 'login',
			]
		],
		'event' => [
			'back' => [
				'delete' => [
					'post' => [
						'route' => [
							'name' => 'admin.equipments',
							'params' => [
								'action' => 'update'
							],
						]
					],
					'post-json' => [
						'data' => [
							'admin-node-file-db' => true
						]
					],
				],
				'update' => [
					'post' => [
						'route' => [
							'name' => 'admin.equipments',
							'params' => [
								'action' => 'update'
							],
						]
					],
					'post-json' => [
						'data' => [
							'admin-node-file-db' => true
						]
					],
				],
				'primary' => [
					'post' => [
						'route' => [
							'name' => 'admin.equipments',
							'params' => [
								'action' => 'primary'
							],
						]
					],
					'post-json' => [
						'data' => [
							'admin-node-file-db' => true
						]
					],
				]
			],
			'front' => [
				'delete' => [
					'post-json' => [
						'data' => [
							'admin-node-file-db' => true
						]
					],
				],
				'update' => [
					'post-json' => [
						'data' => [
							'admin-node-file-db' => true
						]
					],
				],
				'primary' => [
					'post-json' => [
						'data' => [
							'admin-node-file-db' => true
						]
					],
				]
			]
		],
		'entity' => [
			'node' => [
				'enable' => true
			],
			'filter' => ['admin' => true],
			'repo' => [
				'byAlphaId' => [
					'route' => 'id'
				],
			],
		],
	],
];
