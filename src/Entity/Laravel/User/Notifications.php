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

class Notifications extends BaseEntity implements Interfaces\IdInterface
{

	const TYPE_IMPORTANT = 4;
	const TYPE_ALERT = 2;
	const TYPE_SUCCESS = 1;
	const TYPE_WARNING = 3;
	const TYPE_INFO = 5;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'user_notifications';

	public function id()
	{
		return $this->notification_id;
	}

	/**
	 * Owner of this entityr
	 * @return integer
	 */
	public function ownerId()
	{
		return $this->user_id;
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

	public function hasSeen()
	{
		return (bool) $this->is_seen;
	}

	public function hasNotified()
	{
		return (bool) $this->is_notified;
	}

	public function hasRead()
	{
		return (bool) $this->is_read;
	}

	public function isNew()
	{
		return (bool) $this->is_new;
	}

	//fa-bolt vimportant 4
	//fa fa-bullhorn alert 2
	//fa fa-plus success 1
	//fa fa-bell-o warning 3
	/**
	 * The Display ICon depending on type
	 */
	public function displayIcon()
	{
		if($this->type == self::TYPE_ALERT)
		{
			return 'fa-bolt';
		}
		elseif($this->type == self::TYPE_INFO)
		{
			return 'fa-bullhorn';
		}
		elseif($this->type == self::TYPE_IMPORTANT)
		{
			return 'fa-bolt';
		}
		elseif($this->type == self::TYPE_SUCCESS)
		{
			return 'fa-plus';
		}
		elseif($this->type == self::TYPE_WARNING)
		{
			return 'fa-bell-o';
		}
	}

	public function displayColor()
	{
		if($this->type == self::TYPE_ALERT)
		{
			return 'danger';
		}
		elseif($this->type == self::TYPE_INFO)
		{
			return 'info';
		}
		elseif($this->type == self::TYPE_IMPORTANT)
		{
			return 'danger';
		}
		elseif($this->type == self::TYPE_SUCCESS)
		{
			return 'success';
		}
		elseif($this->type == self::TYPE_WARNING)
		{
			return 'warning';
		}
	}

	public function displayTime()
	{
		return zbase_date_human($this->created_at);
	}

	public function displayMessage()
	{
		return $this->remarks;;
	}

	public function url()
	{
		return '#';
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
			'name' => 'user_notification',
			'primaryKey' => 'notification_id',
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
				'enable' => true
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
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
			'comment' => 'Type of Notification'
		];
		$columns['is_read'] = [
			'filterable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'boolean',
			'comment' => 'Read?'
		];
		$columns['is_new'] = [
			'filterable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'boolean',
			'comment' => 'New?'
		];
		$columns['is_seen'] = [
			'filterable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'boolean',
			'comment' => 'Seen?'
		];
		$columns['is_notified'] = [
			'filterable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'boolean',
			'comment' => 'Notified?'
		];
		return $columns;
	}

	// </editor-fold>
}
