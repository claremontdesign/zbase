<?php

namespace Zbase;

/**
 * Zbase Main
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Main.php
 * @project Zbase
 * @package Zbase
 */
use Zbase\Models;
use Zbase\Interfaces;

class Zbase implements Interfaces\ZbaseInterface, Interfaces\InstallCommandInterface, Interfaces\AssetsCommandInterface
{

	const ALERT_INFO = 'info';
	const ALERT_ERROR = 'error';
	const ALERT_WARNING = 'warning';
	const ALERT_SUCCESS = 'success';

	/**
	 * Commands
	 * @var array
	 */
	protected $commands = ['assets' => [], 'clear' => [], 'migrate' => [], 'install' => []];

	/**
	 * Current Widgets
	 * @return \Zbase\Widgets\WidgetInterface[]
	 */
	protected $widgets = [];

	/**
	 * Zbase Added packages
	 * @var array
	 */
	protected $packages = [];

	/**
	 * Current site section
	 * 	admin|front
	 * @var string
	 */
	protected $section = 'front';

	/**
	 * The Current Route Name
	 * @var string
	 */
	protected $currentRouteName = null;

	/**
	 * The Request
	 * @var Models\Request
	 */
	protected $request = null;

	/**
	 * Current Controller
	 * @var \Zbase\Interfaces\ControllerInterface
	 */
	protected $controller = null;

	/**
	 * @var Models\View
	 */
	protected $view = null;

	/**
	 * Collection of Entity Models
	 * @var array
	 */
	protected $entityModels = [];

	/**
	 * Return ViewModel
	 *
	 * @return Models\View
	 */
	public function view()
	{
		if(!$this->view instanceof Models\View)
		{
			$this->view = new Models\View;
			$this->view->start();
		}
		return $this->view;
	}

	/**
	 * Return the Request Model
	 * @return Models\Request
	 */
	public function request()
	{
		if(!$this->request instanceof Models\Request)
		{
			$this->request = new Models\Request;
			$this->request->start();
		}
		return $this->request;
	}

	/**
	 * Return the Entity Model of a given entityName
	 *
	 * @param string $entityName Entity name
	 * @return Zbase\Entity\Entity
	 */
	public function entity($entityName, $entityConfig = [])
	{
		if(!empty($this->entityModels[$entityName]))
		{
			return $this->entityModels[$entityName];
		}
		if(empty($entityConfig))
		{
			$entityConfig = zbase_config_get('entity.' . $entityName, []);
		}
		if(!empty($entityConfig))
		{
			$modelName = zbase_class_name(!empty($entityConfig['model']) ? $entityConfig['model'] : null);
			if(!empty($modelName))
			{
				return $this->entityModels[$entityName] = new $modelName();
			}
			throw new Exceptions\ConfigNotFoundException('Entity "model" configuration for "' . $entityName . '" not found in ' . __CLASS__);
		}
		throw new Exceptions\ConfigNotFoundException('Entity configuration for "' . $entityName . '" not found in ' . __CLASS__);
	}

	/**
	 * Return a Widget
	 * @param string $widgetName
	 * @return \Zbase\Widgets\WidgetInterface[] | null
	 */
	public function widget($widgetName)
	{
		if(!empty($this->widgets[$widgetName]))
		{
			return $this->widgets[$widgetName];
		}
		$widget = zbase_config_get('widgets.' . $widgetName, false);
		if(!empty($widget))
		{
			$w = new \Zbase\Widgets\Type\Form($widgetName, $widget);
			dd($w, $w->enabled());
			if($w->enabled())
			{
				return $this->widgets[$widgetName] = $w;
			}
		}
		return null;
	}

	/**
	 * Current Section
	 *
	 * @return string
	 */
	public function section()
	{
		return $this->section;
	}

	/**
	 * Set the current Section
	 * @param string $section
	 * @return \Zbase\Zbase
	 */
	public function setSection($section)
	{
		$this->section = $section;
		return $this;
	}

	/**
	 * REturn the Controller
	 * @return \Zbase\Interfaces\ControllerInterface
	 */
	public function controller()
	{
		return $this->controller;
	}

	/**
	 * Set the Current Controller
	 * @param \Zbase\Interfaces\ControllerInterface $controller
	 * @return \Zbase\Zbase
	 */
	public function setController(\Zbase\Interfaces\ControllerInterface $controller)
	{
		$this->controller = $controller;
		return $this;
	}

	/**
	 * Return the current route name
	 * @return string
	 */
	public function currentRouteName()
	{
		return $this->currentRouteName;
	}

	/**
	 * Set the Current Route Name
	 * @param string $currentRouteName
	 * @return \Zbase\Zbase
	 */
	public function setCurrentRouteName($currentRouteName)
	{
		$this->currentRouteName = $currentRouteName;
		return $this;
	}

	/**
	 * Add a packageName
	 * @param string $packageName
	 */
	public function addPackage($packageName)
	{
		if(!in_array($packageName, $this->packages()))
		{
			$this->packages[] = $packageName;
		}
	}

	/**
	 * Return all packages
	 * @return array
	 */
	public function packages()
	{
		return $this->packages;
	}

	/**
	 * Add a new Command
	 * @param string $type
	 * @param string $command
	 */
	public function addCommand($type, $command)
	{
		$this->commands[$type] = $command;
	}

	/**
	 * REturn configuration
	 */
	public function config()
	{
		return [];
	}

	/**
	 * Path to this package src
	 * @return string
	 */
	public function path()
	{
		return __DIR__ . '/../';
	}

	/**
	 * zbase installation
	 * @param string $phpCommand
	 */
	public function installCommand($phpCommand)
	{
		$this->__install($phpCommand);
	}

	public function assetsCommand($phpCommand)
	{
		$this->__install($phpCommand);
	}

	/**
	 * Install this package
	 */
	protected function __install($phpCommand)
	{

	}

}
