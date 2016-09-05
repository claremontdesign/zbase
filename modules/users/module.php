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
	'id' => 'users',
	'enable' => function(){return zbase_config_get('modules.users.enable', true);},
	'access' => 'admin',
	'class' => null,
	'backend' => true,
	'frontend' => false,
	'url' => [
		'backend' => 'users/{action?}/{id?}',
	],
	'navigation' => [
		'back' => [
			'enable' => true,
			'nav' => [
				'route' => [
					'name' => 'admin.users'
				],
				'icon' => 'fa fa-users',
				'label' => 'Users',
				'title' => 'Manage Users'
			]
		],
	],
	'api' => [
		'users.logout' => [
			'enable' => true,
			'class' => \Zbase\Entity\Laravel\User\Api::class,
			'method' => 'logout',
		],
	],
	'routes' => [
		'admin-users-template' => [
			'url' => 'admin/templates/users.html',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					$angularDatatable = zbase_angular_widget_datatable('users', 'admin-users');
					$string = null;
					if(!empty($angularDatatable['template']))
					{
						$string = $angularDatatable['template'];
					}
					return $string;
				}
			],
		],
		'admin-user-template' => [
			// 'url' => 'admin/templates/user.html',
			'url' => 'admin/users/view',
//			'view' => [
//				'enable' => true,
//				'layout' => 'blank',
//				'name' => 'type.html',
//				'content' => function(){
//					$angularDatatable = zbase_angular_widget_datatable('users', 'admin-users');
//					$content = zbase_view_render(zbase_view_file_module('users.views.templates.user'));
//					$content = str_replace('APINAME', $angularDatatable['serviceGetSelectedItem'], $content);
//					return $content;
//				}
//			],
		],
	],
	'angular' => [
		'backend' => [
			'routeProvider' => [
				[
					'url' => function(){
						return zbase_angular_route('admin.users', [], true);
						},
							'templateUrl' => function(){
						return zbase_angular_template_url('admin-users-template', []);
						},
							'controller' => 'adminUsersController'
						],
						[
							'url' => function(){
								return zbase_angular_route('admin.users', ['action' => 'view'], true) . '/:itemId';
						},
									'templateUrl' => function(){
								return zbase_url_from_route('admin-user-template', [], true);
						},
									'controller' => 'adminUsersController'
								],
							],
							'controllers' => [
								[
									'controller' => 'adminUsersController',
									'view' => [
										'file' => function(){
											return zbase_view_render(zbase_view_file_module('users.views.angular.controllers.adminUsersController'));
											}
									],
								],
							],
						]
					],
					'controller' => [
						'back' => [
							'action' => [
								'index' => [
									'page' => [
										'title' => 'Manage Users',
										'headTitle' => 'Manage Users',
										'subTitle' => '',
										'breadcrumbs' => [
											['label' => 'Users', 'link' => '#'],
										],
									],
								],
								'view' => [
									'page' => [
										'title' => 'Manage Users',
										'headTitle' => 'Manage Users',
										'subTitle' => '',
										'breadcrumbs' => [
											['label' => 'Users', 'name' => 'admin.users'],
										],
									],
								],
							]
						],
					],
					'event' => [],
					'widgets' => [
						'back' => [
							'controller' => [
								'action' => [
									'index' => [
										'admin-users' => null
									],
									'json-index' => [
										'admin-users' => null
									],
									'view' => [
										'admin-user' => null
									],
									'json-view' => [
										'admin-user' => null
									],
								],
							],
						],
					],
				];
