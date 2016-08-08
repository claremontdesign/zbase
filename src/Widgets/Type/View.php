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

class View extends Widgets\Widget implements Widgets\WidgetInterface, Widgets\ControllerInterface
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'view';

	/**
	 * The UI View File
	 * @var string
	 */
	protected $_viewFile = 'ui.view';

	/**
	 * The Layout to use: default
	 * @var string
	 */
	protected $_viewLayout = 'default';

	/**
	 * Widget View File
	 * @var string
	 */
	protected $_widgetView = null;

	/**
	 *
	 * @var array
	 */
	protected $_viewParams = [];

	/**
	 * Prepare
	 */
	protected function _pre()
	{
		parent::_pre();
		$this->entity();
	}

	/**
	 *
	 * @param array $view View Properties
	 */
	public function setView($view)
	{
		if(!empty($view['file']))
		{
			$this->_widgetView = zbase_value_get($view, 'file');
			$this->setViewFile($this->_widgetView);
		}
		if(!empty($view['layout']))
		{
			$this->_viewLayout = $view['layout'];
		}
		if(!empty($view['params']))
		{
			$this->_viewParams = $view['params'];
		}
	}

	public function widgetViewFile()
	{
		return $this->_widgetView;
	}

	public function viewLayout()
	{
		if(!empty($this->_viewLayout) && $this->_viewLayout != 'default')
		{
			return $this->_viewLayout;
		}
		return null;
	}

	public function viewParams()
	{
		return $this->_viewParams;
	}

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{
		if($action == 'post')
		{
			$action = 'create';
		}
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
