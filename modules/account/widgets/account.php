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
		'event' => [
			'index' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'tabs' => [
			'account' => [
				'type' => 'tab',
				'label' => 'Profile',
				'id' => 'profile',
				'group' => 'accountTab',
				'enable' => true,
				'position' => 100,
				'formConfiguration' => [
					'form' => [
						'startTag' => [
							'action' => zbase_url_from_current(),
							'html' => [
								'attributes' => [
									'class' => [
										'zbase-ajax-form'
									]
								]
							]
						]
					],
				],
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
			'notifications' => [
				'type' => 'tab',
				'label' => 'Notifications',
				'id' => 'notifications',
				'group' => 'accountTab',
				'enable' => true,
				'position' => 9996,
				'contents' => [
					'notifications' => function(){
						return zbase_view_render(zbase_view_file_module('account.views.notifications', 'account','zbase'), ['user' => zbase_auth_user()])->render();
					}
				],
				'formConfiguration' => [
					'submit' => [
						'button' => [
							'enable' => false
						]
					],
				],
			],
			'username' => [
				'type' => 'tab',
				'label' => 'Username',
				'id' => 'username',
				'group' => 'accountTab',
				'enable' => true,
				'formConfiguration' => [
					'form' => [
						'startTag' => [
							'action' => zbase_url_from_current(),
							'html' => [
								'attributes' => [
									'class' => [
										'zbase-ajax-form'
									]
								]
							]
						]
					],
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
			'email' => [
				'type' => 'tab',
				'label' => 'Email Address',
				'id' => 'email',
				'group' => 'accountTab',
				'enable' => true,
				'formConfiguration' => [
					'form' => [
						'startTag' => [
							'action' => zbase_url_from_current(),
							'html' => [
								'attributes' => [
									'class' => [
										'zbase-ajax-form'
									]
								]
							]
						]
					],
				],
				'elements' => [
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
						'html' => [
							'attributes' => [
								'input' => [
									'autocomplete' => 'off'
								]
							],
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
				],
			],
			'password' => [
				'type' => 'tab',
				'label' => 'Update Password',
				'id' => 'password',
				'group' => 'accountTab',
				'enable' => true,
				'formConfiguration' => [
					'form' => [
						'startTag' => [
							'action' => zbase_url_from_current(),
							'html' => [
								'attributes' => [
									'class' => [
										'zbase-ajax-form'
									]
								]
							]
						]
					],
				],
				'elements' => [
					'header' => [
						'ui' => [
							'type' => 'component.pageHeader',
							'id' => 'header',
							'text' => 'To update password, enter your current password.'
						],
					],
					'password' => [
						'type' => 'password',
						'id' => 'password',
						'label' => null,
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'Enter your account password.'
							],
							'accountPassword' => [
								'enable' => true,
								'message' => 'Account password don\'t match.'
							],
						],
					],
				],
			],
			'images' => [
				'type' => 'tab',
				'label' => 'Profile Image',
				'id' => 'images',
				'group' => 'accountTab',
				'enable' => true,
				'position' => 90,
				'formConfiguration' => [
					'angular' => [
						'form' => [
							'startTag' => [
								'html' => [
									'attributes' => [
										'ng-controller' => 'adminAccountMainController',
										'flow-init' => function(){
											return '{headers:{\'X-CSRF-TOKEN\': \'' . zbase_csrf_token() . '\'}, target: \'' . zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updateProfileImage']) . '\'}';
										},
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
