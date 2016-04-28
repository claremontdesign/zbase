<?php

namespace Zbase\Ui\Component;

/**
 * Zbase-Ui-Component-Button
 *
 * Button
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Button.php
 * @project Zbase
 * @package Zbase/Ui/Components
 * http://getbootstrap.com/css/#buttons
 */
use Zbase\Traits;
use Zbase\Interfaces;
use Zbase\Ui as UIs;

class Button extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'button';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.components.button';
	protected $color = 'default';
	protected $size = 'default';

	/**
	 * The Tag to use a|button
	 * @var string
	 */
	protected $tag = 'button';

	/**
	 * Is this a submit button
	 * @var boolean
	 */
	protected $submit = false;

	/**
	 * is Disabled
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * is Active?
	 * @var boolean
	 */
	protected $active = false;

	/**
	 * Return the Color
	 */
	public function getColor()
	{
		$theme = zbase_config_get('theme.ui.component.button.color.' . $this->tag . '.' . $this->color, null);
		if(!empty($theme))
		{
			return $theme;
		}
		if($this->tag == 'a')
		{
			return $this->color;
		}
		if($this->color == 'green')
		{
			return 'btn-success';
		}
		if($this->color == 'blue')
		{
			return 'btn-info';
		}
		if($this->color == 'red')
		{
			return 'btn-danger';
		}
		if($this->color == 'yellow')
		{
			return 'btn-warning';
		}
		return 'btn-' . $this->color;
	}

	/**
	 * Return the Buton Size
	 * @return string
	 */
	public function getSize()
	{
		if(strtolower($this->size) == 'large')
		{
			return 'btn-lg';
		}
		if(strtolower($this->size) == 'small')
		{
			return 'btn-sm';
		}
		if(strtolower($this->size) == 'extrasmall')
		{
			return 'btn-xs';
		}
		if(strtolower($this->size) == 'default')
		{
			return null;
		}
		return 'btn-' . $this->size;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = $this->_v('html.attributes.wrapper', []);
		$attr['class'][] = 'zbase-ui-button';
		if($this->tag == 'a')
		{
			$attr['class'][] = 'btn';
		}
		$attr['class'][] = $this->getColor();
		$size = $this->getSize();
		if(!is_null($size))
		{
			$attr['class'][] = $size;
		}
		$attr['role'][] = 'button';
		if($this->tag == 'button')
		{
			$attr['type'][] = 'button';
		}
		if(!empty($this->disabled))
		{
			$attr['class'][] = 'disabled';
		}
		if(!empty($this->active))
		{
			$attr['class'][] = 'active';
		}

		$title = !empty($this->title) ? $this->title : $this->getLabel();
		$attr['title'] = $title;
		$route = $this->route;
		if(!empty($route))
		{
			if(!empty($attr['onclick']))
			{
				$attr['onclick'] = "zbase_gotoLocation('".zbase_url_from_route($route, $this->routeParams)."')";
				if($this->tag == 'a')
				{
					$attr['href'] = '#';
				}
			}
			else
			{
				if($this->tag == 'a')
				{
					$attr['href'] = zbase_url_from_route($route, $this->routeParams);
				}
			}
		}
		return $attr;
	}

	/**
	 * Return the HREF attribute
	 * @return string
	 */
	public function href()
	{
		$route = $this->route;
		if(!empty($route))
		{
			return zbase_url_from_route($route, $this->routeParams);
		}
		return null;
	}

}
