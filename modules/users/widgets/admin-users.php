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
	'type' => 'datatable',
	'enable' => true,
	'config' => [
		'queryOnLoad' => false,
		'searchable' => [
			'enable' => true,
			'json' => true,
			'url' => function(){
				return zbase_url_from_route('admin.users');
			},
			'input' => [
				'placeholder' => 'Enter UserID, email address;'
			],
			'onload' => true,
		],
		'row' => [
			'clickable' => [
				'enable' => true,
				'action' => 'view',
			]
		],
		'angular' => [
			'route' => [
				'name' => 'admin.users'
			],
			'controller' => 'adminUsersController',
			'view' => [
				'list' => [
					'type' => 'list',
					'link' => '#/users/view/<% APINAME.id %>',
					'url' => '/users/view/',
				],
				'file' => null,
				'format' => '<span>Email Address</span>: <span><% APINAME.email %></span>',
			]
		],
		'entity' => [
			'name' => 'user',
			'node' => [
				'enable' => true
			],
			'filter' => ['admin' => true],
		],
		'actions' => [
			'view' => [
				'enable' => true,
				'route' => [
					'name' => 'admin.users',
					'params' => ['action' => 'view', 'id' => 'row::user_id']
				],
			],
			'create' => [
				'enable' => false,
				'label' => 'Create new node',
				'route' => [
					'name' => 'admin.users',
					'params' => ['action' => 'create']
				],
			],
			'update' => [
				'enable' => false,
				'route' => [
					'name' => 'admin.users',
					'params' => ['action' => 'update', 'id' => 'row::user_id']
				],
			],
			'delete' => [
				'enable' => false,
				'route' => [
					'name' => 'admin.users',
					'params' => ['action' => 'delete', 'id' => 'row::user_id']
				],
			],
			'restore' => [
				'enable' => false,
				'route' => [
					'name' => 'admin.users',
					'params' => ['action' => 'restore', 'id' => 'row::user_id']
				],
			],
			'ddelete' => [
				'enable' => false,
				'route' => [
					'name' => 'admin.users',
					'params' => ['action' => 'ddelete', 'id' => 'row::user_id']
				],
			],
		],
		'columns' => [
			'user_id' => [
				'label' => 'ID',
				'enable' => true,
				'data' => [
					'type' => 'integer',
					'index' => 'user_id'
				],
			],
			'rolename' => [
				'label' => 'Role',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'displayRoleName'
				],
			],
			'email' => [
				'label' => 'Email Address',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'email'
				],
			],
			'first_name' => [
				'label' => 'First Name',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'profile.first_name'
				],
			],
			'last_name' => [
				'label' => 'Last Name',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'profile.last_name'
				],
			],
			'cityStateCountry' => [
				'label' => 'Location',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'cityStateCountry'
				],
			],
			'status' => [
				'label' => 'Status',
				'enable' => true,
				'data' => [
					'type' => 'userStatus',
					'index' => 'status'
				],
			],
			'created_at' => [
				'label' => 'Created',
				'enable' => true,
				'data' => [
					'type' => 'timestamp',
					'index' => 'created_at'
				],
			],
			'login' => [
				'label' => 'Login',
				'enable' => function(){return zbase_auth_duplex_enable();},
				'data' => [
					'type' => 'string',
					'index' => 'loginAs'
				],
			],
		],
	],
];
