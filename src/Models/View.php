<?php

namespace Zbase\Models;

/**
 * Zbase-Model-View
 *
 * Model for the Theme,Templates and anything for the view
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file View.php
 * @project Zbase
 * @package Zbase/Model
 */
use Zbase\Interfaces;
use Zbase\Exceptions;

class View
{

	const HEADMETA = 'headMeta';
	const HEADLINK = 'headLink';
	const STYLESHEET = 'stylesheet';
	const JAVASCRIPT = 'javascript';
	const SCRIPT = 'script';
	const STYLE = 'style';
	const NAVIGATION = 'navigation';

	/**
	 * Has Prepared?
	 * @var boolean
	 */
	protected $prepared = false;

	/**
	 * The HEAD Title
	 * @var string|array
	 */
	protected $pageTitle = null;

	/**
	 * The Title (H1)
	 * @var string
	 */
	protected $title = null;

	/**
	 * SubTitle
	 * @var string
	 */
	protected $subTitle = null;

	/**
	 * Set the page metas like description, keyword, title
	 * @var Zbase\Models\View\HeadMeta[]
	 */
	protected $headMeta = [];

	/**
	 *
	 * @var Zbase\Models\View\HeadLink[]
	 */
	protected $headLink = [];

	/**
	 *
	 * @var Zbase\Models\View\Stylesheet[]
	 */
	protected $stylesheet = [];

	/**
	 *
	 * @var Zbase\Models\View\Javascript[]
	 */
	protected $javascript = [];

	/**
	 *
	 * @var Zbase\Models\View\Script[]
	 */
	protected $script = [];

	/**
	 *
	 * @var Zbase\Models\View\Style[]
	 */
	protected $style = [];

	/**
	 *
	 * @var Zbase\Models\View\Navigation[]
	 */
	protected $navigation = [];

	/**
	 * Breadcrumb
	 * @var array
	 */
	protected $breadcrumb = [];
	protected $variable = [];

	/**
	 * Placeholders
	 *
	 * @var array
	 */
	protected $placeholders = [];

	public function __construct()
	{

	}

	/**
	 * Start
	 */
	public function start()
	{
		$this->_setPlugins();
		$this->_setDefaults();
	}

	/**
	 * Set the pagetitle
	 *
	 * @param string|array $pageTitle
	 * 	If array is given, the first index is the pageTitle and the second is the pageSubTitle
	 *
	 * @return \Zbase\Models\View
	 */
	public function setPageTitle($pageTitle)
	{
		if($pageTitle instanceof \Closure)
		{
			$this->pageTitle = $pageTitle();
		}
		else
		{
			$this->pageTitle = $pageTitle;
		}
		return $this;
	}

	/**
	 * SEt the Title and Subtitle
	 * @param type $title
	 * @param type $subTitle
	 * @return \Zbase\Models\View
	 */
	public function setTitle($title, $subTitle = null)
	{
		$this->title = $title;
		$this->subTitle = $subTitle;
		return $this;
	}

	/**
	 * Return the Title
	 * @return string
	 */
	public function title()
	{
		if(empty($this->title))
		{
			return $this->pageTitle;
		}
		return $this->title;
	}

	/**
	 * Return the SubTitle
	 * @return string
	 */
	public function subTitle()
	{
		return $this->subTitle;
	}

	/**
	 *
	 * @see setPageTitle()
	 */
	public function pageTitle()
	{
		$prefix = zbase_config_get('view.default.title.prefix', 'Zbase');
		$suffix = zbase_config_get('view.default.title.suffix', null);
		$separator = zbase_config_get('view.default.title.separator', ' | ');
		$arr = [];
		if(!is_null($prefix))
		{
			$arr[] = $prefix;
		}
		if(!empty($this->pageTitle))
		{
			$arr[] = $this->pageTitle;
		}
		if(!is_null($suffix))
		{
			$arr[] = $suffix;
		}
		return implode($separator, $arr);
	}

	/**
	 * @see class::add()
	 */
	public function set($type, $config)
	{
		return $this->add($type, $config);
	}

