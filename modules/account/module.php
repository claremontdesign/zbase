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
		'user.login' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'login',
			'requestMethod' => 'post',
			'params' => [
				'paramOne' => [
					'validation' => [
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
					'validation' => [
						'required' => [
							'enable' => true,
						],
					],
					'name' => 'Password',
					'varname' => 'password'
				],
			],
		],
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
		'user.byid' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'findUserById',
			'params' => [
				'paramOne' => [
					'validation' => [
						'required' => [
							'enable' => true,
							'message' => 'UserID is Required.'
						],
						'numeric' => [
							'enable' => true,
							'message' => 'UserID must be a number.'
						],
					],
					'name' => 'UserID',
					'varname' => 'userId'
				],
			],
		],
		'user.byemail' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'findUserByEmail',
			'params' => [
				'paramOne' => [
					'validation' => [
						'required' => [
							'enable' => true,
							'message' => 'Email address is Required.'
						],
						'email' => [
							'enable' => true,
							'message' => 'Invalid email address.'
						],
					],
					'name' => 'Email',
					'varname' => 'email'
				],
			],
		],
	// </editor-fold>
	],
	'routes' => [
		'admin-angular-auth-login-template' => [
			'url' => 'admin/angular/template/auth-login.html',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					return zbase_view_render(zbase_view_file_module('account.views.angular.back.mobile.templates.auth.login'));
				}
			],
			'middleware' => [
				'guestOnly' => true,
			],
		],
	],
	'angular' => [
		'mobile' => [
			'backend' => [
				'routeProvider' => [
					[
						'url' => function(){
							return zbase_angular_route('admin.account', [], true);
						},
								'templateUrl' => function(){return zbase_angular_template_url('account',[], true);},
								'controller' => 'adminAccountMainController'
							],
							[
								'auth' => false,
								'url' => '/',
								'templateUrl' => function(){
									return zbase_angular_template_url('admin-angular-auth-login-template', [], true);
								},
										'controller' => 'adminAuthController'
									],
								],
								'controllers' => [
									[
										'controller' => 'adminAccountMainController',
										'view' => [
											'file' => function(){
												return zbase_view_render(zbase_view_file_module('account.views.angular.back.mobile.controllers.account'));
								}
										],
									],
									[
										'auth' => false,
										'controller' => 'adminAuthController',
										'view' => [
											'file' => function(){
												return zbase_view_render(zbase_view_file_module('account.views.angular.back.mobile.controllers.auth'));
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
					'event' => [
						'front' => [
							'index' => [
								'post' => [
									'post' => [
										'route' => [
											'name' => 'account',
										]
									]
								],
							],
						],
					],
					'widgets' => [
						'controller' => [
							'index' => function(){
								return zbase_config_get('modules.account.widgets.controller.index', ['account' => null]);
					}
								],
							],
						];
