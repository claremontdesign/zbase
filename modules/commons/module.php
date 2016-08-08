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
 * @file dsstore/module.php
 *
 */
return [
	'id' => 'commons',
	'enable' => true,
	'backend' => false,
	'frontend' => false,
	'url' => [
//		'backend' => 'admin/lib/{type?}/{one?}/{two?}',
//		'frontend' => 'lib/{type?}/{one?}/{two?}',
	],
	'routes' => [
		'lib_geo_ph' => [
			'usernameRouteCheck' => false,
			'url' => 'lib/geo/ph/cities.js',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.js',
				'content' => function(){
					return zbase_view_render(zbase_view_file_module('commons.views.lib.geo.ph', 'commons', 'zbase'));
				}
			],
		],
	]
];