	/**
	 * Add multiple view
	 *
	 * @param type $configs
	 */
	public function multiAdd($configs)
	{
		if(!empty($configs))
		{
			foreach ($configs as $id => $config)
			{
				foreach ($config as $k => $v)
				{
					$config[$k] = zbase_data_get($config, $k);
				}
				if(!empty($config['type']))
				{
					if($config['type'] == self::SCRIPT)
					{
						$config['script'] = str_replace(array('<script type="text/javascript">', '</script>', '<style type="text/css">', '</style>'), '', $config['script']);
					}
					if($config['type'] == self::STYLE)
					{
						$config['style'] = str_replace(array('<style type="text/css">', '</style>'), '', $config['style']);
					}
					$config['id'] = $id;
					$this->add($config['type'], $config);
				}
			}
		}
	}

	/**
	 * Add a new View element
	 *
	 * @param string $type
	 * @param array $config
	 * @return object
	 */
	public function add($type, $config = [], $group = null)
	{
		if($this->_hasKey($type))
		{
			$typeName = zbase_string_camel_case($type);
			$className = 'Zbase\Models\View\\' . ucfirst($typeName);
			$class = zbase_config_get('class.view.' . strtolower($typeName) . '.class', $className);
			// dd($type, $this->_hasKey($type), $className, $class);
			if(class_exists($class))
			{
				$object = new $class($config);
				if($object instanceof Interfaces\StatusInterface && !$object->enabled())
				{
					return null;
				}
				if($object instanceof Interfaces\AuthInterface && !$object->hasAccess())
				{
					return null;
				}
				if($object instanceof Interfaces\IdInterface)
				{
					if(!empty($group))
					{
						$this->{$typeName}[$group][$object->getId()] = $object;
					}
					else
					{
						$this->{$typeName}[$object->getId()] = $object;
					}
					return $object;
				}
			}
		}
		return null;
	}

	/**
	 * Retrieve a View Element by $name
	 *
	 * @param string $type
	 * @param string $id
	 * @return object
	 */
	public function get($type, $id, $group = null)
	{
		if($this->_hasKey($type) && $this->has($type, $id, $group))
		{
			$typeName = zbase_string_camel_case($type);
			if(!empty($group))
			{
				return $this->{$typeName}[$group][$id];
			}
			return $this->{$typeName}[$id];
		}
		return null;
	}

	/**
	 * Check if a View Element exists
	 *
	 * @param string $type
	 * @param string $id
	 * @return boolean
	 */
	public function has($type, $id, $group = null)
	{
		$typeName = zbase_string_camel_case($type);
		if(!empty($group))
		{
			if(!empty($this->{$typeName}[$group][$id]))
			{
				return true;
			}
			return false;
		}
		if(!empty($this->{$typeName}[$id]))
		{
			return true;
		}
		return false;
	}

	/**
	 * Render View Elements
	 *
	 * @param string $type
	 * @return string
	 */
	public function render($type, $group = null)
	{
		$str = '';
		if($this->_hasKey($type))
		{
			$all = $this->all($type, $group);
			if($type == self::NAVIGATION)
			{
				dd($all);
			}
			if(!empty($all))
			{
				foreach ($all as $object)
				{
					$str .= EOF . $object;
				}
			}
		}
		return $str;
	}

	/**
	 * Retrive all View Elements of a certain $type
	 *
	 * @param string $type
	 * @return array
	 */
	public function all($type, $group = null)
	{
		if($this->_hasKey($type))
		{
			$typeName = zbase_string_camel_case($type);
			if(!empty($group))
			{
				if($type == self::NAVIGATION)
				{
					return zbase_collection($this->{$typeName}[$group])->sortBy(function($view){
								if($view instanceof Interfaces\PositionInterface)
								{
									return $view->position();
								}
						})->all();
				}
			}
			return zbase_collection($this->$typeName)->sortByDesc(function($view){
						if($view instanceof Interfaces\PositionInterface)
						{
							return $view->position();
						}
					})->all();
		}
	}

