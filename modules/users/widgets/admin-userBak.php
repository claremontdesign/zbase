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
	'enable' => function(){return zbase_config_get('modules.users.widgets.adminUser', true);},
	'config' => [
		'form_tab' => false,
		'form' => [
			'startTag' => [
				'html' => [
					'attributes' => [
						'ng-controller' => 'adminUsersController',
					],
				],
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update',
				'html' => [
					'attributes' => [
						'ng-click' => 'ANGULAR_WIDGET_MODULE_SCOPENAME.updateSelectedItem()'
					]
				],
			],
		],
		'entity' => [
			'node' => [
				'enable' => true,
			],
			'name' => 'user',
			'repo' => [
				'byId' => [
					'route' => 'id'
				],
			],
		],
		'event' => [
			'view' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'html' => [
			'content' => [
				'pre' => [
					'enable' => true,
					'html' => function(){
						return zbase_widget('admin-users', [], true, ['config' => ['searchable' => ['onload' => false]]]);
					}
				]
			],
		],
		'tabs' => [
			'account' => [
				'type' => 'tab',
				'label' => 'Account',
				'id' => 'account',
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
			'profile' => [
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
			'passwordx' => [
				'type' => 'tab',
				'label' => 'Update Password',
				'id' => 'passwordx',
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
					'password' => [
						'type' => 'password',
						'id' => 'password',
						'label' => 'Enter new Password',
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'Enter new password.'
							],
							'same' => [
								'enable' => true,
								'text' => 'required|confirmed|min:6'
							],
						],
					],
					'password_confirmation' => [
						'type' => 'password',
						'id' => 'password_confirmation',
						'label' => 'Confirm new Password',
						'validations' => [
							'required' => [
								'enable' => true,
								'message' => 'Enter new password.'
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
