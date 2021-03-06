<?php

namespace Zbase;

ini_set('max_execution_time', 300);

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

class Zbase implements Interfaces\ZbaseInterface, Interfaces\InstallCommandInterface, Interfaces\AssetsCommandInterface, Interfaces\ClearCommandInterface, Interfaces\TestCommandInterface
{

	const ALERT_INFO = 'info';
	const ALERT_ERROR = 'error';
	const ALERT_WARNING = 'warning';
	const ALERT_SUCCESS = 'success';

	/**
	 * Just return a success json response
	 */
	const RESPONSE_JSON = 'json';

	/**
	 * Commands
	 * @var array
	 */
	protected $commands = ['assets' => [], 'clear' => [], 'migrate' => [], 'install' => []];

	/**
	 * the Console command
	 * @var Command
	 */
	protected $consoleCommand = null;

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
	 *
	 * @var \Zbase\Models\System
	 */
	protected $system = null;

	/**
	 *
	 * @var \Zbase\Models\Telegram
	 */
	protected $telegram = null;

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
	 * Json
	 * @var Json
	 */
	protected $json = [];

	/**
	 * The Response format
	 * default: html
	 * @var string html|xml|json
	 */
	protected $responseFormat = 'html';

	/**
	 *
	 * @var Models\Ui
	 */
	protected $ui = null;
	protected $auth = null;

	/**
	 * Collection of Entity Models
	 * @var array
	 */
	protected $entityModels = [];

	/**
	 * Return the response format
	 * @return string
	 */
	public function getResponseFormat()
	{
		return $this->responseFormat;
	}

	/**
	 * SEt the response format
	 * @param string $responseFormat The response format json|xml|html
	 * @return \Zbase\Zbase
	 */
	public function setResponseFormat($responseFormat = 'html')
	{
		$this->responseFormat = $responseFormat;
		return $this;
	}

	public function setAuth($auth)
	{
		$this->auth = $auth;
	}

	public function getAuth()
	{
		return $this->auth;
	}

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
	 * Return the SystemUtility
	 * @retur \Zbase\Models\System
	 */
	public function system()
	{
		if(!$this->system instanceof \Zbase\Models\System)
		{
			$this->system = new \Zbase\Models\System;
		}
		return $this->system;
	}

	/**
	 * Return the SystemUtility
	 * @retur \Zbase\Models\System
	 */
	public function telegram()
	{
		if(!$this->telegram instanceof \Zbase\Models\Telegram)
		{
			$this->telegram = new \Zbase\Models\Telegram;
		}
		return $this->telegram;
	}

