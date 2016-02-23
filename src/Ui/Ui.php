<?php

namespace Zbase\Ui;

/**
 * Zbase Ui
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Feb 23, 2016 4:18:45 PM
 * @file Ui.php
 * @project Zbase
 * @package Zbase\Ui
 */
class Ui
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
	 * The View File
	 * @var string
	 */
	protected $_viewFile = null;


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
		return zbase_data_get($this->getAttributes(), $key, $default);
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
	public function prepare()
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
	 * Set the View File
	 * @param string $viewFile
	 * @return \Zbase\Ui\Ui
	 */
	public function setViewFile($viewFile)
	{
		$this->_viewFile = $viewFile;
		return $this;
	}

	/**
	 * Set the UI Content
	 * @param string $content
	 * @return \Zbase\Ui\Ui
	 */
	public function setContent($content)
	{
		$this->_content = $content;
		return $this;
	}

	/**
	 * HTML the widget
	 * @return string
	 */
	public function __toString()
	{
		$this->_prepared();

		if(!empty($this->_viewString))
		{
			return strtr($this->_viewString, array(
				'{wrapperAttributes}' => $this->wrapperAttributes(),
				'{content}' => $this->content
			));
		}

		if(!is_null($this->_viewFile))
		{
			return zbase_view_render(zbase_view_file_contents($this->_viewFile), ['ui' => $this])->__toString();
		}
		return '';
	}

}
