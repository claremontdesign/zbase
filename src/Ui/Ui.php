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
	 * Has been rendered
	 * @var boolean
	 */
	protected $_rendered = false;

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
	 * If true, will search for $_viewFile using: zbase_view_file_contents
	 * @var boolean
	 */
	protected $_viewFileContent = true;

	/**
	 * Contents
	 * @var string|Ui\UiInterface[]
	 */
	protected $_contents = null;

	/**
	 * Variable to pass to the View
	 * @var string
	 */
	protected $_viewParams = [];

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
			$this->_enable = $this->_v('enable', true);
		}
		return $this->_enable;
	}

	/**
	 * Process access
	 * 	Redirect if needed to
	 *  Else will display a message to the user when rendering the UI
	 */
	protected function _access()
	{
		if(!$this->hasAccess())
		{
			/**
			 * If User has Auth
			 */
			if(zbase_auth_has())
			{
				$redirectToRoute = $this->_v('access.noaccess.route', null);
				$message = $this->_v('access.noaccess.message', null);
			}
			else
			{
				$redirectToRoute = $this->_v('access.noauth.route', null);
				$message = $this->_v('access.noauth.message', null);
			}
			if(!empty($message))
			{
				$this->_viewParams['message'] = $message;
			}
			if(!empty($redirectToRoute))
			{
				$this->setViewFile('ui.auth');
				return;
				// return redirect()->to(zbase_url_from_route($redirectToRoute));
			}
			if(!empty($message))
			{
				$this->setViewFile('ui.message.access');
				return;
			}
			$this->setViewFile(null);
		}
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
			$this->_hasAccess = zbase_auth_check_access($this->getAccess());
		}
		return $this->_hasAccess;
	}

	public function setHasAccess($access)
	{
		$this->_hasAccess = $access;
		return $this;
	}

	/**
	 * @var string
	 * access = renter
	 *
	 * @var array
	 * access.role
	 * access.enable
	 * access.noauth when a user has no auth
	 * access.noauth.route redirect
	 * access.noauth.message display a message
	 * access.noaaccess when a user has an auth and no access
	 * access.noaaccess.route redireect
	 * access.noaaccess.message display a message
	 *
	 * @return string
	 */
	public function getAccess()
	{
		$access = $this->_v('access', null);
		if(is_array($access))
		{
			$enable = $this->_v('access.enable', false);
			if(!empty($enable))
			{
				return $this->_v('access.role', zbase_auth_minimum());
			}
			return 'guest';
		}
		else
		{
			if(is_null($access))
			{
				return zbase_auth_minimum();
			}
		}
		return $access;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Prepare">
	/**
	 * Retrieves a value from the configuration
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function _v($key, $default = null)
	{
		if(zbase_is_angular_template())
		{
			return zbase_data_get($this->getAttributes(), 'angular.' . $key, zbase_data_get($this->getAttributes(), $key, $default, $this), $this);
		}
		return zbase_data_get($this->getAttributes(), $key, $default, $this);
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
		/**
		 * SEnd the UI to a placeholder
		 */
		$placeholder = $this->_v('view.placeholder', null);
		if(!empty($placeholder))
		{
			zbase_view_placeholder_add($placeholder, $this->id(), $this->render());
		}
	}

	/**
	 * Prepare UI
	 * @return void
	 */
	public function prepare()
	{
		try
		{
			if(empty($this->_prepared))
			{
				$this->_prepared = true;
				if($this->enabled())
				{
					$this->_access();
					$this->_pre();
					$this->_post();
				}
			}
			return $this;
		} catch (\Exception $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
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

	/**
	 * The Content to be rendered before the UI Wrapper
	 * html.content.pre
	 * html.content.pre.view
	 * @return HTML
	 */
	public function htmlPreContent()
	{
		$enable = $this->_v('html.content.pre.enable', false);
		if(!empty($enable))
		{
			$viewFile = $this->_v('html.content.pre.view', false);
			if(!empty($viewFile))
			{
				return zbase_view_render(zbase_view_file_contents($viewFile), ['ui' => $this]);
			}
			$html = $this->_v('html.content.pre.html', false);
			if(!empty($html))
			{
				return $html;
			}
		}
		return '';
	}

	/**
	 * The Content to be rendered after the UI Wrapper
	 * html.content.post
	 * html.content.post.view
	 * @return HTML
	 */
	public function htmlPostContent()
	{
		$enable = $this->_v('html.content.post.enable', false);
		if(!empty($enable))
		{
			$viewFile = $this->_v('html.content.post.view', false);
			if(!empty($viewFile))
			{
				return zbase_view_render(zbase_view_file_contents($viewFile), ['ui' => $this])->__toString();
			}
			$html = $this->_v('html.content.post.html', false);
			if(!empty($html))
			{
				return $html;
			}
		}
		return '';
	}

	/**
	 * The Content to be appended inside the UI Wrapper
	 * html.content.append
	 * html.content.append.view
	 * @return HTML
	 */
	public function htmlAppendContent()
	{
		$enable = $this->_v('html.content.append.enable', false);
		if(!empty($enable))
		{
			$viewFile = $this->_v('html.content.append.view', false);
			if(!empty($viewFile))
			{
				return zbase_view_render(zbase_view_file_contents($viewFile), ['ui' => $this])->__toString();
			}
			$html = $this->_v('html.content.append.html', false);
			if(!empty($html))
			{
				return $html;
			}
		}
		return '';
	}

	/**
	 * The Content to be appended inside the UI Wrapper
	 * html.content.prepend
	 * html.content.preped.view
	 * @return HTML
	 */
	public function htmlPrependContent()
	{
		$enable = $this->_v('html.content.prepend.enable', false);
		if(!empty($enable))
		{
			$viewFile = $this->_v('html.content.prepend.view', false);
			if(!empty($viewFile))
			{
				return zbase_view_render(zbase_view_file_contents($viewFile), ['ui' => $this])->__toString();
			}
			$html = $this->_v('html.content.prepend.html', false);
			if(!empty($html))
			{
				return $html;
			}
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
	 * Return the View Parameters
	 * @return array
	 */
	public function getViewParams()
	{
		return $this->_viewParams;
	}

	/**
	 * HTML the ui
	 * @return string
	 */
	public function __toString()
	{
		$this->prepare();
		try
		{
			if(!is_null($this->_viewFile) && empty($this->_rendered))
			{
				if(!zbase_request_is_ajax())
				{
					zbase()->view()->multiAdd($this->_v('view.library', false));
				}
				$this->_viewParams['ui'] = $this;
				$str = $this->htmlPreContent();
				if(!empty($this->_viewFileContent))
				{
					$str .= zbase_view_render(zbase_view_file_contents($this->_viewFile), $this->getViewParams())->__toString();
				}
				else
				{
					$str .= zbase_view_render($this->_viewFile, $this->getViewParams())->__toString();
				}
				$str .= $this->htmlPostContent();
				$this->_rendered = true;
				return $str;
			}
			return '';
		} catch (\Exception $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}

	public function render()
	{
		return $this->__toString();
	}

	// </editor-fold>

	/**
	 * UI Factory
	 * @param array $configuration
	 * @return \Zbase\Ui\UiInterface
	 */
	public static function factory($configuration)
	{
		// $configuration = zbase_data_get($configuration);
		if(!is_array($configuration))
		{
			$configuration = zbase_data_get($configuration);
			if(empty($configuration))
			{
				return null;
			}
		}
		$type = !empty($configuration['type']) ? $configuration['type'] : 'ui';
		$prefix = '';
		if(!empty(preg_match('/component./', $type)))
		{
			$prefix = '\\Component';
			$type = zbase_string_camel_case(str_replace('component.', '', $type));
		}
		if(!empty(preg_match('/data./', $type)))
		{
			$prefix = '\\Data';
			$type = zbase_string_camel_case(str_replace('data.', '', $type));
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