	/**
	 * Prepare
	 * 	 - all objects that needed to be passed to the placeholder
	 *
	 * @return void
	 */
	public function prepare()
	{
		if(empty($this->prepared))
		{

			$jsVars = [];
			$mobileDetector = zbase()->mobile()->detector();
			$isMobile = $mobileDetector->isMobile();
			if(!$mobileDetector->isMobileTheme())
			{
				$jsVars[] = 'isMobile=' . ($isMobile ? 'true;' : 'false;');
				$jsVars[] = 'isMobileTablet=' . ($mobileDetector->isTablet() ? 'true' : 'false');
				$jsVars[] = 'isMobileIOS=' . ($mobileDetector->isIos() ? 'true' : 'false');
				$jsVars[] = 'isMobileAndroid=' . ($mobileDetector->isAndroidOs() ? 'true' : 'false');
				$jsVars[] = 'screenWidth=window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName(\'body\')[0].clientWidth';
				$jsVars[] = 'screenHeight=window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName(\'body\')[0].clientHeight';
				$jQueryResize = 'window.onresize = function(){'
						. 'if (typeof (window.innerWidth) == \'number\') {
								screenWidth = window.innerWidth;
								screenHeight = window.innerHeight;
							} else {
								if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
								screenWidth = document.documentElement.clientWidth;
								screenHeight = document.documentElement.clientHeight;
								} else {
									if (document.body && (document.body.clientWidth || document.body.clientHeight))
									{
										screenWidth = document.body.clientWidth;
										screenHeight = document.body.clientHeight;
									}
								}
							}
					};';
//				$jsVarsLoad = [
//					'id' => 'mobileDetect',
//					'type' => \Zbase\Models\View::SCRIPT,
//					'enable' => true,
//					'script' => '<script type="text/javascript">var ' . implode(',', $jsVars) . ';' . $jQueryResize . '</script>',
//					'placeholder' => 'head_scripts',
//				];
//				$this->add(self::SCRIPT, $jsVarsLoad);
			}
			$controller = zbase_request_controller();
			if(!empty($controller))
			{
				$controllerName = $controller->getName();
				$this->placeholders['body_class']['controller'] = 'controller-' . $controllerName;
				$this->placeholders['body_class']['controller-action'] = 'controller-' . $controllerName . '-' . zbase()->controller()->getActionName();
				if(zbase_alerts_has('error'))
				{
					$this->placeholders['body_class']['error'] = 'page-error';
				}
			}
			$this->_preparePlaceholders();
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Placeholders">
	/**
	 * Add item to placeholder
	 *
	 * @param string $placeholder Placeholder name
	 * @param string $id Item ID
	 * @param \Zbase\Interfaces\HtmlInterface|string $html
	 */
	public function addToPlaceholder($placeholder, $id, $html)
	{
		$this->placeholders[$placeholder][$id] = $html;
	}

	/**
	 * Remove an item from the $placeholder by $id
	 *
	 * @param string $placeholder Placeholder name
	 * @param string $id Item ID
	 */
	public function removeFromPlaceholder($placeholder, $id)
	{
		if($this->inPlaceholder($placeholder, $id))
		{
			unset($this->placeholders[$placeholder][$id]);
		}
	}

	/**
	 * Retrieve an item from the placeholder
	 * 	if $id is empty, will return all item from the $placeholder
	 *
	 * @param string $placeholder Placeholder name
	 * @param string $id Item ID
	 * @return \Zbase\Interfaces\HtmlInterface|null|\Zbase\Interfaces\HtmlInterface[]
	 */
	public function getFromPlaceholder($placeholder, $id)
	{
		if(!empty($id) && $this->inPlaceholder($placeholder, $id))
		{
			return $this->placeholders[$placeholder][$id];
		}
		if(!empty($this->placeholders[$placeholder]))
		{
			return $this->placeholders[$placeholder];
		}
		return null;
	}

	/**
	 * Check if an $id is in the  $placeholder
	 *
	 * @param string $placeholder Placeholder name
	 * @param string $id Item ID
	 * @return boolean
	 */
	public function inPlaceholder($placeholder, $id)
	{
		$this->_preparePlaceholders();
		return !empty($this->placeholders[$placeholder][$id]);
	}

	/**
	 * Render the $placeholder
	 *
	 * @param string $placeholder
	 * @return string
	 */
	public function renderPlaceholder($placeholder)
	{
		$str = '';
		if(!empty($this->placeholders[$placeholder]))
		{
//			$collection = $this->placeholders[$placeholder];
//			$i = 0;
//			foreach ($collection as $coll)
//			{
//				$position = $coll->getPosition();
//				if(empty($position))
//				{
//					$coll->setPosition(count($collection) - $i);
//				}
//				$i++;
//			}
//
//
//			$items = zbase_collection($collection)->sortByDesc(function ($itm) {
//						return $itm->getPosition();
//				})->all();
			foreach ($this->placeholders[$placeholder] as $obj)
			{
				$str .= $obj;
			}
		}
		return $str;
	}

	/**
	 * Prepare the placeholders
	 */
	protected function _preparePlaceholders()
	{
		$javascripts = $this->all(self::JAVASCRIPT);
		if(!empty($javascripts))
		{
			foreach ($javascripts as $javascript)
			{
				if($javascript instanceof Interfaces\PlaceholderInterface)
				{
					$this->addToPlaceholder($javascript->getPlaceholder(), $javascript->id(), $javascript);
				}
			}
		}
		$scripts = $this->all(self::SCRIPT);
		if(!empty($scripts))
		{
			foreach ($scripts as $script)
			{
				if($script instanceof Interfaces\PlaceholderInterface)
				{
					if($script->isOnLoad())
					{
						$this->addToPlaceholder('body_scripts_onload', $script->id(), $script);
					}
					else
					{
						$this->addToPlaceholder($script->getPlaceholder(), $script->id(), $script);
					}
				}
			}
		}
	}

	// </editor-fold>

	/**
	 * Check if $type property exists
	 *
	 * @param string $type
	 * @return boolean
	 * @throws Exceptions\PropertyNotFound
	 */
	protected function _hasKey($type)
	{
		$typeName = zbase_string_camel_case($type);
		if(!property_exists($this, $typeName))
		{
			throw new Exceptions\PropertyNotFoundException('Property "' . $type . '" not found in ' . __CLASS__);
		}
		return true;
	}

	// <editor-fold defaultstate="collapsed" desc="Plugins">
	/**
	 * Autoload plugins
	 */
	protected function _setPlugins()
	{
		$plugins = zbase_config_get('view.autoload.plugins', []);
		if(!empty($plugins))
		{
			foreach ($plugins as $id)
			{
				zbase_view_plugin_load($id);
			}
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Defaults">
	/**
	 * Set template defaults
	 *
	 */
	protected function _setDefaults()
	{
		$this->setPageTitle(zbase_config_get('view.default.title.title', null));
		zbase_view_meta_description(zbase_config_get('view.default.description', 'Zbase - aims to provide an effortless module to the world, regardless of the framework!'));
		zbase_view_meta_keywords(zbase_config_get('view.default.keywords', 'laravel, zend framework, php, framework, module'));
		$this->placeholders['body_class'][zbase_tag()] = zbase_tag();

		$navMain = zbase_config_get('nav.' . zbase_section());
		if(!empty($navMain))
		{
			foreach ($navMain as $group => $navs)
			{
				if(!empty($navs))
				{
					$counter = 0;
					foreach ($navs as $id => $nav)
					{
						if(empty($nav['id']))
						{
							$nav['id'] = $id;
						}
						if(!isset($nav['position']))
						{
							$nav['position'] = $counter++;
						}
						$this->add(self::NAVIGATION, $nav, $group);
					}
				}
			}
		}
	}

	// </editor-fold>

	/**
	 * Set Bredcrumb
	 *  Label => $link Assoc Array
	 * [
	 * 	[label => link],
	 * 	[label => link]
	 * ]
	 *
	 * 	Based navigation setup
	 * 	[
	 * 		main.home
	 * 		main.child.homeb
	 * 		main.child.child.homec
	 * 	]
	 * 	output: home / homeb / homec
	 *
	 * @param array $array
	 */
	public function setBreadcrumb($array)
	{
		$this->breadcrumb = $array;
		return $this;
	}

	/**
	 * Return Breadcrumb
	 * @return array
	 */
	public function getBreadcrumb()
	{
		return $this->breadcrumb;
	}

}
