<?php

namespace Zbase\Entity\Laravel\User;

/**
 * Zbase-UserProfile Entity
 *
 * UserProfile Entity Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file UserProfile.php
 * @project Zbase
 * @package Zbase/Entity/User
 */
use Zbase\Entity\Laravel\Entity as BaseEntity;

class UserProfile extends BaseEntity
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'user_profile';

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
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
		];
		return $relations;
	}

	/**
	 * Table Columns
	 * @param array $columns Configuration default data
	 * @return array
	 */
	public static function tableColumns($columns = [])
	{
		$columns = [
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
			'title' => [
				'filterable' => [
					'name' => 'title',
					'enable' => true
				],
				'sortable' => [
					'name' => 'title',
					'enable' => true
				],
				'length' => 64,
				'nullable' => true,
				'hidden' => false,
				'fillable' => true,
				'type' => 'string',
				'subType' => 'personTitle',
				'comment' => 'Title'
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
				'nullable' => true,
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
				'nullable' => true,
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
				'nullable' => true,
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
				'nullable' => true,
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
				'nullable' => true,
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
				'nullable' => true,
				'type' => 'string',
				'subType' => 'avatarurl',
				'length' => 255,
				'comment' => 'Avatar URL'
			],
			'options' => [
				'hidden' => false,
				'fillable' => true,
				'type' => 'json',
				'nullable' => true,
				'comment' => 'Some data'
			],
		];
		return $columns;
	}
}
