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
		zbase_db_transaction_start();
		try
		{
			$parent = !empty($data['parent']) ? $data['parent'] : null;
			if(!empty($parent))
			{
				if($parent instanceof Interfaces\EntityInterface)
				{
					$parentNode = $parent;
				}
				else
				{
					$parentNode = $this->repository()->byId((int) $parent);
				}
				/**
				 * A value for parent was given but we cannot find the root.
				 */
				if(!$parentNode instanceof Interfaces\EntityInterface)
				{
					$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing the request for "%title%".', ['%title%' => $this->title, '%id%' => $this->id()]);
					return false;
				}
			}
			if($action == 'create' && strtolower($method) == 'post')
			{
				if(empty($parentNode))
				{
					$parentNode = self::root();
				}
				$this->nodeAttributes($data);
				$this->makeChildOf($parentNode);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Created "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				$this->nodeAttributes($data);
				$this->save();
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

	

	// </editor-fold>
}
