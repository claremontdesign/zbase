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
		return zbase_config_get('modules.account.widgets.email.enable', true);
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
			'email' => [
				'post' => [
					'redirect' => [
						'enable' => false
					]
				]
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update Email Address'
			]
		],
		'form' => [
			'startTag' => [
				'action' => function(){
					if(zbase_is_back())
					{
						return zbase_url_from_route('admin.account', ['action' => 'email']);
					}
					return zbase_url_from_route('account', ['action' => 'email']);
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
			'email' => [
				'type' => 'email',
				'id' => 'email',
				'label' => 'Email Address',
				'entity' => [
					'property' => 'email'
				],
				'angular' => [
					'ngModel' => 'currentUser.email',
				],
				'html' => [
					'attributes' => [
						'input' => [
							'autocomplete' => 'off'
						]
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Email address is required.'
					],
					'unique' => [
						'enable' => true,
						'text' => function(){
									return 'unique:' . zbase_entity('user')->getTable() . ',email,' . zbase_auth_user()->id() . ',user_id';
								},
						'message' => 'Email address already exists.'
					],
					'not_in' => [
						'enable' => true,
						'text' => function(){
									return 'not_in:' . zbase_auth_user()->email;
								},
						'message' => 'Please provide a different email address.'
					],
				],
			],
		],
	],
];
