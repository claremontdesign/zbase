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

class Address extends BaseEntity
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'user_address';
	protected $hidden = ['user_id'];

	// <editor-fold defaultstate="collapsed" desc="TableDefinitions">

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		$entity = [];
		$entity['table'] = [
			'name' => 'users_address',
			'primaryKey' => 'address_id',
			'optionable' => true,
			'timestamp' => true
		];
		return $entity;
	}

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
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		$columns['address_id'] = [
			'filterable' => [
				'enable' => true
			],
			'sortable' => [
				'enable' => true
			],
			'label' => 'Address ID',
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'unique' => true,
			'unsigned' => true,
			'length' => 16,
			'comment' => 'Address Id'
		];
		$columns['user_id'] = [
			'length' => 16,
			'hidden' => false,
			'fillable' => true,
			'type' => 'integer',
			'index' => true,
			'unsigned' => true,
			'foreign' => [
				'table' => 'users',
				'column' => 'user_id',
				'onDelete' => 'cascade'
			],
			'comment' => 'User ID'
		];
		$columns['is_default'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'boolean',
			'comment' => 'Is Default Address'
		];
		$columns['is_active'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'index' => true,
			'type' => 'boolean',
			'comment' => 'Is Address Active'
		];
		$columns['type'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'index' => true,
			'type' => 'string',
			'comment' => 'Address Type'
		];
		$columns['comment'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Comment'
		];
		$columns['address'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Address'
		];
		$columns['address_two'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Address Line Two'
		];
		$columns['city'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'City'
		];
		$columns['zip'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Zip'
		];
		$columns['state'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Province'
		];
		$columns['country'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Country'
		];
		$columns['phone'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Phone'
		];
		$columns['fax'] = [
			'filterable' => [
				'enable' => true,
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Fax'
		];
		return $columns;
	}

	// </editor-fold>
}
