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
	'enable' => true,
	'config' => [
		'access' => 'only::sudo',
		'values' => [
			'default' => [
				'enable' => true,
				'array' => function(){
					return zbase()->telegram()->settings();
				}
			],
		],
		'entity' => null,
		'elements' => [
			'status' => [
				'type' => 'select',
				'id' => 'status',
				'label' => 'Status',
				'multiOptions' => 'enableDisable',
				'help' => [
					'text' => 'Disable telegram messaging.'
				],
			],
			'botusername' => [
				'type' => 'text',
				'id' => 'botusername',
				'label' => 'Bot Name',
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'Bot Username',
						],
					],
				],
			],
			'bottoken' => [
				'type' => 'text',
				'id' => 'bottoken',
				'label' => 'Bot Token',
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'Bot Token',
						],
					],
				],
			],
			'webhook' => [
				'type' => 'text',
				'id' => 'webhook',
				'label' => 'WebHook (Optional)',
				'html' => [
					'attributes' => [
						'input' => [
							'value' => function(){
								$token = zbase()->telegram()->token();
								if(!empty($token))
								{
									return zbase_url_from_route('telegramhook', ['token' => $token]);
								}
							},
							'placeholder' => 'Webhook',
						],
					],
				],
			],
		]
	],
];
