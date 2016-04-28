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
	 * Check if navigation is Active
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active;
	}

	public function getRouteUrl()
	{
		if(!empty($this->route))
		{
			return zbase_url_from_config(['route' => $this->route]);
		}
	}

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
		$childStr = $this->_childrenMenu();
		$str = '';
		$str .= '<li class="' . (!empty($this->active) ? 'active' : '') . '">' . EOF;
		$str .= '<a href="' . $this->getRouteUrl() . '" title="' . $this->title . '" ' . $this->renderHtmlAttributes() . '>' . EOF;
		$str .= '<i class="' . $this->icon . '"></i>' . EOF;
		$str .= '<span class="title">' . $this->label . '</span>' . EOF;
		if(!empty($this->children))
		{
			$str .= '<span class="arrow"></span>';
		}
		$str .= '</a>' . EOF;
		$str .= $childStr;
		$str .= '</li>' . EOF;
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

}
