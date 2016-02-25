<?php

namespace Zbase\Ui;

/**
 * Zbase-Form Tab
 *
 * Tab Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Tab.php
 * @project Zbase
 * @package Zbase/Widgets
 *
 * $configuration.tabs
 */
use Zbase\Traits;
use Zbase\Ui as UIs;
use Zbase\Interfaces;

class Tabs extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * UI Type
	 * @var string
	 */
	protected $_type = 'tabs';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.tabs';

	/**
	 * Flag if this Tabs has an active tab
	 * @var boolean
	 */
	protected $_hasActiveTab = false;

	/**
	 * Collection of Tab
	 * @var UIs\Tab[]
	 */
	protected $_tabs = [];

	/**
	 * Check for a Tab
	 * @param \Zbase\UIs\Tab $tab
	 * @return boolean
	 */
	public function has(UIs\Tab $tab)
	{
		if(!empty($this->_tabs))
		{
			foreach ($this->_tabs as $t)
			{
				if($tab->id() == $t->id())
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Remove a Tab
	 * @param \Zbase\UIs\Tab $tab
	 * @return boolean
	 */
	public function remove(UIs\Tab $tab)
	{
		if(!empty($this->_tabs))
		{
			foreach ($this->_tabs as $k => $t)
			{
				if($tab->id() == $t->id())
				{
					unset($this->_tabs[$k]);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Add a Tab
	 * @param \Zbase\UIs\Tab $tab
	 * @return \Zbase\UIs\Tabs
	 */
	public function add(UIs\Tab $tab)
	{
		if($tab->isActive())
		{
			$this->_hasActiveTab = true;
		}
		$this->_tabs[] = $tab;
		return $this;
	}

	/**
	 * Return all the tabs
	 * @return UIs\Tab[]
	 */
	public function tabs()
	{
		return $this->_tabs;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-ui-' . $this->_type;
		$attr['id'] = 'zbase-ui-tabs-' . $this->getHtmlId();
		return $attr;
	}

	/**
	 * Prepare
	 * return void
	 */
	protected function _pre()
	{
		$this->_tabs = $this->sortPosition($this->_tabs);
		if(empty($this->_hasActiveTab) && !empty($this->_tabs))
		{
			zbase_collection($this->_tabs)->first()->setActive(true);
		}
		parent::_pre();
	}

}
