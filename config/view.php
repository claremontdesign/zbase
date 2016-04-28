<?php

/**
 * View configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file view.php
 * @project Zbase
 * @package config
 *
 * view.templates.front.package = packagename
 * view.templates.front.theme = packagename|themename
 */
return [
	'view' => [
		// <editor-fold defaultstate="collapsed" desc="Templates">
		// </editor-fold>
		// <editor-fold defaultstate="collapsed" desc="Plugins">
		'plugins' => [
			'meta-author' => [
				'type' => \Zbase\Models\View::HEADMETA,
				'enable' => true,
				'name' => 'author',
				'content' => 'Dennes B Abing'
			],
			'_token' => [
				'type' => \Zbase\Models\View::HEADMETA,
				'enable' => true,
				'name' => '_token',
				'content' => function(){return zbase_csrf_token();},
			],
			'zbase' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('js/js.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'zbase-app-init',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => true,
						'script' => 'Zbase.init();',
						'onLoad' => true,
					],
				]
			],
			'nodes' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('js/nodes/nodes.js'),
				'enable' => true
			],
			// <editor-fold defaultstate="collapsed" desc="Jquery">
			'jquery' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'cdn' => '//code.jquery.com/jquery-1.11.0.min.js',
				'src' => zbase_path_asset('jquery/jquery-1.11.0.min.js'),
				'enable' => true,
				'position' => 999,
				'dependents' => [
					[
						'id' => 'migrate',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//code.jquery.com/jquery-migrate-1.2.1.min.js',
						'src' => zbase_path_asset('jquery/jquery-migrate-1.2.1.min.js'),
						'enable' => true,
						'position' => 998,
					],
					[
						'id' => 'jqueryMobile',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js',
						'enable' => false,
						'position' => 997,
					],
					[
						'id' => 'ui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//code.jquery.com/ui/1.11.4/jquery-ui.min.js',
						'src' => zbase_path_asset('jquery/jquery-ui-1.11.4.min.js'),
						'enable' => true,
						'position' => 997,
					]
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Bootstrap">
			'bootstrap' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'cdn' => '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
				'position' => 996,
				'src' => zbase_path_asset('bootstrap/js/bootstrap.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'meta-charset',
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'position' => 999,
						'html' => [
							'attributes' => [
								'charset' => 'utf-8'
							]
						],
					],
					[
						'id' => 'meta-viewport',
						'type' => \Zbase\Models\View::HEADMETA,
						'name' => 'viewport',
						'position' => 998,
						'content' => 'width=device-width, initial-scale=1',
						'enable' => true
					],
					[
						'id' => 'meta-compatibility',
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'position' => 997,
						'content' => 'IE=edge',
						'html' => [
							'attributes' => [
								'http-equiv' => 'X-UA-Compatible'
							]
						]
					],
					[
						'id' => 'base',
						'type' => \Zbase\Models\View::STYLESHEET,
						'cdn' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
						'href' => zbase_path_asset('bootstrap/css/bootstrap.min.css'),
						'position' => 999,
						'enable' => true,
					],
					[
						'id' => 'theme',
						'type' => \Zbase\Models\View::STYLESHEET,
						'cdn' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css',
						'position' => 998,
						'href' => zbase_path_asset('bootstrap/css/bootstrap-theme.min.css'),
						'enable' => true,
					],
					[
						'id' => 'html5shiv',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
						'src' => zbase_path_asset('bootstrap/js/html5shiv-3.7.2.min.js'),
						'enable' => true,
						'placeholder' => 'head_javascripts',
						'html' => [
							'conditions' => 'if lt IE 9'
						],
					],
					[
						'id' => 'respond',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'cdn' => '//oss.maxcdn.com/respond/1.4.2/respond.min.js',
						'src' => zbase_path_asset('bootstrap/js/respond-1.4.2.min.js'),
						'enable' => true,
						'placeholder' => 'head_javascripts',
						'html' => [
							'conditions' => 'if lt IE 9'
						],
					]
				],
			// </editor-fold>
			],
			// <editor-fold defaultstate="collapsed" desc="Widget:Tree">
			'bootstrap-treeview' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('bootstrap/plugins/treeview/js/bootstrap-treeview.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'bootstrap-treeview',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('bootstrap/plugins/treeview/css/bootstrap-treeview.css'),
						'enable' => true,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Widget:JStree">
			'jstree' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/jstree/dist/jstree.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'jstree-css',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/jstree/dist/themes/default/style.min.css'),
						'enable' => true,
					],
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Metronic::Admin">
			'metronic-admin' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('metronic/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'),
				'enable' => true,
				'dependents' => [
					[
						'id' => 'metronic-admin-appinit',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => true,
						'script' => 'App.init();',
						'onLoad' => true,
					],
					[
						'id' => 'metronic-admin-slimscroll',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js'),
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'metronic-admin-blockui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery.blockui.min.js'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'metronic-admin-cookie',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/jquery.cokie.min.js'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'metronic-admin-uniform',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/plugins/uniform/jquery.uniform.min.js'),
						'enable' => true,
						'position' => 496,
					],
					[
						'id' => 'metronic-admin-app',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('metronic/scripts/core/app-zbase.js'),
						'enable' => true,
						'position' => 495,
					],
					[
						'id' => 'metronic-admin-font',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
						'enable' => true,
						'position' => 499,
					],
					[
						'id' => 'metronic-admin-font-awesome',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/plugins/font-awesome/css/font-awesome.min.css'),
						'enable' => true,
						'position' => 498,
					],
					[
						'id' => 'metronic-admin-metronic',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/style-metronic.css'),
						'enable' => true,
						'position' => 497,
					],
					[
						'id' => 'metronic-admin-style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/style.css'),
						'enable' => true,
						'position' => 496,
					],
					[
						'id' => 'metronic-admin-responsive',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/style-responsive.css'),
						'enable' => true,
						'position' => 495,
					],
					[
						'id' => 'metronic-admin-plugins',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/plugins.css'),
						'enable' => true,
						'position' => 494,
					],
					[
						'id' => 'metronic-admin-theme',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/themes/default.css'),
						'enable' => true,
						'position' => 493,
					],
					[
						'id' => 'metronic-admin-custom',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('metronic/css/custom.css'),
						'enable' => true,
						'position' => 492,
					],
				]
			]
		// </editor-fold>
		],
		'autoload' => [
			'plugins' => ['meta-viewport', 'meta-charset', 'meta-http-equiv', 'meta-author', 'zbase', '_token']
		]
	// </editor-fold>
	],
];
