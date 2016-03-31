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

class Message extends BaseEntity
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'messages';

	public function save(array $options = [])
	{
		$model = parent::save($options);
		if(empty($this->alpha_id))
		{
			$this->_updateAlphaId();
		}
		return $model;
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->message_id) && empty($this->message_id) && !empty($this->alphable))
		{
			$alphaId = zbase_generate_hash([$this->message_id, time()], $this->entityName);
			$i = 1;
			while ($this->fetchByAlphaId($alphaId) > 0)
			{
				$alphaId = zbase_generate_hash([time(), $i++, $this->message_id], $this->entityName);
			}
			$this->alpha_id = $alphaId;
			$this->save();
		}
	}

	/**
	 * Fetch a Row By AlphaId
	 * @param string $alphaId
	 * @return Collection[]
	 */
	public function fetchByAlphaId($alphaId)
	{
		return $this->repository()->byAlphaId($alphaId);
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
			'user_id' => [
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
				'comment' => 'Owner ID'
			],
			'sender_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'index' => true,
				'unsigned' => true,
				'comment' => 'Sender ID'
			],
			'node_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'nullable' => true,
				'unique' => true,
				'unsigned' => true,
				'comment' => 'Node ID'
			],
			'node_prefix' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'nullable' => true,
				'index' => true,
				'unsigned' => true,
				'comment' => 'Node Prefix'
			],
			'subject' => [
				'filterable' => [
					'name' => 'subject',
					'enable' => true
				],
				'sortable' => [
					'name' => 'subject',
					'enable' => true
				],
				'length' => 255,
				'nullable' => true,
				'hidden' => false,
				'fillable' => true,
				'type' => 'string',
				'comment' => 'Subject'
			],
			'content' => [
				'nullable' => true,
				'hidden' => false,
				'fillable' => true,
				'type' => 'text',
				'comment' => 'Content'
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
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Reply Status'
			],
			'parent_id' => [
				'filterable' => [
					'name' => 'parentid',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'integer',
				'index' => true,
				'comment' => 'Parent ID'
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
			'name' => 'messages',
			'primaryKey' => 'message_id',
			'timestamp' => true,
			'alphaId' => true,
			'optionable' => true
		];
		return $entity;
	}

}
