<?php

/**
 * Entities configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file entity.php
 * @project Zbase
 * @package config
 *
 */
return [
	'entity' => [
		'user' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\User\User::class,
			'data' => [
				'factory' => [
					'enable' => true,
					'rows' => 10
				],
			],
			'table' => [
				'name' => 'users',
				'primaryKey' => 'user_id',
				'timestamp' => true,
				'softDelete' => true,
				'description' => 'User',
				'alphaId' => true,
				'rememberToken' => true,
				'optionable' => true
			]
		],
		'user_profile' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\User\UserProfile::class,
			'data' => [
				'factory' => [
					'enable' => true,
					'dependent' => true,
				],
			],
			'table' => [
				'name' => 'users_profile',
				'description' => 'User Profiles'
			]
		],
		'user_address' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\User\UserAddress::class,
			'table' => [
				'name' => 'users_address',
				'description' => 'User Addresses'
			]
		],
		'user_logs' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\User\Log::class,
			'table' => [
				'name' => 'users_logs',
				'description' => 'User Logs'
			]
		],
		'user_notifications' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\User\Notifications::class,
			'table' => [
				'name' => 'users_notification',
				'description' => 'User Notifications'
			]
		],
		'user_roles' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\User\Role::class,
			'table' => [
				'name' => 'user_roles',
				'primaryKey' => 'role_id',
				'description' => 'User - List of Roles',
			]
		],
		'users_roles' => [
			'enable' => true,
			'model' => false,
			'data' => [
				'factory' => [
					'enable' => true,
					'dependent' => true
				],
			],
			'table' => [
				'name' => 'users_roles',
				'description' => 'User - Role Pivot table',
				'columns' => [
					'user_id' => [
						'length' => 16,
						'hidden' => false,
						'fillable' => true,
						'type' => 'integer',
						'unsigned' => true,
						'foreign' => [
							'table' => 'users',
							'column' => 'user_id',
							'onDelete' => 'cascade'
						],
						'comment' => 'User ID'
					],
					'role_id' => [
						'length' => 16,
						'hidden' => false,
						'fillable' => true,
						'type' => 'integer',
						'index' => true,
						'unique' => false,
						'unsigned' => true,
						'foreign' => [
							'table' => 'user_roles',
							'column' => 'role_id',
							'onDelete' => 'cascade'
						],
						'comment' => 'Role ID'
					],
				]
			]
		],
		'user_tokens' => [
			'enable' => true,
			'model' => false,
			'table' => [
				'name' => 'user_tokens',
				'description' => 'User - Tokens',
				'polymorphic' => [
					'prefix' => 'taggable'
				],
				'primaryKey' => 'token_id',
				'columns' => [
					'token_id' => [
						'sortable' => [
							'name' => 'tokenid',
							'enable' => true
						],
						'label' => 'Token ID',
						'hidden' => false,
						'fillable' => false,
						'type' => 'integer',
						'unique' => true,
						'unsigned' => true,
						'length' => 16,
						'comment' => 'Token Id'
					],
					'user_id' => [
						'length' => 16,
						'hidden' => false,
						'fillable' => true,
						'nullable' => true,
						'type' => 'integer',
						'unsigned' => true,
						'foreign' => [
							'table' => 'users',
							'column' => 'user_id',
							'onDelete' => 'cascade'
						],
						'comment' => 'User ID'
					],
					'token' => [
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'comment' => 'Token'
					],
					'email' => [
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'nullable' => true,
						'type' => 'string',
						'foreign' => [
							'table' => 'users',
							'column' => 'email',
							'onDelete' => 'cascade'
						],
						'comment' => 'Email Address'
					],
					'created_at' => [
						'hidden' => false,
						'fillable' => false,
						'type' => 'timestamp',
						'nullable' => false,
					]
				]
			]
		],
		'messages' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Message\Message::class
		],
		'messages_recipient' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Message\Recipient::class
		],
		'messages_files' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Message\File::class
		],
	],
];
