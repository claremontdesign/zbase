<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 5, 2016 11:51:42 PM
 * @file profile.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 * zbase()->loadModuleFrom(PATH_TO_MODULES);
 * 		- widgets will be added automatically if a "widget" folder is found (zbase()->loadWidgetsFrom(PATH_TO_WIDGETS))
 *
 */
return [
	'id' => 'account',
	'enable' => true,
	'access' => 'user',
	'class' => null,
	'backend' => true,
	'frontend' => true,
	'url' => [
		'backend' => 'account/{action?}',
		'frontend' => 'account/{action?}/{task?}',
	],
	// domain.com/api/username-key/format=xml|json/module/object/method/paramOne/paramTwo/paramThree/paramFour/paramFive/paramSix
	// http://zbase.com/api/username/key/json/account/user/byid/1
	// http://zbase.com/api/username/key/json/account/user/byemail/dennes.b.abing@gmail.com
	'api' => [
		// <editor-fold defaultstate="collapsed" desc="API">
		// <editor-fold defaultstate="collapsed" desc="user.password">
		'user.password' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'password',
			'requestMethod' => 'post',
			'params' => [
				'email' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
						'email' => [
							'enable' => true,
						],
						'exists' => [
							'enable' => true,
							'text' => function(){
								return 'exists:' . zbase_entity('user')->getTable() . ',email';
							},
							'message' => 'Email address not found.'
						],
					],
					'name' => 'Email Address',
					'varname' => 'username',
				],
			],
		],
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="user.login">
		'user.login' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'login',
			'requestMethod' => 'post',
			'params' => [
				'paramOne' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
						'email' => [
							'enable' => true,
						],
					],
					'name' => 'Email Address',
					'varname' => 'username'
				],
				'paramTwo' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
					],
					'name' => 'Password',
					'varname' => 'password'
				],
			],
		],
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="user.updateProfile">
		'user.updateProfile' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'updateProfile',
			'requestMethod' => 'post',
			'notParams' => ['userId'],
			'params' => [
				'first_name' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
					],
					'name' => 'Last Name',
				],
				'last_name' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
					],
					'name' => 'Last Name',
				],
			],
		],
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="user.updateProfileImage">
		'user.updateProfileImage' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'updateProfileImage',
			'requestMethod' => ['get', 'post'],
			'notParams' => ['userId'],
			'params' => [],
		],
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="user.updateEmail">
		'user.updateEmail' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'updateEmail',
			'requestMethod' => 'post',
			'notParams' => ['userId'],
			'params' => [
				'email' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
						'email' => [
							'enable' => true,
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
					'name' => 'Email Address',
				],
				'accountpassword' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
						'accountPassword' => [
							'enable' => true,
							'message' => 'Account password don\'t match.'
						],
					],
					'name' => 'Account Password',
				],
			],
		],
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="user.updatePassword">
		'user.updatePassword' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'updatePassword',
			'requestMethod' => 'post',
			'notParams' => ['userId'],
			'params' => [
				'password' => [
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
				'passwordConfirm' => [
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
				'accountpassword' => [
					'validations' => [
						'required' => [
							'enable' => true,
						],
						'accountPassword' => [
							'enable' => true,
							'message' => 'Account password don\'t match.'
						],
					],
					'label' => 'Account Password',
				],
			],
		],
		// </editor-fold>
		'user.logout' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'logout',
		],
		'user.current' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'current',
		],
	// </editor-fold>
	],
	'navigation' => [
		'back' => [
			'enable' => true,
			'nav' => [
				'order' => 1,
				'route' => [
					'name' => 'admin.account'
				],
				'icon' => 'fa fa-user',
				'label' => 'Account',
				'title' => 'Account Settings'
			]
		],
	],
	'routes' => [],
	'angular' => [
		'mobile' => [
			'backend' => [
				'routeProvider' => [
					[
						'url' => function(){
							return zbase_angular_route('admin.account', [], true);
							},
								'templateUrl' => function(){
							return zbase_angular_template_url('account', [], true);
							},
								'controller' => 'adminAccountMainController'
							],
							[
								'url' => function(){
									return zbase_angular_route('admin.logout', [], true);
							},
										'controller' => 'adminAccountMainController'
									],
									[
										'auth' => false,
										'url' => '/',
										'templateUrl' => function(){
											return zbase_angular_template_url('admin.login', [], true);
							},
												'controller' => 'adminAuthController'
											],
										],
										'controllers' => [
											[
												'controller' => 'mainController',
												'view' => [
													'file' => function(){
														return zbase_view_render(zbase_view_file_module('account.views.angular.back.mobile.controllers.mainController'));
							}
												],
											],
											[
												'controller' => 'adminAccountMainController',
												'view' => [
													'file' => function(){
														return zbase_view_render(zbase_view_file_module('account.views.angular.back.mobile.controllers.adminAccountMainController'));
							}
												],
											],
											[
												'auth' => false,
												'controller' => 'adminAuthController',
												'view' => [
													'file' => function(){
														return zbase_view_render(zbase_view_file_module('account.views.angular.back.mobile.controllers.adminAuthController'));
							}
												],
											],
										],
									]
								]
							],
							'controller' => [
								'back' => [
									'action' => [
										'index' => [
											'page' => [
												'title' => 'Account Information',
												'headTitle' => 'Account',
												'subTitle' => 'Manage account and login information',
												'breadcrumbs' => [
													['label' => 'Account', 'link' => '#'],
												],
											],
										],
									]
								],
								'front' => [
									'action' => [
										'index' => [
											'page' => [
												'title' => 'Account Information',
												'headTitle' => 'Account',
												'subTitle' => 'Manage account and login information',
												'breadcrumbs' => [
													['label' => 'Account', 'link' => '#'],
												],
											],
										],
									]
								],
							],
//	'event' => [
//		'front' => [
//			'index' => [
//				'post' => [
//					'post' => [
//						'route' => [
//							'name' => 'account',
//						]
//					]
//				],
//			],
//		],
//	],
		'widgets' => [
			'controller' => [
				'index' => function(){
							return zbase_config_get('modules.account.widgets.controller.index', ['account' => null]);
						},
				'json-index' => function(){
						return zbase_config_get('modules.account.widgets.controller.index', ['account' => null]);
					},
				'resend-email-verification' => function(){
					zbase_auth_user()->resendEmailVerificationCode();
					return zbase_redirect()->to(zbase_url_previous());
				},
				'json-resend-email-verification' => function(){
					zbase_auth_user()->resendEmailVerificationCode();
					return zbase_redirect()->to(zbase_url_previous());
				},
				'json-telegram-check' => function(){
						$r = zbase()->telegram()->checkUserCode(zbase_auth_user());
						if($r)
						{
							zbase()->json()->addVariable('telegramHooked', 1);
							return zbase_redirect()->to(zbase_url_from_route('admin.account'));
						} else {
							dd('waiting to hooked...');
						}
				},
				'telegram-disable' => function(){
					zbase()->telegram()->disableUserTelegram(zbase_auth_user());
					return redirect()->to(zbase_url_previous());
				},
				'email-verify' => function(){
					$emailAddress = zbase_route_input('task');
					$code = zbase_request_input('c');
					$user = zbase_user_by('email', $emailAddress);
					if(!empty($user))
					{
						$user->verifyEmailAddress($code);
						return zbase_redirect(zbase_url_from_route('home'));
					}
					return zbase_abort(404);
				}
			],
		],
	];
