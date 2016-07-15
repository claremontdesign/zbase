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
 * @file module.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 */
return [
	'id' => 'angular',
	'enable' => true,
	'access' => 'admin',
	'class' => null,
	'backend' => true,
	'frontend' => false,
	'url' => [
		'backend' => 'a-{action?}'
	],
	'routes' => [
		// <editor-fold defaultstate="collapsed" desc="AdminAngular ROUTES">
		'angular-js' => [
			'url' => 'admin/zbase-angular.js',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.js',
				'content' => function(){
					return zbase_view_render(zbase_view_file_module('angular.views.back.mobile.js'));
				}
			],
		],
		'admin-angular-mobile-sidebar' => [
			'backend' => true,
			'url' => 'admin/mobile/angular/sidebar.html',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					return zbase_view_render(zbase_view_file_module('angular.views.back.mobile.templates.sidebar'));
				}
			],
			'middleware' => [
				'admin' => true,
			],
		],
		'admin-angular-mobile-sidebar-right' => [
			'backend' => true,
			'url' => 'admin/mobile/angular/sidebar-right.html',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					return zbase_view_render(zbase_view_file_module('angular.views.back.mobile.templates.sidebar-right'));
				}
			],
			'middleware' => [
				'admin' => true,
			],
		],
		'admin-angular-mobile-dashboard-template' => [
			'backend' => true,
			'url' => 'admin/mobile/angular/dashboard.html',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					return zbase_view_render(zbase_view_file_module('angular.views.back.mobile.templates.dashboard'));
				}
			],
			'middleware' => [
				'admin' => true,
			],
		],
	// </editor-fold>
	],
	'angular' => [
		'mobile' => [
			'backend' => [
				'routeProvider' => [
					[
						'url' => '/',
						'templateUrl' => function(){return zbase_url_from_route('admin-angular-mobile-dashboard-template', [],true);},
						'controller' => 'adminDashboardController'
					]
				],
				'controllers' => [
					[
						'controller' => 'adminDashboardController',
						'view' => [
							'file' => function(){
								return zbase_view_render(zbase_view_file_module('angular.views.back.mobile.controllers.adminDashboardController'));
								}
						],
					],
				],
			]
		]
	],
	'widgets' => [
		'back' => [
			'controller' => [
				'action' => [
				],
			]
		]
	],
];
