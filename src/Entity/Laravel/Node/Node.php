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

class Node extends BaseEntity implements WidgetEntityInterface
{

	use \Zbase\Entity\Laravel\Node\Traits\Node;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node';

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
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(rand(3, 10), true)),
			'content' => $faker->text(1000, true),
			'excerpt' => $faker->text(200),
			'status' => rand(0, 2),
		];
	}

	/**
	 * POST-Seeding event
	 */
	public static function seedingEventPost()
	{
		for ($x = 0; $x <= 15; $x++)
		{
			$entity = new \Zbase\Entity\Laravel\Node\Node(self::fakeValue());
			$entity->save();
		}
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
				'type' => 'belongsto',
				'class' => [
					'method' => 'user'
				],
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'user_id'
				],
			],
			'node_category' => [
				'entity' => self::$nodeNamePrefix . '_category',
				'type' => 'manytomany',
				'class' => [
					'method' => 'categories'
				],
				'pivot' => self::$nodeNamePrefix . '_category_pivot',
				'keys' => [
					'local' => 'category_id',
					'foreign' => 'node_id'
				],
			],
			'node_files' => [
				'entity' => self::$nodeNamePrefix . '_files',
				'type' => 'onetomany',
				'class' => [
					'method' => 'files'
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
			'name' => self::$nodeNamePrefix,
			'primaryKey' => 'node_id',
			'timestamp' => true,
			'softDelete' => true,
			'alphaId' => true,
			'nodeable' => true,
			'optionable' => true,
			'sluggable' => true
		];
		return $entity;
	}

}
