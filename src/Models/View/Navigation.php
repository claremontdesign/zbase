<?php

namespace Zbase\Models\View;

/**
 * Nav
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Nav.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Traits;

class Navigation
{

	use Traits\Attribute;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'nav-';

	/**
	 * If Enabled
	 * @var boolean
	 */
	protected $enable = null;

	/**
	 * If has access
	 * @var boolean
	 */
	protected $access = null;

	/**
	 * Order of Arrangement
	 * @var integer
	 */
	protected $order = 0;

	/**
	 * Label
	 * @var string
	 */
	protected $label = null;

	/**
	 * Title
	 * @var string
	 */
	protected $title = null;

	/**
	 * Icon
	 * @var string
	 */
	protected $icon = null;

	/**
	 * isActive?
	 * @var boolean
	 */
	protected $active = false;

	/**
	 * Children
	 * @var array
	 */
	protected $children = [];

	/**
	 * The Route
	 * @var array
	 */
	protected $route = [];

	/**
	 *
	 * @var boolean
	 */
	protected $isAngular = false;

	/**
	 * Nav Format
	 * @var string|HTML
	 */
	protected $format = '<li class="{CLASS_ISACTIVE}">'
			. '{A_PRE}<a href="{URL}" title="{TITLE}" {A_ATTRIBUTES}>{LABEL}</a>{A_POST}'
			. '</li>';

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		$this->setAttributes($attributes);
		if(!empty($this->route['name']))
		{
			if(zbase_route_name_is($this->route['name']))
			{
				$this->active = true;
			}
		}
	}

	/**
	 * Return the Order of Arrangement
	 * @return integer
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * Check if ui is enabled or disable
	 * 	based on the configuration index:enable
	 * 	default: true
	 * @return boolean
	 */
	public function isEnabled()
	{
		if(is_null($this->enable))
		{
			$this->enable = $this->_v('enable', true);
		}
		return $this->enable;
	}

	/**
	 * Process access
	 * 	Redirect if needed to
	 *  Else will display a message to the user when rendering the UI
	 */
	public function hasAccess()
	{
		if(is_null($this->access))
		{
			$this->access = zbase_auth_check_access($this->_v('access', zbase_auth_minimum()));
		}
		return $this->access;
	}

	/**
	 * Check if navigation is Active
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * SET/GET If angular
	 *
	 * @param boolean $flag
	 * @return \Zbase\Models\View\Navigation|boolean
	 */
	public function isAngular($flag = null)
	{
		if(is_bool($flag))
		{
			$this->isAngular = $flag;
			return $this;
		}
		return $this->isAngular;
	}

	/**
	 * Generate URL
	 * @return type
	 */
	public function getRouteUrl()
	{
		if(!empty($this->route))
		{
			if($this->isAngular())
			{
				return zbase_angular_url($this->route);
			}
			return zbase_url_from_config(['route' => $this->route]);
		}
		return '#';
	}

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
//		$childStr = $this->_childrenMenu();
//		$str = '';
//		$str .= '<li class="' . (!empty($this->active) ? 'active' : '') . '">' . EOF;
//		$str .= '<a href="' . $url . '" title="' . $this->title . '" ' . $this->renderHtmlAttributes() . '>' . EOF;
//		$str .= '<i class="' . $this->icon . '"></i>' . EOF;
//		$str .= '<span class="title">' . $this->label . '</span>' . EOF;
//		if(!empty($this->children))
//		{
//			$str .= '<span class="arrow"></span>';
//		}
//		$str .= '</a>' . EOF;
//		$str .= $childStr;
//		$str .= '</li>' . EOF;

		$classIsActive = (!empty($this->active) ? 'active' : '');
		$aPre = '';
		$aPost = '';
		$url = $this->getRouteUrl();
		$title = $this->title;
		$aAttributes = $this->renderHtmlAttributes();
		$label = '';

		$childStr = $this->_childrenMenu();
		$label .= '<i class="' . $this->icon . '"></i>' . EOF;
		$label .= '<span class="title">' . $this->label . '</span>' . EOF;
		if(!empty($this->children))
		{
			$label .= '<span class="arrow"></span>';
		}
		$aPost .= $childStr;


		$str = str_replace(
				array('{CLASS_ISACTIVE}', '{A_PRE}', '{A_POST}', '{URL}', '{TITLE}', '{A_ATTRIBUTES}', '{LABEL}'), array($classIsActive, $aPre, $aPost, $url, $title, $aAttributes, $label), $this->format);
		return $str;
	}

	protected function _childrenMenu()
	{
		if(!empty($this->children))
		{
			$str = '<ul class="sub-menu">' . EOF;
			foreach ($this->children as $k => $nav)
			{
				$n = new Navigation($nav);
				if($n->isActive())
				{
					$this->active = true;
				}
				$str .= $n;
			}
			$str .= '</ul>' . EOF;
			return $str;
		}
	}

	/**
	 * Retrieves a value from the configuration
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function _v($key, $default = null)
	{
		return zbase_data_get($this->getAttributes(), $key, $default);
	}

}
