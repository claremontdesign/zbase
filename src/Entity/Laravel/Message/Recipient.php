<?php

namespace Zbase\Entity\Laravel\Message;

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

class Recipient extends BaseEntity
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'messages_recipient';

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	/**
	 * Owner of this entityr
	 * @return integer
	 */
	public function ownerId()
	{
		return $this->message_id;
	}

	public function id()
	{
		return $this->message_recipient_id;
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->message_recipient_id) && empty($this->alpha_id) && !empty($this->alphable))
		{
			$alphaId = zbase_generate_hash([$this->message_recipient_id, time()], $this->entityName);
			$i = 1;
			while ($this->repository()->byAlphaId($alphaId) > 0)
			{
				$alphaId = zbase_generate_hash([time(), $i++, $this->message_recipient_id], $this->entityName);
			}
			$this->alpha_id = $alphaId;
			$this->save();
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Entity Configuration">
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
				'type' => 'onetomany',
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
		$columns = [
			'message_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'index' => true,
				'unsigned' => true,
				'foreign' => [
					'table' => 'messages',
					'column' => 'message_id',
					'onDelete' => 'cascade'
				],
				'comment' => 'Message ID'
			],
			'user_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'index' => true,
				'unsigned' => true,
//				'foreign' => [
//					'table' => 'users',
//					'column' => 'user_id',
//					'onDelete' => 'cascade'
//				],
				'comment' => 'Owner ID/Recipient'
			],
			'status' => [
				'filterable' => [
					'name' => 'status',
					'enable' => true
				],
				'sortable' => [
					'name' => 'status',
					'enable' => true
				],
				'hidden' => false,
				'default' => 2,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Is message viewable to the user?'
			],
			'admin_read_status' => [
				'filterable' => [
					'name' => 'admin_read_status',
					'enable' => true
				],
				'sortable' => [
					'name' => 'admin_read_status',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'default' => 0,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Admin Read Status'
			],
			'read_status' => [
				'filterable' => [
					'name' => 'read',
					'enable' => true
				],
				'sortable' => [
					'name' => 'read',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'default' => 0,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Read Status'
			],
			'trash_status' => [
				'filterable' => [
					'name' => 'trash',
					'enable' => true
				],
				'sortable' => [
					'name' => 'trash',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'default' => 0,
				'index' => true,
				'comment' => 'Trash Status'
			],
			'reply_status' => [
				'filterable' => [
					'name' => 'reply',
					'enable' => true
				],
				'sortable' => [
					'name' => 'reply',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'default' => 0,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Reply Status'
			],
			'is_starred' => [
				'filterable' => [
					'name' => 'star',
					'enable' => true
				],
				'sortable' => [
					'name' => 'star',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'default' => 0,
				'index' => true,
				'comment' => 'Starred'
			],
			'is_important' => [
				'filterable' => [
					'name' => 'important',
					'enable' => true
				],
				'sortable' => [
					'name' => 'important',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'default' => 0,
				'index' => true,
				'comment' => 'Important'
			],
			'is_draft' => [
				'filterable' => [
					'name' => 'draft',
					'enable' => true
				],
				'sortable' => [
					'name' => 'draft',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'default' => 0,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Draft'
			],
			'is_out' => [
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'default' => 0,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Is message out'
			],
			'is_in' => [
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'default' => 0,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Is message in'
			],
		];
		return $columns;
	}

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		$entity['table'] = [
			'name' => 'messages_recipient',
			'primaryKey' => 'message_recipient_id',
			'timestamp' => false,
			'optionable' => true,
			'alphaId' => true,
			'description' => 'Messages Recipient',
		];
		return $entity;
	}

	// </editor-fold>
}
