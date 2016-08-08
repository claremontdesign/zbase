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
//		'submit' => [
//			'button' => [
//				'html' => [
//					'attributes' => [
//						'angular' => [
//							'ng-controller' => 'adminAccountMainController',
//						]
//					],
//				],
//			]
//		],
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
//		'event' => [
//			'front' => [
//				'index' => [
//					'post' => [
//						'post' => [
//							'route' => [
//								'name' => 'account',
//							]
//						]
//					],
//				],
//			],
//		],
		'tabs' => [
			'account' => [
				'type' => 'tab',
				'label' => 'Profile',
				'id' => 'profile',
				'group' => 'accountTab',
				'enable' => true,
				'elements' => [
					'first_name' => [
						'type' => 'text',
						'id' => 'first_name',
						'enable' => true,
						'label' => 'First Name',
						'angular' => [
							'ngModel' => [
								'prefix' => 'currentUser.profile'
							],
						],
						'entity' => [
							'property' => 'first_name'
						]
					],
					'last_name' => [
						'type' => 'text',
						'id' => 'last_name',
						'enable' => true,
						'label' => 'Last Name',
						'angular' => [
							'ngModel' => [
								'prefix' => 'currentUser.profile'
							],
						],
						'entity' => [
							'property' => 'last_name'
						]
					],
				],
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
							'not_in' => [
								'enable' => true,
								'text' => function(){
									return 'not_in:' . zbase_auth_user()->username;
								},
								'message' => 'Please provide a different username.'
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
						'angular' => [
							'ngModel' => 'currentUser.email',
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
							'not_in' => [
								'enable' => true,
								'text' => function(){
									return 'not_in:' . zbase_auth_user()->email;
								},
								'message' => 'Please provide a different email address.'
							],
						],
					],
//					'account_password' => [
//						'prefix' => 'email_',
//						'widget' => 'accountConfirm',
//					],
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
						'angular' => [
							'ngModel' => 'currentUser.password',
						],
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
						'angular' => [
							'ngModel' => 'currentUser.passwordConfirm',
						],
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'Please verify new password.'
							],
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
//					'account_password' => [
//						'prefix' => 'password_',
//						'widget' => 'accountConfirm',
//					],
				],
			],
			'images' => [
				'type' => 'tab',
				'label' => 'Profile Image',
				'id' => 'images',
				'group' => 'accountTab',
				'enable' => true,
				'formConfiguration' => [
					'angular' => [
						'form' => [
							'startTag' => [
								'html' => [
									'attributes' => [
										'ng-controller' => 'adminAccountMainController',
										'flow-init' => function(){return '{headers:{\'X-CSRF-TOKEN\': \''.  zbase_csrf_token().'\'}, target: \''.  zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updateProfileImage']) . '\'}'; },
										'flow-files-submitted' => '$flow.upload();',
									],
								],
							],
						],
						'submit' => [
							'button' => [
								'enable' => false,
							],
						],
					],
				],
				'elements' => [
					'file' => [
						'type' => 'file',
						'id' => 'file',
						'label' => 'Update Image',
						'action' => function(){
							return zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updateProfileImage']);
						},
						'entity' => [
							'property' => 'file',
						],
						'html' => [
							'attributes' => [
								'input' => [
									'style' => 'width: 100px;'
								],
							],
							'content' => [
								'pre' => [
									'enable' => true,
									'view' => zbase_view_file_contents('node.files.files')
								]
							],
						],
					],
				]
			],
		],
	],
];
