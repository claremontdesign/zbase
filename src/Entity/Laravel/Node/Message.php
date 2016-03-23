<?php

namespace Zbase\Entity\Laravel\Node;

/**
 * Zbase-Node Entity
 *
 * Node Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Node.php
 * @project Zbase
 * @package Zbase/Entity/Node
 */
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;
use Zbase\Entity\Laravel\Node\Traits\BaseNode;

class Message extends BaseEntity implements WidgetEntityInterface
{

	use BaseNode;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node_messages';

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'node';

	/**
	 * The Route Name to use
	 * @var string
	 */
	protected $routeName = 'node';

	public function id()
	{
		return $this->message_id;
	}

	public function alphaId()
	{
		return $this->alpha_id;
	}

	public function title()
	{
		return $this->title;
	}

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 * @return boolean
	 */
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		return $this->nodeWidgetController($method, $action, $data, $widget);
	}

	// <editor-fold defaultstate="collapsed" desc="Table Definitions">
	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			static::$nodeNamePrefix => [
				'entity' => static::$nodeNamePrefix,
				'type' => 'belongsto',
				'class' => [
					'method' => static::$nodeNamePrefix
				],
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'node_id'
				],
			],
//			'sender' => [
//				'entity' => 'user',
//				'type' => 'onetomany',
//				'inverse' => true,
//				'class' => [
//					'method' => 'sender'
//				],
//				'keys' => [
//					'local' => 'user_id',
//					'foreign' => 'sender_id'
//				],
//			],
//			'recipient' => [
//				'entity' => 'user',
//				'type' => 'belongsto',
//				'class' => [
//					'method' => 'recipient'
//				],
//				'keys' => [
//					'local' => 'recipient_id',
//					'foreign' => 'user_id'
//				],
//			],
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
			'name' => static::$nodeNamePrefix . '_messages',
			'description' => 'Nodes messages Table',
			'primaryKey' => 'message_id',
			'timestamp' => true,
			'alphaId' => true,
			'optionable' => true
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
		$columns['node_id'] = [
			'filterable' => [
				'name' => 'nodeid',
				'enable' => true
			],
			'sortable' => [
				'name' => 'nodeid',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => false,
			'type' => 'integer',
			'index' => true,
			'comment' => 'Node Id'
		];
		$columns['recipient_id'] = [
			'filterable' => [
				'name' => 'recipient',
				'enable' => true
			],
			'sortable' => [
				'name' => 'recipient',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => false,
			'type' => 'integer',
			'index' => true,
			'comment' => 'User-Recipient Id'
		];
		$columns['sender_id'] = [
			'filterable' => [
				'name' => 'sender',
				'enable' => true
			],
			'sortable' => [
				'name' => 'sender',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => false,
			'type' => 'integer',
			'index' => true,
			'comment' => 'User-Sender Id'
		];
		$columns['title'] = [
			'filterable' => [
				'name' => 'title',
				'enable' => true
			],
			'sortable' => [
				'name' => 'title',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'index' => false,
			'comment' => 'Title'
		];
		$columns['content'] = [
			'filterable' => [
				'name' => 'excerpt',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Excerpt'
		];
		$columns['read_status'] = [
			'filterable' => [
				'name' => 'status',
				'enable' => true
			],
			'sortable' => [
				'name' => 'status',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'unsigned' => true,
			'type' => 'boolean',
			'index' => true,
			'comment' => 'Read Status'
		];
		return $columns;
	}

	// </editor-fold>
}
