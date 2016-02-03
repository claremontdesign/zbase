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
			'model' => Zbase\Entity\Framework\User\User::class,
			'data' => [
				'factory' => [
					'enable' => true,
					'rows' => 10
				],
			],
			'relations' => [
				'profile' => [
					'entity' => 'user_profile',
					'type' => 'onetoone',
					'class' => [
						'method' => 'profile'
					],
					'keys' => [
						'local' => 'user_id',
						'foreign' => 'user_id'
					],
				],
				'roles' => [
					'entity' => 'user_roles',
					'type' => 'manytomany',
					'class' => [
						'method' => 'roles'
					],
					'pivot' => 'users_roles', // The Pivot Entity Index
					'keys' => [
						'local' => 'role_id', // the foreign key name of the model on which you are defining the relationship
						'foreign' => 'user_id' // the foreign key name of the model that you are joining to
					],
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
				'columns' => [
					'user_id' => [
						'filterable' => [
							'name' => 'userid',
							'enable' => true
						],
						'sortable' => [
							'name' => 'userid',
							'enable' => true
						],
						'label' => 'User ID',
						'hidden' => false,
						'fillable' => false,
						'type' => 'integer',
						'unique' => true,
						'unsigned' => true,
						'length' => 16,
						'comment' => 'User Id'
					],
					/**
					 * User Statuses:
					 *
					 * 40 - 50 - Banned - Cannot Login the site
					 * 20 - 30 - Banned - Can Login the site but with notice
					 * 1 - 20 - Account OK; Default status
					 *
					 */
					'status' => [
						'filterable' => [
							'name' => 'status',
							'enable' => true
						],
						'sortable' => [
							'name' => 'status',
							'enable' => true
						],
						'hidden' => true,
						'fillable' => false,
						'type' => 'string',
						'valueMap' => [
							'ban_no_auth' => 'Banned cannot Login',
							'ban_can_auth' => 'Banned can Login',
							'ok' => 'Ok'
						],
						'nullable' => false,
						'unsigned' => false,
						'comment' => 'Account Status'
					],
					'username' => [
						'filterable' => [
							'name' => 'username',
							'enable' => true
						],
						'sortable' => [
							'name' => 'username',
							'enable' => true
						],
						'hidden' => false,
						'length' => 32,
						'fillable' => true,
						'type' => 'string',
						'subType' => 'userName',
						'unique' => true,
						'comment' => 'Unique user Name'
					],
					'name' => [
						'filterable' => [
							'name' => 'name',
							'enable' => true
						],
						'sortable' => [
							'name' => 'name',
							'enable' => true
						],
						'hidden' => false,
						'length' => 64,
						'fillable' => true,
						'type' => 'string',
						'subtype' => 'personDisplayName',
						'comment' => 'Display name'
					],
					'email' => [
						'filterable' => [
							'name' => 'email',
							'enable' => true
						],
						'sortable' => [
							'name' => 'email',
							'enable' => true
						],
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'subtype' => 'email',
						'unique' => true,
						'comment' => 'User email address'
					],
					'email_verified' => [
						'filterable' => [
							'name' => 'emailverified',
							'enable' => true
						],
						'sortable' => [
							'name' => 'emailverified',
							'enable' => true
						],
						'hidden' => false,
						'fillable' => false,
						'type' => 'boolean',
						'subtype' => 'yesno',
						'default' => 0,
						'comment' => 'Is email verified'
					],
					'email_verified_at' => [
						'filterable' => [
							'name' => 'emailverifieddate',
							'enable' => true
						],
						'sortable' => [
							'name' => 'emailverifieddate',
							'enable' => true
						],
						'hidden' => false,
						'fillable' => false,
						'type' => 'timestamp',
						'nullable' => true,
						'comment' => 'Date email verified'
					],
					'password' => [
						'hidden' => true,
						'fillable' => false,
						'type' => 'string',
						'subType' => 'password',
						'length' => 60,
						'comment' => 'User crypted password'
					],
					'password_updated_at' => [
						'hidden' => false,
						'fillable' => false,
						'type' => 'timestamp',
						'nullable' => true,
						'comment' => 'Date password updated'
					]
				]
			]
		],
		'user_profile' => [
			'enable' => true,
			'model' => Zbase\Entity\Framework\User\UserProfile::class,
			'data' => [
				'factory' => [
					'enable' => true,
					'dependent' => true,
				],
			],
			'relations' => [
				'user' => [
					'entity' => 'user',
					'type' => 'onetoone',
					'class' => [
						'method' => 'user'
					],
					'keys' => [
						'local' => 'user_id',
						'foreign' => 'user_id'
					],
				],
			],
			'table' => [
				'name' => 'users_profile',
				'description' => 'User Profiles',
				'columns' => [
					'user_id' => [
						'length' => 16,
						'hidden' => false,
						'fillable' => true,
						'type' => 'integer',
						'index' => true,
						'unique' => true,
						'unsigned' => true,
						'foreign' => [
							'table' => 'users',
							'column' => 'user_id',
							'onDelete' => 'cascade'
						],
						'comment' => 'User ID'
					],
					'first_name' => [
						'filterable' => [
							'name' => 'fname',
							'enable' => true
						],
						'sortable' => [
							'name' => 'fname',
							'enable' => true
						],
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'subType' => 'personFirstName',
						'comment' => 'User First name'
					],
					'last_name' => [
						'filterable' => [
							'name' => 'lname',
							'enable' => true
						],
						'sortable' => [
							'name' => 'lname',
							'enable' => true
						],
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'subType' => 'personLastName',
						'comment' => 'User Last name'
					],
					'middle_name' => [
						'filterable' => [
							'name' => 'mname',
							'enable' => true
						],
						'sortable' => [
							'name' => 'mname',
							'enable' => true
						],
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'subType' => 'personMiddleName',
						'comment' => 'User middle name'
					],
					'dob' => [
						'filterable' => [
							'name' => 'dob',
							'enable' => true
						],
						'sortable' => [
							'name' => 'dob',
							'enable' => true
						],
						'length' => 64,
						'hidden' => false,
						'fillable' => true,
						'nullable' => true,
						'default' => null,
						'type' => 'timestamp',
						'subType' => 'birthdate',
						'comment' => 'Date of birth'
					],
					'gender' => [
						'filterable' => [
							'name' => 'gender',
							'enable' => true
						],
						'sortable' => [
							'name' => 'gender',
							'enable' => true
						],
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'valueMap' => [
							'f' => 'Female',
							'm' => 'Male'
						],
						'comment' => 'Gender'
					],
					'avatar' => [
						'hidden' => false,
						'fillable' => false,
						'type' => 'string',
						'subType' => 'avatarurl',
						'length' => 255,
						'comment' => 'Avatar URL'
					],
				]
			]
		],
		'user_roles' => [
			'enable' => true,
			'model' => Zbase\Entity\Framework\User\Role::class,
			'relations' => [
				'user_roles' => [
					'type' => 'manytomany',
					'pivot' => 'users_roles',
					'keys' => [
						'local' => 'user_id',
						'foreign' => 'role_id'
					],
				],
				'parent' => [
					'type' => 'onetoone',
					'entity' => 'user_roles',
					'keys' => [
						'local' => 'parent_id',
						'foreign' => 'role_id'
					],
				],
			],
			'data' => [
				'defaults' => [
					[
						'parent_id' => NULL,
						'role_name' => 'user'
					],
					[
						'parent_id' => 1,
						'role_name' => 'moderator'
					],
					[
						'parent_id' => 2,
						'role_name' => 'admin'
					],
					[
						'parent_id' => 3,
						'role_name' => 'sudo'
					],
				]
			],
			'table' => [
				'name' => 'user_roles',
				'primaryKey' => 'role_id',
				'description' => 'List of Roles',
				'columns' => [
					'parent_id' => [
						'hidden' => false,
						'fillable' => false,
						'type' => 'integer',
						'unsigned' => true,
						'nullable' => true,
						'length' => 16,
						'comment' => 'Parent Id'
					],
					'role_name' => [
						'hidden' => false,
						'fillable' => true,
						'type' => 'string',
						'length' => 32,
						'unique' => true,
						'index' => true,
						'comment' => 'Role name'
					],
				]
			]
		],
		'users_roles' => [
			'enable' => true,
			'model' => [],
			'data' => [
				'factory' => [
					'enable' => true,
					'dependent' => true,
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
	],
];
