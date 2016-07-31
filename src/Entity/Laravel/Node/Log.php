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
use Zbase\Interfaces;
use Zbase\Traits;

class Log extends BaseEntity implements WidgetEntityInterface, Interfaces\EntityLogInterface
{

	use Traits\EntityLog;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node_logs';

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'node';

	// <editor-fold defaultstate="collapsed" desc="DataTable Widget Query Interface/Methods">

	/**
	 * Return SELECTs
	 * @param array $filters
	 */
	public function querySelects($filters)
	{
		return ['*'];
	}

	/**
	 * Join Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryJoins($filters, $sorting = [], $options = [])
	{
		$joins = [];
		return $joins;
	}

	/**
	 * Filter Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryFilters($filters, $sorting = [], $options = [])
	{
		$queryFilters = [];
		return $queryFilters;
	}

	/**
	 * Sorting Query
	 * @param array $sorting Array of Sorting
	 * @param array $filters Array of Filters
	 * @param array $options some options
	 * @return array
	 */
	public function querySorting($sorting, $filters = [], $options = [])
	{
		$sort = [];
		return $sort;
	}

	/**
	 * return the number of rows per page
	 * @return array
	 */
	public function getRowsPerPages()
	{
		return [10, 20, 30, 50, 100, 250, 500];
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="NodeWidgetController">

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

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 * @return boolean
	 */
	public function nodeWidgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		zbase_db_transaction_start();
		try
		{

		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
		}
		try
		{

		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing the request for "%title%".', ['%title%' => $this->title, '%id%' => $this->id()]);
			zbase_db_transaction_rollback();
		}
		return false;
	}

	// </editor-fold>
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
			'name' => static::$nodeNamePrefix . '_log',
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
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
			'comment' => 'Node Id'
		];
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
