<?php

namespace Zbase\Entity\Laravel\Node;

/**
 * Zbase-Node Nested
 *
 * Node Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Nested.php
 * @project Nested
 * @package Zbase/Entity/Node
 */
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;
use Zbase\Interfaces;

class Category extends Nested implements WidgetEntityInterface, Interfaces\EntityInterface
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'node_category';

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
	 * Return the ID
	 * @return integer
	 */
	public function id()
	{
		return $this->category_id;
	}

	/**
	 * Return the Root Node
	 * @return
	 */
	public function getRoot()
	{
		return self::root();
	}

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 */
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		if(($action == 'update' && strtolower($method) == 'post') || ($action == 'create' && strtolower($method) == 'post'))
		{
			$this->nodeAttributes($data);
		}
		zbase_db_transaction_start();
		try
		{
			$parent = !empty($data['category']) ? $data['category'] : null;
			$parentNodes = [];
			if(!empty($parent))
			{
				if(is_array($parent))
				{
					foreach ($parent as $p)
					{
						if($parent instanceof Interfaces\EntityInterface)
						{
							$parentCategoryNode = $p;
						}
						else
						{
							$parentCategoryNode = $this->repository()->byId($p);
						}
						if($parentCategoryNode instanceof Interfaces\EntityInterface)
						{
							$parentNodes[] = $parentCategoryNode;
						}
						else
						{
							$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing your request.');
							return false;
						}
					}
				}
			}
			if($action == 'create' && strtolower($method) == 'post')
			{
				if(empty($parentNodes))
				{
					$parentNodes[] = self::root();
				}
				$this->save();
				$this->_setParentNodes($parentNodes);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Created "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				$this->save();
				$this->_setParentNodes($parentNodes);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Saved "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
		}

		if($action == 'index')
		{

			return;
		}
		if($action == 'update')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		if($action == 'delete')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		try
		{
			if($action == 'move')
			{

			}
			if($action == 'delete')
			{
				$this->delete();
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$undoText = '';
				if(!empty($this->hasSoftDelete()))
				{
					$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Undo Delete" class="undodelete">Undo</a>.';
					$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				}
				$this->_actionMessages[$action]['success'][] = _zt('Deleted "%title%"! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return true;
			}
			if($action == 'restore')
			{
				if($this->trashed())
				{
					$this->restore();
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" was restored!', ['%title%' => $this->title, '%id%' => $this->id()]);
					return true;
				}
				$this->_actionMessages[$action]['error'][] = _zt('Error restoring "%title%". Row was not trashed.!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return false;
			}
			if($action == 'ddelete')
			{
				if($this->trashed())
				{
					$this->forceDelete();
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" was removed from database!', ['%title%' => $this->title, '%id%' => $this->id()]);
					return true;
				}
				$this->_actionMessages[$action]['error'][] = _zt('Error restoring "%title%". Row was not trashed.!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return false;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing the request for "%title%".', ['%title%' => $this->title, '%id%' => $this->id()]);
			zbase_db_transaction_rollback();
		}
		return false;
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->category_id) && empty($this->alpha_id) && !empty($this->alphable))
		{
			$this->alpha_id = zbase_generate_hash($this->category_id, $this->entityName);
			$this->save();
		}
	}

	/**
	 * SEt the Parents
	 * @param Nested[] $parentNodes
	 */
	protected function _setParentNodes($parentNodes)
	{
		if(is_array($parentNodes))
		{
			foreach ($parentNodes as $p)
			{
				$this->makeChildOf($p);
			}
		}
	}

	// <editor-fold defaultstate="collapsed" desc="FakeValues">
	/**
	 * Generate FAke Values
	 */
	public static function fakeValues()
	{
		$categories = [
			['title' => 'Root Category', 'status' => 2, 'children' => [
					['title' => 'TV & Home Theather', 'status' => rand(0, 2)],
					['title' => 'Tablets & E-Readers', 'status' => rand(0, 2)],
					['title' => 'Computers', 'status' => rand(0, 2), 'children' => [
							['title' => 'Laptops', 'status' => rand(0, 2), 'children' => [
									['title' => 'PC Laptops', 'status' => rand(0, 2)],
									['title' => 'Macbooks (Air/Pro)', 'status' => rand(0, 2)]
								]
							],
							['title' => 'Desktops', 'status' => rand(0, 2), 'children' => [
									['title' => 'Towers Only', 'status' => rand(0, 2)],
									['title' => 'Desktop Packages', 'status' => rand(0, 2)],
									['title' => 'All-in-One Computers', 'status' => rand(0, 2)],
									['title' => 'Gaming Desktops', 'status' => rand(0, 2)]
								]
							]
						]
					],
					['title' => 'Cell Phones', 'status' => rand(0, 2)]
				]
			]
		];
		parent::buildTree($categories);
	}

	/**
	 * POST-Seeding event
	 * @param array $entity Entity Configuration
	 */
	public static function seedingEventPost($entity = [])
	{
		if(!empty($entity['pivot']))
		{
			return [];
		}
		self::fakeValues();
		return [];
	}

	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(3, true)),
			'status' => rand(0, 2),
		];
	}

	// </editor-fold>
	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		if(!empty($entity['pivot']))
		{
			$entity['table'] = [
				'name' => self::$nodeNamePrefix . '_category_pivot',
				'description' => 'Nodes-Categories Pivot Table',
				'pivotable' => ['entity' => self::$nodeNamePrefix, 'nested' => self::$nodeNamePrefix . '_category'],
				'orderable' => true,
			];
			return $entity;
		}
		$entity['table'] = [
			'name' => self::$nodeNamePrefix . '_category',
			'primaryKey' => 'category_id',
			'timestamp' => true,
			'softDelete' => true,
			'alphaId' => true,
			'nodeable' => true,
			'nesteable' => true,
			'optionable' => true,
			'sluggable' => true
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
			self::$nodeNamePrefix => [
				'entity' => self::$nodeNamePrefix,
				'type' => 'manytomany',
				'class' => [
					'method' => self::$nodeNamePrefix
				],
				'pivot' => self::$nodeNamePrefix . '_category_pivot',
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'category_id'
				],
			],
		];
		return $relations;
	}

}
