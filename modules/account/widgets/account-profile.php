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
	'type' => 'form',
	'enable' => function(){
		return zbase_config_get('modules.account.widgets.profile.enable', true);
	},
	'config' => [
		'entity' => [
			'name' => 'user',
			'method' => 'currentUser',
			'repo' => [
				'method' => 'currentUser',
			],
		],
		'event' => [
			'profile' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update Profile'
			]
		],
		'form' => [
			'startTag' => [
				'action' => function(){
					if(zbase_is_back())
					{
						return zbase_url_from_route('admin.account', ['action' => 'profile']);
					}
					return zbase_url_from_route('account', ['action' => 'profile']);
				},
				'html' => [
					'attributes' => [
						'class' => [
							'zbase-ajax-form'
						]
					]
				]
			]
		],
		'elements' => [
			'first_name' => [
				'type' => 'text',
				'id' => 'first_name',
				'enable' => true,
				'label' => 'First Name',
				'entity' => [
					'property' => 'first_name'
				]
			],
			'last_name' => [
				'type' => 'text',
				'id' => 'last_name',
				'enable' => true,
				'label' => 'Last Name',
				'entity' => [
					'property' => 'last_name'
				]
			],
		],
	],
];
