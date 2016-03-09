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
 * $configuration.group The Group Id (Ui\Tabs::id) where this tab will be belong
 * $configuration.contents
 * $configuration.contents.UiInterface|ContentInterface
 */
use Zbase\Traits;
use Zbase\Interfaces;
use Zbase\Ui as UIs;

class Tab extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * UI Type
	 * @var string
	 */
	protected $_type = 'tab';

	/**
	 * Element Label
	 * @var string
	 */
	protected $_label = null;

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.tab';

	/**
	 * The Group Id
	 * @var Ui\Tabs
	 */
	protected $_group = null;

	/**
	 * Tab is Active
	 * @var boolean
	 */
	protected $_active = false;

	/**
	 * REturn the Id
	 * @return type
	 */
	public function id()
	{
		return $this->_group->id() . '-' . $this->id;
	}

	/**
	 * Set label
	 * @param string $label
	 * @return \Zbase\Ui\Tab
	 */
	public function setLabel($label)
	{
		$this->_label = $label;
		return $this;
	}

	/**
	 * Return the label
	 * @return string
	 */
	public function label()
	{
		return $this->_label;
	}

	/**
	 * If Tab is Active or Not
	 * @param boolean $flag
	 * @return \Zbase\Ui\Tab
	 */
	public function setActive($flag)
	{
		$this->_active = $flag;
		return $this;
	}

	/**
	 * Check if Tab is Active or NOt
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->_active;
	}

	/**
	 *
	 * @param string|UIs\Tabs $group
	 * @return UIs\Tab
	 */
	public function setGroup($group)
	{
		$groupId = $group instanceof UIs\Tabs ? $group->id() : $group;
		$this->_group = zbase()->ui()->tabs()->get($groupId, true)->add($this);
		return $this;
	}

	/**
	 * Return the Group that this Tab belongs
	 * @return UIs\Tab
	 */
	public function group()
	{
		return $this->_group;
	}

	/**
	 * Prepare
	 * @return void
	 */
	protected function _pre()
	{
		$this->_contents = $this->sortPosition($this->_contents);
		parent::_pre();
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-ui-' . $this->_type;
		$attr['class'][] = 'tab-pane';
		$attr['class'][] = 'fade';
		if($this->isActive())
		{
			$attr['class'][] = 'active';
			$attr['class'][] = 'in';
		}
		$attr['id'] = $this->getHtmlId();
		return $attr;
	}
}
