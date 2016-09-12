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
		'entity' => [
			'name' => 'user',
			'node' => [
				'enable' => true,
			],
			'repo' => [
				'byId' => [
					'route' => 'id'
				]
			],
		],
		'event' => [
			'status' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update Status'
			]
		],
		'form' => [
			'startTag' => [
				'action' => function(){
					return zbase_url_from_route('admin.users', ['action' => 'status', 'id' => zbase_route_input('id')]);
				},
				'html' => [
					'attributes' => [
						'class' => [
							'zbase-ajax-form'
						]
					]
				]
			]
		],
		'elements' => [
			'roleName' => [
				'type' => 'select',
				'id' => 'role',
				'label' => 'Role',
				'multiOptions' => 'userRoles',
				'entity' => [
					'property' => 'roleName'
				],
			],
			'status' => [
				'type' => 'select',
				'id' => 'status',
				'label' => 'Status',
				'multiOptions' => 'userStatus',
				'entity' => [
					'property' => 'status'
				],
			],
		],
	],
];
