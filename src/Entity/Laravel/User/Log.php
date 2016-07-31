<?php

namespace Zbase\Entity\Laravel\User;

/**
 * Zbase-User Entity
 *
 * User Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file User.php
 * @project Zbase
 * @package Zbase/Entity/User
 */
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Zbase\Interfaces;
use Zbase\Traits;

class Log extends BaseEntity implements Interfaces\IdInterface, Interfaces\EntityLogInterface
{

	use Traits\EntityLog;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'user_logs';

	public function id()
	{
		return $this->log_id;
	}

	public function description()
	{
		return $this->remarks;
	}

	public function name()
	{
		return $this->type;
	}

	public function title()
	{
		return $this->type;
	}

	// <editor-fold defaultstate="collapsed" desc="SEEDING / Table Configuration">
	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		return [];
	}

	/**
	 * POST-Seeding event
	 */
	public static function seedingEventPost($entity = [])
	{

	}

	/**
	 * Seed
	 */
	public static function seeder($max = 15)
	{

	}

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			'owner' => [
				'entity' => 'user',
				'type' => 'onetomany',
				'class' => [
					'method' => 'owner'
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
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		$entity['table'] = [
			'name' => 'user_log',
			'primaryKey' => 'log_id',
			'optionable' => true,
			'timestamp' => true
		];
		return $entity;
	}

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		$columns['user_id'] = [
			'filterable' => [
				'name' => 'user_id',
				'enable' => true
			],
			'sortable' => [
				'name' => 'user_id',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'index' => false,
			'comment' => 'User Id'
		];
		$columns['remarks'] = [
			'filterable' => [
				'name' => 'excerpt',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Remarks'
		];
		$columns['type'] = [
			'filterable' => [
				'name' => 'type',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => false,
			'type' => 'string',
			'comment' => 'Type of Log'
		];
		return $columns;
	}

	// </editor-fold>
}
