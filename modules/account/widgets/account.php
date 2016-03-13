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
	/**
	 * string|array
	 * 	string: minimum|admin
	 *  array: [admin, user]
	 * Who has access.
	 * minimum|role name
	 * minimum is the minimum role for the current section, else a role name or array of role names
	 */
	/**
	 * Widget configuration
	 */
	'config' => [
		/**
		 * Form on each tab
		 */
		'form_tab' => false,
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
			'name' => 'user',
			'method' => 'currentUser',
			'repo' => [
				'method' => 'currentUser',
			],
		],
		/**
		 * controller
		 * controller.post.actionName
		 * controller.post.actionName.entity.method = The method to call on the entity
		 * widget()->controller(actionName);
		 */
		'controller' => [
			'post' => [
				'index' => [
					'entity' => [
						'method' => 'updateAccount'
					],
				],
			],
		],
		'tabs' => [
			'account' => [
				'type' => 'tab',
				'label' => 'Profile',
				'id' => 'profile',
				'group' => 'accountTab',
				'enable' => false,
			],
			'email' => [
				'type' => 'tab',
				'label' => 'Email Address',
				'id' => 'email',
				'group' => 'accountTab',
				'enable' => true,
				'elements' => [
					'username' => [
						'type' => 'text',
						'id' => 'username',
						'label' => 'Username',
						'entity' => [
							'property' => 'username'
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
						],
					],
					'email' => [
						'type' => 'email',
						'id' => 'email',
						'label' => 'Email Address',
						'entity' => [
							'property' => 'email'
						],
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'Email address is required.'
							],
							'unique' => [
								'enable' => true,
								'text' => function(){
									return 'unique:' . zbase_entity('user')->getTable() . ',email,' . zbase_auth_user()->id() . ',user_id';
								},
								'message' => 'Email address already exists.'
							],
						],
					],
					'account_password' => [
						'widget' => 'accountConfirm',
					],
				],
			],
			'password' => [
				'type' => 'tab',
				'label' => 'Password',
				'id' => 'password',
				'group' => 'accountTab',
				'enable' => true,
				'elements' => [
					'password' => [
						'type' => 'password',
						'id' => 'password',
						'label' => 'New Password',
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'A new password is required.'
							],
							'min' => [
								'enable' => true,
								'text' => 'min:6',
								'message' => 'Password too short.'
							],
							'passwordStrengthCheck' => [
								'enable' => true,
								'message' => 'Password is too weak.'
							],
						],
					],
					'password_confirm' => [
						'type' => 'password',
						'id' => 'password_confirm',
						'label' => 'Confirm New Password',
						'validations' => [
							'required_with' => [
								'enable' => true,
								'text' => 'required_with:password',
								'message' => 'Please verify new password.'
							],
							'same' => [
								'enable' => true,
								'text' => 'same:password',
								'message' => 'New passwords are not the same.'
							],
						],
					],
					'account_password' => [
						'widget' => 'accountConfirm',
					],
				],
			],
		],
	],
];
