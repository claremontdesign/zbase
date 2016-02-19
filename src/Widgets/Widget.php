<?php

namespace Zbase\Widgets;

/**
 * Zbase-Widgets Widget
 *
 * Widget base model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Widget.php
 * @project Zbase
 * @package Zbase/Widgets
 */
use Zbase\Interfaces;
use Zbase\Exceptions;
use Zbase\Traits;

class Widget
{

	/**
	 * Widget prepared flag
	 * @var boolean
	 */
	protected $_prepared = false;

	/**
	 * is Enabled?
	 * @var boolean
	 */
	protected $_enable = null;

	/**
	 * Has Access?
	 * @var boolean
	 */
	protected $_hasAccess = null;

	/**
	 * The Widget ID
	 * @var string
	 */
	protected $_widgetId = null;

	/**
	 * The Widget configuration
	 * widgets[widgetId][config] = []
	 * @var array
	 */
	protected $_configuration = [];

	/**
	 * Constructor
	 * @param string $widgetId
	 * @param array $configuration
	 */
	public function __construct($widgetId, $configuration)
	{
		$this->_widgetId = $widgetId;
		$this->_configuration = $configuration;
	}

	/**
	 * Check if widget is enabled or disable
	 * 	based on the configuration index:enable
	 * 	default: true
	 * @return boolean
	 */
	public function enabled()
	{
		if(is_null($this->_enable))
		{
			if($this->hasAccess() && !empty($this->_widgetId))
			{
				$this->_enable = $this->_v('enable', true);
			}
			else
			{
				$this->_enable = false;
			}
		}
		return $this->_enable;
	}

	/**
	 * Check if current user has access to this widget
	 * 	string|array
	 * 	string: minimum|admin
	 * 		"minimum" is the minimum role for the current section, else a role name or array of role names
	 * 	array: [admin, user]
	 * 		if array, current user role should be one in the array
	 * @return boolean
	 */
	public function hasAccess()
	{
		if(is_null($this->_hasAccess))
		{
			$this->_hasAccess = zbase_auth_check_access($this->_v('access', zbase_auth_minimum()));
		}
		return $this->_hasAccess;
	}

	/**
	 * Retrieves a value from the configuration
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function _v($key, $default = null)
	{
		return zbase_data_get($this->_configuration, $key, $default);
	}

	/**
	 * PreParation
	 * @return void
	 */
	protected function _pre()
	{

	}

	/**
	 * PostPreparation
	 * @return void
	 */
	protected function _post()
	{

	}

	/**
	 * Prepare the widget
	 * @return void
	 */
	protected function _prepared()
	{
		if(empty($this->_prepared))
		{
			$this->_prepared = true;
			if($this->enabled())
			{
				$this->_pre();
				$this->_post();
			}
		}
	}

	/**
	 * HTML the widget
	 * @return string
	 */
	public function __toString()
	{
		$this->_prepared();
	}

}
