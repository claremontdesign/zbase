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
	'enable' => function(){
		return zbase_config_get('modules.account.widgets.username.enable', true);
	},
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
			'username' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update Username'
			]
		],
		'form' => [
			'startTag' => [
				'action' => function(){
					return zbase_url_from_route('admin.users', ['action' => 'username', 'id' => zbase_route_input('id')]);
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
			'username' => [
				'type' => 'text',
				'id' => 'username',
				'enable' => function(){
					return zbase_config_get('auth.username.enable', false);
						},
				'label' => 'Username',
				'entity' => [
					'property' => 'username'
				],
				'angular' => [
					'ngModel' => 'currentUser.username',
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Username is required.'
					],
					'unique' => [
						'enable' => true,
						'text' => function(){
							return 'unique:' . zbase_entity('user')->getTable() . ',username,' . zbase_auth_user()->id() . ',user_id';
								},
						'message' => 'Username already exists.'
					],
					'regex' => [
						'enable' => true,
						'text' => function(){
							return 'regex:/^[a-z][a-z0-9]{5,31}$/';
								},
						'message' => 'Invalid username.'
					],
					'min' => [
						'enable' => true,
						'text' => function(){
							return 'min:5';
								},
						'message' => 'Username should be of 5 up to 32 characters.'
					],
					'max' => [
						'enable' => true,
						'text' => function(){
							return 'max:32';
								},
						'message' => 'Username should be of 5 up to 32 characters.'
					],
					'not_in' => [
						'enable' => true,
						'text' => function(){
							$notAllowedUsernames = require zbase_path_library('notallowedusernames.php');
							$notAllowedUsernames[] = zbase_auth_user()->username();
							return 'not_in:' . implode(',', $notAllowedUsernames);
								},
						'message' => 'Please provide a different username.'
					],
				],
			],
		],
	],
];
