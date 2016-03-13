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
 *
 * $configuration.type
 * $configuration.subtype
 * $configuration.label
 * $configuration.title
 * $configuration.position
 * $configuration.description
 * $configuration.help
 * $configuration.id
 * $configuration.html
 * $configuration.html.attributes
 * $configuration.html.attributes.wrapper
 */
use Zbase\Interfaces;
use Zbase\Exceptions;

abstract class Ui
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
	 * Contents
	 * @var string|Ui\UiInterface[]
	 */
	protected $_contents = null;

	/**
	 * Constructor
	 * @param string $id
	 * @param array $configuration
	 */
	public function __construct($configuration = null)
	{
		if(!empty($configuration) && is_array($configuration))
		{
			$this->setConfiguration($configuration);
		}
	}

	/**
	 * Set the Configuration
	 * @param array $configuration
	 */
	public function setConfiguration(array $configuration)
	{
		$this->setAttributes($configuration);
		if($this instanceof Interfaces\IdInterface)
		{
			if(empty($configuration['id']))
			{
				$this->setId($configuration['id']);
			}
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Enable|HasAccess">
	/**
	 * Check if ui is enabled or disable
	 * 	based on the configuration index:enable
	 * 	default: true
	 * @return boolean
	 */
	public function enabled()
	{
		if(is_null($this->_enable))
		{
			if($this->hasAccess())
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
	 * Check if current user has access to this ui
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

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Prepare">
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
	 * Prepare UI
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

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Contents">
	/**
	 * Set the UI Content
	 * @param array|Ui\ContentInterface[]|Ui\UiInterface[] $contents
	 * @return \Zbase\Ui\Ui
	 */
	public function setContents($contents)
	{
		if(is_array($contents))
		{
			foreach ($contents as $content)
			{
				$this->addContent($content);
			}
		}
		return $this;
	}

	/**
	 * Return the Content
	 * @return string
	 */
	public function getContents()
	{
		return $this->_contents;
	}

	/**
	 * Add a Content
	 * @param string|Ui\Contentarra $content
	 * @param string $id optional The Content Id
	 * @return \Zbase\Ui\Ui
	 */
	public function addContent($content, $id = null)
	{
		if(is_string($content))
		{
			$configuration = ['content' => $content, 'id' => $id];
		}
		if(is_array($content))
		{
			$configuration = $content;
		}
		if(!$content instanceof Interfaces\ContentInterface && !empty($configuration) && is_array($configuration))
		{
			if(empty($configuration['id']))
			{
				throw new Exceptions\ConfigNotFoundException('Content $id shoud be set to create a content.');
			}
			if(!empty($this->_contents[$configuration['id']]))
			{
				throw new Exceptions\DuplicateIdException('Content with the same id: ' . $configuration['id'] . ' already exists.');
			}
			if(empty($configuration['position']))
			{
				$configuration['position'] = count($this->_contents);
			}
			$className = zbase_model_name('', 'ui.content', '\Zbase\Ui\Content');
			$c = new $className($configuration);
		}
		else
		{
			$c = $content;
		}
		if($c instanceof ContentInterface || $c instanceof UiInterface)
		{
			if($c->enabled())
			{
				$this->_contents[$c->id()] = $c;
			}
		}
		return $this;
	}

	/**
	 * @param string|Ui\UiInterface $content
	 * @return \Zbase\Ui\Ui
	 */
	public function removeContent($content)
	{
		if(!empty($this->_contents))
		{
			if($content instanceof Interfaces\IdInterface)
			{
				if(!empty($this->_contents[$content->id()]))
				{
					unset($this->_contents[$content->id()]);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Render The Contents
	 * @return string
	 */
	public function renderContents()
	{
		if(!empty($this->_contents))
		{
			$str = [];
			foreach ($this->_contents as $content)
			{
				$str[] = $content->__toString();
			}
			return implode(' ', $str);
		}
		return '';
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Rendering">

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$someAttributes = property_exists($this, '_htmlWrapperAttributes') ? $this->_htmlWrapperAttributes : [];
		$generalAttributes = zbase_config_get('ui.' . $this->_type . '.html.attributes.wrapper', []);
		$attr = array_merge_recursive($this->_v('html.attributes.wrapper', []), $someAttributes, $generalAttributes);
		$attr['class'][] = 'zbase-ui-wrapper';
		$attr['id'] = 'zbase-ui-wrapper-' . $this->id();
		return $attr;
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
	 * HTML the ui
	 * @return string
	 */
	public function __toString()
	{
		$this->prepare();

		if(!empty($this->_viewString))
		{
			return strtr($this->_viewString, array(
				'{wrapperAttributes}' => $this->wrapperAttributes(),
				'{content}' => $this->renderContents()
			));
		}

		if(!is_null($this->_viewFile))
		{
			return zbase_view_render(zbase_view_file_contents($this->_viewFile), ['ui' => $this])->__toString();
		}
		return '';
	}

	// </editor-fold>

	/**
	 * UI Factory
	 * @param array $configuration
	 * @return \Zbase\Ui\UiInterface
	 */
	public static function factory($configuration)
	{
		$type = !empty($configuration['type']) ? $configuration['type'] : 'ui';
		$prefix = '';
		if(!empty(preg_match('/component./', $type)))
		{
			$prefix = '\\Component';
			$type = zbase_string_camel_case(str_replace('component.', '', $type));
		}
		$id = !empty($configuration['id']) ? $configuration['id'] : null;
		if(is_null($id))
		{
			throw new Exceptions\ConfigNotFoundException('Index:id is not set on Ui Factory');
		}
		if(!empty($type))
		{
			$className = zbase_model_name(null, 'class.ui.' . strtolower($type), '\Zbase\Ui' . $prefix . '\\' . ucfirst($type));
			$element = new $className($configuration);
			return $element;
		}
		return null;
	}

}
