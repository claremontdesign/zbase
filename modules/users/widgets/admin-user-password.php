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
		return zbase_config_get('modules.account.widgets.password.enable', true);
	},
	'config' => [
		'entity' => [
			'name' => 'user',
			'node' => [
				'enable' => true,
			],
			'repo' => [
				'byId' => [
					'route' => 'id'
				]
			],
		],
		'event' => [
			'password' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update Password'
			]
		],
		'form' => [
			'startTag' => [
				'action' => function(){
					return zbase_url_from_route('admin.users', ['action' => 'password', 'id' => zbase_route_input('id')]);
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
			'password' => [
				'type' => 'password',
				'id' => 'password',
				'label' => 'Enter new Password',
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Enter new password.'
					],
					'same' => [
						'enable' => true,
						'text' => 'required|confirmed|min:6'
					],
				],
			],
			'password_confirmation' => [
				'type' => 'password',
				'id' => 'password_confirmation',
				'label' => 'Confirm new Password',
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Enter new password.'
					],
				],
			],
		],
	],
];
