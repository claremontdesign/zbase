<?php

namespace Zbase\Entity\Laravel\Node\Traits;

/**
 * Zbase-Entity Zbase Entity Maker
 *
 * Filterable Entity
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Entity.php
 * @project Zbase
 * @package Zbase/Entity/Traits
 */
trait File
{

	/**
	 * The Action Messages
	 * @var array
	 */
	protected $_actionMessages = [];

	/**
	 * Return the Node ID
	 * @return string
	 */
	public function id()
	{
		return $this->node_id;
	}

	/**
	 * Return a messages based on the Action made
	 * @param boolean $flag
	 * @param string $action create|update|delete|restore|ddelete
	 * @return array
	 */
	public function getActionMessages($action)
	{
		if(!empty($this->_actionMessages[$action]))
		{
			return $this->_actionMessages[$action];
		}
		return [];
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
			if($action == 'create' && strtolower($method) == 'post')
			{
				$this->nodeAttributes($data);
				$this->save();
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
			if($action == 'delete')
			{
				if($this->hasSoftDelete())
				{
					$this->deleted_at = zbase_date_now();
					$this->save();
				}
				else
				{
					$this->delete();
				}
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
	 * Set the Node Attributes
	 * @param array $data
	 */
	public function nodeAttributes($data)
	{
		if(!empty($data['title']))
		{
			$this->title = $data['title'];
		}
	}

	/**
	 * Set the Title Attribute
	 * @param string $value
	 */
	public function setTitleAttribute($value)
	{
		$this->attributes['title'] = $value;
		$this->slug = $value;
	}

	// <editor-fold defaultstate="collapsed" desc="SLUG">
	// </editor-fold>

	/**
	 * Create a Log
	 * @param string $action
	 * @param array $options
	 * @return void
	 */
	public function log($action, $options = [])
	{

	}

	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(rand(3, 10), true)),
			'status' => rand(0, 2),
		];
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
		$columns['excerpt'] = [
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
		$columns['status'] = [
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
			'comment' => 'Status'
		];
		$columns['is_primary'] = [
			'filterable' => [
				'name' => 'primary',
				'enable' => true
			],
			'sortable' => [
				'name' => 'primary',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'unsigned' => true,
			'type' => 'boolean',
			'index' => true,
			'comment' => 'Is Primary'
		];
		$columns['filename'] = [
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'Filename'
		];
		$columns['url'] = [
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'URL'
		];
		$columns['filetype'] = [
			'hidden' => false,
			'length' => 32,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'File type'
		];
		$columns['mimetype'] = [
			'filterable' => [
				'name' => 'mimetype',
				'enable' => true
			],
			'sortable' => [
				'name' => 'mimetype',
				'enable' => true
			],
			'hidden' => false,
			'length' => 32,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'Mime Type'
		];
		$columns['size'] = [
			'filterable' => [
				'name' => 'size',
				'enable' => true
			],
			'sortable' => [
				'name' => 'size',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
			'comment' => 'File size in byte'
		];
		return $columns;
	}

}
