<?php

namespace Zbase\Models\Ui;

/**
 * Zbase-Model-Ui Tabs
 *
 * Model for the Ui and Tabs Collection
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Tabs.php
 * @project Zbase
 * @package Zbase/Model
 */
use Zbase\Ui as UIs;
use Zbase\Exceptions;

class Tabs
{

	/**
	 * Type of UI Collection
	 * @var string
	 */
	protected $_type = 'tabs';

	/**
	 * Collection of Tabs
	 * @var Zbase\UIs\Tab[]
	 */
	protected $tabs = null;

	public function __construct()
	{

	}

	/**
	 * Create a new Tab Collection
	 * @param id $collectionId
	 * @param array $configuration
	 * @return UIs\Tabs
	 */
	public function create($collectionId, $configuration)
	{
		$configuration['type'] = $this->_type;
		$ui = UIs\Ui::factory($configuration);
		if(!empty($this->tabs[$collectionId]))
		{
			throw new Exceptions\DuplicateIdException('Collection with the same id: ' . $collectionId . ' already exists.');
		}
		return $this->tabs[$collectionId] = $ui;
	}

	/**
	 * Add a tab to the Collection
	 * Proxy to UIs\Tabs::add()
	 * @param string|Zbase\UIs\Tabs $collection The Tab collection ID
	 * @param UIs\Tab $tab The Tab
	 * @return \Zbase\Models\Ui
	 */
	public function add($collection, UIs\Tab $tab)
	{
		$collectionId = $collection instanceof UIs\Tabs ? $collection->id() : $collection;
		$c = $this->get($collectionId, true);
		if($c !== false)
		{
			if(!empty($tab) && $c instanceof UIs\Tabs)
			{
				return $c->add($tab);
			}
		}
		return $this;
	}

	/**
	 * Check if $collectionId exists
	 * Proxy to UIs\Tabs::remove()
	 * @param string $collection The tabsId to check
	 * @param string|Zbase\UIs\Tab $tab Check if a certain tab is in the Collection
	 * @return boolean
	 */
	public function remove($collection, $tab = null)
	{
		$c = $this->get($collection);
		if($c !== false)
		{
			if(!empty($tab) && $c instanceof UIs\Tabs)
			{
				return $c->remove($tab);
			}
		}
		return false;
	}

	/**
	 * Check if $collectionId exists
	 * Proxy to UIs\Tabs::has()
	 * @param string $collection The tabsId to check
	 * @param string|Zbase\UIs\Tab $tab Check if a certain tab is in the Collection
	 * @return boolean
	 */
	public function has($collection, $tab = null)
	{
		$c = $this->get($collection);
		if($c !== false)
		{
			if(!empty($tab) && $c instanceof UIs\Tabs)
			{
				return $c->has($tab);
			}
			return true;
		}
		return false;
	}

	/**
	 * Return the collection by collectionId
	 * Proxy to UIs\Tabs::get()
	 * @param string $collection
	 * @return UIs\Tabs
	 */
	public function get($collection, $add = false)
	{
		if(!empty($this->tabs))
		{
			foreach ($this->tabs as $id => $tab)
			{
				if($id == $collection)
				{
					return $tab;
				}
			}
		}
		if(!empty($add))
		{
			if($collection instanceof UIs\Tabs)
			{
				$this->tabs[$collection->id()] = $collection;
			}
			else
			{
				$configuration = [
					'id' => $collection,
					'enable' => true
				];
				$collection = $this->create($collection, $configuration);
			}
			return $collection;
		}
		return false;
	}

}
