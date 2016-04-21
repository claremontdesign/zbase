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
use Zbase\Interfaces;
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
		$str = '';
		$str .= '<li class="' . (!empty($this->active) ? 'active' : '') . '">';
		$str .= '<a href="' . $this->getRouteUrl() . '" title="' . $this->title . '" ' . $this->renderHtmlAttributes() . '>';
		$str .= '<i class="' . $this->icon . '"></i>';
		$str .= '<span class="title">' . $this->label . '</span>';
		$str .= '</a>';
		$str .= '</li>';
		return $str;
	}

}
