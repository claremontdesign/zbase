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

class Navigation implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\PositionInterface, Interfaces\AttributeInterface, Interfaces\StatusInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Status,
	 Traits\Position,
	 Traits\Url,
	 Traits\Id;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'nav-';

	/**
	 * Include in breadcrumb
	 * @var false
	 */
	protected $breadcrumb = false;

	/**
	 * Label
	 * @var string
	 */
	protected $label = null;

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
	 * A separator/divider after
	 * @var boolean
	 */
	protected $separator = false;

	/**
	 * Child Navs
	 * @var Zbase\Models\View\Nav[];
	 */
	protected $children = [];

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		$this->setAttributes($attributes);
		if(!empty($this->children))
		{
			$counter = 0;
			foreach ($this->children as $id => $child)
			{
				$class = __CLASS__;
				if(empty($child['id']))
				{
					$child['id'] = $this->id() . '-' . $id;
				}
				if(!isset($child['position']))
				{
					$child['position'] = $counter++;
				}
				$object = new $class($child);
				unset($this->children[$id]);
				if($object instanceof Interfaces\StatusInterface && !$object->enabled())
				{
					continue;
				}
				if($object instanceof Interfaces\AuthInterface && !$object->hasAccess())
				{
					continue;
				}
				if($object instanceof Interfaces\IdInterface)
				{
					$this->children[$object->id()] = $object;
				}
			}
		}
		if(zbase_url() == $this->getUrl())
		{
			$this->active = true;
		}
	}

	/**
	 * Check if there are children
	 *
	 * @return boolean
	 */
	public function hasChildren()
	{
		return !empty($this->children);
	}

	/**
	 * @see $separator
	 *
	 * @return boolean
	 */
	public function hasSeparator()
	{
		return $this->separator;
	}

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
		$id = $this->id();
		$script = $this->getScript();
		if(!empty($id) && !empty($script))
		{
			return EOF . '<a href="' . $this->getHref() . '" title="' . $this->getTitle() . '" ' . $this->renderHtmlAttributes() . '>' . $this->getLabel() . '</a>' . EOF;
		}
		return '';
	}

	/**
	 * If to include in main Menu
	 *
	 * @return boolean
	 */
	public function inMenu()
	{
		$inMenu = $this->getAttribute('inMenu', null);
		if(!is_null($inMenu))
		{
			return $inMenu;
		}
		return true;
	}

}
