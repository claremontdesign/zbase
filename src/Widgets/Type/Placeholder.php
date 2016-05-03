<?php

namespace Zbase\Widgets\Type;

/**
 * Zbase-Widgets Widget-Type TreeView
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file TreeView.php
 * @project Zbase
 * @package Zbase/Widgets/Type
 *
 * https://github.com/jonmiles/bootstrap-treeview
 * http://jonmiles.github.io/bootstrap-treeview/#grandchild1
 *
 */
use Zbase\Widgets;

class Placeholder extends Widgets\Widget implements Widgets\WidgetInterface, Widgets\ControllerInterface
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'placeholder';

	/**
	 * Placeholder name
	 * @var string
	 */
	protected $_placeholder = null;

	/**
	 * Prepare
	 */
	protected function _pre()
	{
		parent::_pre();
		$this->entity();
	}

	public function setPlaceholder($placeholder)
	{
		$this->_placeholder = $placeholder;
	}

	/**
	 * Return the Placeholder
	 * @return type
	 */
	public function getPlaceholder()
	{
		return $this->_placeholder;
	}

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{
		$this->_action = $action;
		if(empty($this->_entity()) && $this->isEntityNeeded())
		{
			return zbase_abort(404);
		}
	}

	public function validateWidget($action)
	{

	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-widget-wrapper';
		$attr['id'] = 'zbase-widget-wrapper-' . $this->id();
		return $attr;
	}

}
