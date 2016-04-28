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
trait BaseNode
{

	/**
	 * The Action Messages
	 * @var array
	 */
	protected $_actionMessages = [];

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
		dd($action, $data);
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
	 * Create a Log
	 * @param string $action
	 * @param array $options
	 * @return void
	 */
	public function log($action, $options = [])
	{

	}

}
