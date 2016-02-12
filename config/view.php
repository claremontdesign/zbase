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
//			'_token' => [
//				'type' => \Zbase\Models\View::HEADMETA,
//				'enable' => true,
//				'name' => '_token',
//				'content' => zbase_csrf_token(),
//			],
			// <editor-fold defaultstate="collapsed" desc="Jquery">
			'zbase' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('js/js.js'),
				'enable' => true
			],
			'jquery' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => '//code.jquery.com/jquery-1.11.0.min.js',
				'enable' => true,
				'dependents' => [
					[
						'id' => 'migrate',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => '//code.jquery.com/jquery-migrate-1.2.1.min.js',
						'enable' => true,
					],
					[
						'id' => 'ui',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => '//code.jquery.com/ui/1.11.4/jquery-ui.min.js',
						'enable' => true,
					]
				]
			],
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="Bootstrap">
			'bootstrap' => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
				'enable' => true,
				'dependents' => [
					[
						'id' => 'meta-charset',
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'position' => 9999,
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
						'position' => 9998,
						'content' => 'width=device-width, initial-scale=1',
						'enable' => true
					],
					[
						'id' => 'meta-compatibility',
						'type' => \Zbase\Models\View::HEADMETA,
						'enable' => true,
						'position' => 9997,
						'content' => 'IE=edge',
						'html' => [
							'attributes' => [
								'http-equiv' => 'X-UA-Compatible'
							]
						]
					],
					[
						'id' => 'theme',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css',
						'enable' => true,
					],
					[
						'id' => 'base',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
						'enable' => true,
					],
					[
						'id' => 'html5shiv',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => '//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
						'enable' => true,
						'placeholder' => 'head_javascripts',
						'html' => [
							'conditions' => 'if lt IE 9'
						],
					],
					[
						'id' => 'respond',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => '//oss.maxcdn.com/respond/1.4.2/respond.min.js"',
						'enable' => true,
						'placeholder' => 'head_javascripts',
						'html' => [
							'conditions' => 'if lt IE 9'
						],
					]
				]
			// </editor-fold>
			],
		],
		'autoload' => [
			'plugins' => ['meta-viewport', 'meta-charset', 'meta-http-equiv', 'meta-author', 'jquery', 'bootstrap', 'zbase']
		]
	// </editor-fold>
	],
];