	/**
	 * Return the JSON View Model
	 * @return type
	 */
	public function json()
	{
		if(!$this->json instanceof Models\Json)
		{
			$className = zbase_model_name('view', null, '\Zbase\Models\Json');
			$this->json = new $className;
			$this->json->start();
		}
		return $this->json;
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
	 * @param array $entityConfig EntityConfiguration
	 * @param boolean|string $newInstance will create new instance.
	 * @return Zbase\Entity\Entity
	 */
	public function entity($entityName, $entityConfig = [], $newInstance = true)
	{
		if(empty($newInstance))
		{
			if(!empty($this->entityModels[$entityName]))
			{
				return $this->entityModels[$entityName];
			}
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
				if(!empty($newInstance))
				{
					return new $modelName();
				}
				return $this->entityModels[$entityName] = new $modelName();
			}
			throw new Exceptions\ConfigNotFoundException('Entity "model" configuration for "' . $entityName . '" not found in ' . __CLASS__);
		}
		//$value = app()['config']['entity'];
		//dd($value, zbase_config_get('entity'), $entityName, $entityConfig);
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
				$enable = zbase_data_get($config, 'enable');
				if(empty($this->modules[$name]) && $enable)
				{
					$config['path'] = $path;
					$this->modules[$name] = $config;
				}
				return $this;
			}
		}
		// throw new Exceptions\ConfigNotFoundException('Module ' . $path . ' folder or ' . zbase_directory_separator_fix($path . '/module.ph') . ' not found.');
	}

	/**
	 * Return Module
	 * @param string $name
	 * @param Module|array $config
	 * @return null|\Zbase\Module\ModuleInterface
	 */
	public function module($name, $config = null)
	{
		if($config instanceof \Zbase\Module\ModuleInterface)
		{
			return $config;
		}
		if(!empty($this->modules[$name]))
		{
			if(!$this->modules[$name] instanceof \Zbase\Module\ModuleInterface)
			{
				$moduleClassname = !empty($this->modules[$name]['class']) ? $this->modules[$name]['class'] : zbase_model_name('module', 'class.module', \Zbase\Module\Module::class);
				$module = new $moduleClassname;
//				if($module->isEnable() && $module->hasAccess())
//				{
//					$module->setConfiguration($this->modules[$name]);
//					$this->modules[$name] = $module;
//				}
//				else
//				{
//					unset($this->modules[$name]);
//				}
				$module->setConfiguration($this->modules[$name]);
				$this->modules[$name] = $module;
			}
			if(!empty($this->modules[$name]) && $this->modules[$name] instanceof \Zbase\Module\ModuleInterface)
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
	 * Prepare Widgets
	 *
	 * @return void
	 */
	public function prepareWidgets()
	{
		foreach ($this->modules as $name => $m)
		{
			$m = $this->module($name, $m);
			if(!empty($m))
			{
				if(zbase_directory_check($m->getPath() . '/widgets'))
				{
					$this->loadWidgetFrom($m->getPath() . '/widgets');
				}
			}
		}
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
						$name = str_replace('.php', '', basename($file));
						$widget['id'] = $name;
					}
					if(!empty($widget['type']) && !is_array($widget['type']) && strtolower($widget['type']) == 'placeholder')
					{
						$this->widget($name, $widget);
					}
					else
					{
						$this->widgets[$name] = $widget;
					}
				}
			}
		}
	}

	/**
	 * Return/Create a Widget
	 * @param string|array $widget
	 * @param array $config Configuration
	 * @param boolean $clone Will clone if widget was already created
	 * @param array $overrideConfig Confiuration will be overriden
	 *
	 * @return \Zbase\Widgets\WidgetInterface[] | null
	 */
	public function widget($widget, $config = [], $clone = null, $overrideConfig = [])
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
			if(!empty($this->widgets[$name]) && $this->widgets[$name] instanceof \Zbase\Widgets\WidgetInterface)
			{
				if(!empty($clone))
				{
					return clone $this->widgets[$name];
				}
				return $this->widgets[$name];
			}
			if(empty($config))
			{
				if(is_array($this->widgets[$name]))
				{
					$config = $this->widgets[$name];
				}
			}
			if(!empty($config))
			{
				if(!empty($overrideConfig))
				{
					$config = array_replace_recursive($config, $overrideConfig);
				}
				$name = !empty($config['id']) ? $config['id'] : null;
				$type = !empty($config['type']) ? $config['type'] : [];
				$config = !empty($config['config']) ? $config['config'] : [];
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
				if(strtolower($type) == 'view')
				{
					$w = new \Zbase\Widgets\Type\View($name, $config);
				}
				if(strtolower($type) == 'placeholder')
				{
					$w = new \Zbase\Widgets\Type\Placeholder($name, $config);
				}
				if(strtolower($type) == 'db')
				{
					$w = new \Zbase\Widgets\Type\Db($name, $config);
				}
				if(!empty($w) && $w instanceof \Zbase\Widgets\WidgetInterface)
				{
					if($w->enabled())
					{
						if(strtolower($type) == 'placeholder')
						{
							zbase_view_placeholder_add($w->getPlaceholder(), $w->id(), $w);
						}
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

	/**
	 * Return the merged configuration of packages
	 *
	 * @return array
	 */
	public function getPackagesMergedConfigs()
	{
		$packages = $this->packages();
		$configs = [];
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$configFiles = zbase_package($packageName)->config();
				$packagePath = zbase_package($packageName)->path();
				if(is_array($configFiles))
				{
					foreach ($configFiles as $configFile)
					{
						if(file_exists($configFile))
						{
							$configs = array_replace_recursive($configs, require $configFile);
						}
					}
				}
				else
				{
					$configs = array_replace_recursive($configs, require $configFiles);
				}
			}
		}
		return $configs;
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
	 * The Console Command
	 * @param type $command
	 */
	public function setConsoleCommand($command)
	{
		$this->consoleCommand = $command;
	}

	/**
	 * Return the Console Command
	 * @return type
	 */
	public function consoleCommand()
	{
		return $this->consoleCommand;
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
	public function installCommand($phpCommand, $options = [])
	{
		echo "Copying zbase files to Laravel app\n";
		zbase_file_copy_folder(__DIR__ . '/../dummy/install/', zbase_app_path('../'));
	}

	public function assetsCommand($phpCommand, $options = [])
	{

	}

	public function clearCommand($phpCommand, $options = [])
	{

	}

	public function testCommand($phpCommand, $options = [])
	{

	}

}
