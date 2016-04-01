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
	'id' => 'dashboard-front',
	'enable' => true,
	'frontend' => true,
	'access' => 'user',
	'url' => [
		'frontend' => 'dashboard',
	],
	'controller' => [
		'front' => [
			'action' => [
				'index' => [
					'page' => [
						'title' => 'Dashboard',
						'headTitle' => 'Dashboard',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Dashboard', 'link' => '#'],
						],
					],
				],
			]
		],
	],
	'widgets' => [
		'front' => [
			'controller' => [
				'action' => [
					'index' => [
						'dashboard-front-index' => null
					],
				],
			]
		]
	],
];
