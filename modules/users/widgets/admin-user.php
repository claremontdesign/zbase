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
	'type' => 'view',
	'enable' => function(){return zbase_config_get('modules.users.widgets.admin-user', true);},
	'config' => [
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
		'view' => [
			'layout' => 'default',
			'file' => function(){
				return zbase_view_file_module('account.views.account', 'account', 'zbase');
			},
		]
	]
];
