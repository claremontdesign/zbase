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
use Zbase\Exceptions;

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
	 * Zbase packages
	 * @var array
	 */
	protected $packages = [];

	/**
	 * Modules
	 * @return \Zbase\Module\ModuleInterface[]
	 */
	protected $modules = [];

	/**
	 * Widgets
	 * @return \Zbase\Widgets\WidgetInterface[]
	 */
	protected $widgets = [];

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
	 *
	 * @var \Zbase\Models\Mobile
	 */
	protected $mobile = null;

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
	 *
	 * @var Models\Ui
	 */
	protected $ui = null;

	/**
	 * Collection of Entity Models
	 * @var array
	 */
	protected $entityModels = [];

	/**
	 * Return the MobileUtility
	 * @retur \Zbase\Models\Mobile
	 */
	public function mobile()
	{
		if(!$this->mobile instanceof \Zbase\Models\Mobile)
		{
			$this->mobile = new \Zbase\Models\Mobile;
		}
		return $this->mobile;
	}

	/**
	 * Return ViewModel
	 *
	 * @return Models\View
	 */
	public function view()
	{
		if(!$this->view instanceof Models\View)
		{
			$className = zbase_model_name('view', null, '\Zbase\Models\View');
			$this->view = new $className;
			$this->view->start();
		}
		return $this->view;
	}

	/**
	 * Return UiModel
	 *
	 * @return Models\View
	 */
	public function ui()
	{
		if(!$this->ui instanceof Models\Ui)
		{
			$className = zbase_model_name('ui', null, '\Zbase\Models\Ui');
			$this->ui = new $className;
		}
		return $this->ui;
	}

	/**
	 * Return the Request Model
	 * @return Models\Request
	 */
	public function request()
	{
		if(!$this->request instanceof Models\Request)
		{
			$className = zbase_model_name('request', null, 'Models\Request');
			$this->request = new $className;
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
	 * Add a module
	 * 	Module will be created only f they are called.
	 * @param string $path Path to module folder with a module.php returning an array
	 * @retur Zbase
	 */
	public function addModule($name, $path)
	{
		if(zbase_file_exists($path . '/module.php'))
		{
			$config = require zbase_directory_separator_fix($path . '/module.php');
			$name = !empty($config['id']) ? $config['id'] : null;
			if(empty($name))
			{
				throw new Exceptions\ConfigNotFoundException('Module configuration ID not found.');
			}
			if(!empty($name))
			{
				if(empty($this->modules[$name]))
				{
					$this->modules[$name] = $config;
				}
				return $this;
			}
		}
		throw new Exceptions\ConfigNotFoundException('Module ' . $path . ' folder or ' . zbase_directory_separator_fix($path . '/module.ph') . ' not found.');
	}

	/**
	 * Return Module
	 * @param string $name
	 * @return null|\Zbase\Module\ModuleInterface
	 */
	public function module($name)
	{
		if(!empty($this->modules[$name]))
		{
			if(!$this->modules[$name] instanceof \Zbase\Module\ModuleInterface)
			{
				$moduleClassname = !empty($this->modules[$name]['class']) ? $this->modules[$name]['class'] : zbase_model_name('module', 'class.module', \Zbase\Module\Module::class);
				$module = new $moduleClassname;
				$module->setConfiguration($this->modules[$name]);
				$this->modules[$name] = $module;
			}
			if($this->modules[$name] instanceof \Zbase\Module\ModuleInterface)
			{
				return $this->modules[$name];
			}
		}
		return null;
	}

	/**
	 * Modules can be added automatically by providing a path to the modules
	 * EAch module folder should have at least a module.php file
	 * @param string $path Folders to module
	 */
	public function loadModuleFrom($path)
	{
		if(zbase_directory_check($path))
		{
			$folders = zbase_directories($path);
			if(!empty($folders))
			{
				foreach ($folders as $folder)
				{
					$this->addModule(basename($folder), $folder);
					if(zbase_directory_check($folder . '/widgets'))
					{
						$this->loadWidgetFrom($folder . '/widgets');
					}
				}
			}
		}
	}

	/**
	 * Return all packages
	 * @return array
	 */
	public function modules()
	{
		return $this->modules;
	}

	/**
	 *
	 */
	public function loadWidgetFrom($path)
	{
		if(zbase_directory_check($path))
		{
			$files = zbase_directory_files($path);
			if(!empty($files))
			{
				foreach ($files as $file)
				{
					$widget = require $file;
					if(empty($widget['id']))
					{
						$widget['id'] = str_replace('.php', '', basename($file));
					}
					$this->widget($widget);
				}
			}
		}
	}

	/**
	 * Return/Create a Widget
	 * @param string|array $widget
	 * @param boolean $clone Will clone if widget was already created
	 * @return \Zbase\Widgets\WidgetInterface[] | null
	 */
	public function widget($widget, $clone = null)
	{
		if(is_array($widget))
		{
			$name = !empty($widget['id']) ? $widget['id'] : null;
			$config = !empty($widget['config']) ? $widget['config'] : [];
			$type = !empty($widget['type']) ? $widget['type'] : [];
		}
		else
		{
			$name = $widget;
		}
		if(!empty($name))
		{
			if(!empty($this->widgets[$name]))
			{
				if(!empty($clone))
				{
					return clone $this->widgets[$name];
				}
				return $this->widgets[$name];
			}
			if(empty($config))
			{
				$config = zbase_config_get('widgets.' . $name, false);
			}
			if(!empty($config))
			{
				if(strtolower($type) == 'form')
				{
					$w = new \Zbase\Widgets\Type\Form($name, $config);
				}
				if(strtolower($type) == 'datatable')
				{
					$w = new \Zbase\Widgets\Type\Datatable($name, $config);
				}
				if(strtolower($type) == 'treeview')
				{
					$w = new \Zbase\Widgets\Type\TreeView($name, $config);
				}
				if($w instanceof \Zbase\Widgets\WidgetInterface)
				{
					if($w->enabled())
					{
						return $this->widgets[$name] = $w;
					}
				}
			}
		}
		return null;
	}

	/**
	 * Return all widgets
	 * @return array
	 */
	public function widgets()
	{
		return $this->widgets;
	}

	// <editor-fold defaultstate="collapsed" desc="PACKAGES">
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

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="CONTROLLER">
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
	 * REturn the Controller
	 * @return \Zbase\Interfaces\ControllerInterface
	 */
	public function controller()
	{
		return $this->controller;
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

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="SECTION">
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

	// </editor-fold>

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
