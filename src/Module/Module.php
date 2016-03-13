<?php

namespace Zbase\Module;

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 6, 2016 12:07:18 AM
 * @file Module.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 * module.id = unique id
 * module.enable = enable/disable
 * module.access = access level
 * module.class = The classname to use
 * module.backend = true|false
 * module.frontend = true|false
 * module.url.front = the front URL key
 * module.url.back = the back URL key; default to module.id
 *
 * Add: zbase()->addModule('module', module[]);
 * Get: zbase()->module('module');
 * Get All: zbase()->modules()
 *
 * @TODO
 * modules.module.controller
 * modules.module.controller.class = If you want to call an action, this is required; else, will load the default module controller
 * modules.module.controller.action = will be called, do whatever you want with the action. be sure to define a controller.class value
 * modules.module.controller.action.view = Will load the value of this returning a view() response
 *
 * controller will be check first, then widgets.
 *
 * modules.module.widgets
 * modules.module.widgets.controller
 * modules.module.widgets.controller = array|string; Widget/s will be loaded when actions will is defined, unless specific action is defined.
 * modules.module.widgets.controller.actionName = array|string; widgets[]|widget, will be loaded;
 * modules.module.widgets.controller.actionName.widgetId = null (will check from added widget)
 * modules.module.widgets.controller.actionName.widgetId = PATH_TO_WIDGET (will load widget and configuration by the path provided)
 */
use Zbase\Traits;
use Zbase\Interfaces;

class Module implements ModuleInterface, Interfaces\AttributeInterface
{

	use Traits\Attribute;

	/**
	 * Module Configuration
	 * @var array
	 */
	protected $configuration = [];

	/**
	 * User Has Access Flag
	 * @var boolean
	 */
	protected $hasAccess = null;

	/**
	 * Disable/Enable
	 * @var booleaan
	 */
	protected $isEnable = null;

	public function __construct($configuration = null)
	{
		$this->setConfiguration($configuration);
	}

	/**
	 * This module has a backend interface
	 * Route will be created dynamically
	 * This module can be accessed: /admin/$moduleId()
	 */
	public function hasBackend()
	{
		return (bool) $this->_v('backend', false);
	}

	/**
	 * Module can be accessed via frontend
	 * Route will be created dynamically
	 * Url: domain.com/$moduleId()
	 */
	public function hasFrontend()
	{
		return (bool) $this->_v('frontend', false);
	}

	/**
	 * The URL Key per section
	 * default: /$moduleId()/$action/$record/$task
	 * @param string $section
	 * @return string
	 */
	public function sectionUrl($section = 'frontend')
	{
		if($section == 'backend')
		{
			return $this->_v('url.backend', $this->id());
		}
		return $this->_v('url.frontend', $this->id());
	}

	/**
	 * Create a URL based from params
	 * @param string $section The Section to generate the route
	 * @param array $params Params to generate route
	 * @return string
	 */
	public function url($section, $params)
	{
		if($section == 'backend' || $section == 'back')
		{
			$adminKey = zbase_config_get('routes.adminkey.key', 'admin');
			$routeName = $adminKey . '.' . $this->id();
		}
		else
		{
			$routeName = $this->id();
		}
		return zbase_url_from_route($routeName, $params);
	}

	/**
	 * If backend is enabled
	 */
	public function isEnable()
	{
		if(is_null($this->isEnable))
		{
			$this->isEnable = (bool) $this->_v('enable');
		}
		return $this->isEnable;
	}

	/**
	 * If current user has access
	 */
	public function hasAccess()
	{
		if(is_null($this->hasAccess))
		{
			$this->hasAccess = zbase_auth_check_access($this->_v('access'));
		}
		return $this->hasAccess;
	}

	/**
	 * Enabled and Has Access
	 * @return boolean
	 */
	public function isEnableAndAccessible()
	{
		return $this->isEnable() && $this->hasAccess();
	}

	/**
	 * Module unique ID/name
	 * @return string
	 */
	public function id()
	{
		return $this->_v('id');
	}

	/**
	 * Module title
	 * @return string
	 */
	public function title()
	{
		return ucfirst($this->_v('meta.title', $this->id()));
	}

	/**
	 * Module Description
	 * @return string
	 */
	public function description()
	{
		return $this->_v('meta.description', null);
	}

	/**
	 * Return Widgets by Controller Action
	 * @param string $action The Controller Action
	 * @return \Zbase\Widgets\WidgetInterface[]
	 */
	public function widgetsByControllerAction($action)
	{
		$widgets = $this->_v('widgets.controller.' . $action, []);
		if(is_array($widgets))
		{
			foreach ($widgets as $name => $path)
			{
				if(is_string($path) && zbase_file_exists($path))
				{
					$config = require $path;
				}
				if(!empty($config) && is_array($config))
				{
					if(empty($config['id']))
					{
						$config['id'] = $name;
					}
					$widget = zbase_widget(['id' => $name, 'config' => $config]);
				}
				if(is_null($path))
				{
					$widget = zbase()->widget($name);
				}
				if($widget instanceof \Zbase\Widgets\WidgetInterface)
				{
					$widget->setModule($this);
					$widgets[$name] = $widget;
				}
			}
			return $widgets;
		}
		return null;
	}

	// <editor-fold defaultstate="collapsed" desc="CONFIGURATION">
	/**
	 * Set The Module Configuration
	 * @param array $configuration
	 * @return \Zbase\Module\Module
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
		return $this;
	}

	/**
	 * Return the Module Configuration
	 * @return array
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	/**
	 * Retrieves a value from the configuration
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function _v($key, $default = null)
	{
		return zbase_data_get($this->getConfiguration(), $key, $default);
	}

	// </editor-fold>
}
