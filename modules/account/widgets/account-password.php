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
			'method' => 'currentUser',
			'repo' => [
				'method' => 'currentUser',
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
					if(zbase_is_back())
					{
						return zbase_url_from_route('admin.account', ['action' => 'password']);
					}
					return zbase_url_from_route('account', ['action' => 'password']);
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
			'header' => [
				'ui' => [
					'type' => 'component.pageHeader',
					'id' => 'header',
					'text' => 'To update password, enter your current password. We will send you an email with a link to reset your password. You will then be logout automatically.'
				],
			],
			'password' => [
				'type' => 'password',
				'id' => 'password',
				'label' => null,
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Enter your account password.'
					],
					'accountPassword' => [
						'enable' => true,
						'message' => 'Account password don\'t match.'
					],
				],
			],
		],
	],
];
