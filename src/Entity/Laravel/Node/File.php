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

class File extends BaseEntity implements WidgetEntityInterface
{

	use \Zbase\Entity\Laravel\Node\Traits\File;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node_files';

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'node';

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
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

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			self::$nodeNamePrefix => [
				'entity' => self::$nodeNamePrefix,
				'type' => 'belongsto',
				'class' => [
					'method' => self::$nodeNamePrefix
				],
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'node_id'
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
			'name' => self::$nodeNamePrefix . '_files',
			'description' => 'Files Table',
			'primaryKey' => 'file_id',
			'timestamp' => true,
			'alphaId' => true,
			'optionable' => true
		];
		return $entity;
	}
}
