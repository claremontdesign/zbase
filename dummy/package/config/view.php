<?php

/**
 * View specific configuration
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 19, 2016 2:26:57 PM
 * @file view.php
 */
return [
	'view' => [
		'templates' => [
			'front' => [
				'package' => packagename_tag(),
				'theme' => packagename_tag()
			],
		],
		'default' => [
			'title' => [
				'prefix' => '',
				'separator' => ' ',
				'suffix' => 'Packagename'
			],
			'description' => '',
			'keywords' => ''
		],
		'plugins' => [
			// <editor-fold defaultstate="collapsed" desc="PackageName">
			packagename_tag() => [
				'type' => \Zbase\Models\View::JAVASCRIPT,
				'src' => zbase_path_asset('packagename'),
				'enable' => false,
				'position' => 399,
				'dependents' => [
					[
						'id' => 'script-onload',
						'type' => \Zbase\Models\View::SCRIPT,
						'enable' => false,
						'script' => 'PackageName::Init()',
						'onLoad' => true,
					],
					[
						'id' => 'js',
						'type' => \Zbase\Models\View::JAVASCRIPT,
						'src' => zbase_path_asset('packagename/'),
						'enable' => false,
						'position' => 398,
					],
					[
						'id' => 'style',
						'type' => \Zbase\Models\View::STYLESHEET,
						'href' => zbase_path_asset('packagename'),
						// 'href' => '//fonts.googleapis.com/css?family=Merriweather:100,300,400,300italic,400italic,600,700',
						'enable' => false,
						'html' => [
							'conditions' => 'if IE 9'
						],
						'position' => 497,
					],
				]
			]
		// </editor-fold>
		],
	]
];
