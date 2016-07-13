<?php

namespace Zbase\Traits;

/**
 * Zbase-Html
 *
 * Reusable HTML Methods
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Html.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Html
{

	/**
	 * The HTML Attributes
	 * @var array
	 */
	protected $htmlAttributes = null;

	/**
	 * The HTML Conditions
	 * @var string
	 */
	protected $htmlConditions = null;

	/**
	 * Return the HTML Id
	 * @return string
	 */
	public function getHtmlId()
	{
		$id = method_exists($this, 'id') ? $this->id() : null;
		$prefix = property_exists($this, 'htmlPrefix') ? $this->htmlPrefix : null;
		return $prefix . $id;
	}

	/**
	 * Return the HTML Attributes in string format
	 *
	 * @param array $htmlAttributes
	 * @return array
	 */
	public function renderHtmlAttributes($htmlAttributes = null)
	{
		if(is_null($htmlAttributes))
		{
			$htmlAttributes = $this->getHtmlAttributes();
		}
		if(zbase_is_angular_template())
		{
			if(!empty($htmlAttributes['angular']))
			{
				$htmlAttributes = $htmlAttributes['angular'];
			}
		}
		if(!empty($htmlAttributes) && is_array($htmlAttributes))
		{
			$attributes = [];
			foreach ($htmlAttributes as $key => $value)
			{
				if(is_array($value))
				{
					$attributes[] = $key . '="' . implode(' ', $value) . '"';
				}
				else
				{
					$attributes[] = $key . '="' . $value . '"';
				}
			}
			return implode(' ', $attributes);
		}
		return null;
	}

	/**
	 * Retrieve HTML Attributes from the set of attributes
	 * HTML Attributes: attributes::html.attributes
	 *
	 * @return array
	 */
	public function getHtmlAttributes()
	{
		if(is_null($this->htmlAttributes))
		{
			$attributes = [];
			if(method_exists($this, 'getAttributes'))
			{
				$attributes = $this->getAttributes();
			}
			if(!empty($attributes))
			{
				$this->htmlAttributes = zbase_data_get($attributes, 'html.attributes', []);
			}
			else
			{
				$this->htmlAttributes = [];
			}
		}
		return $this->htmlAttributes;
	}

	/**
	 * Set the HTML Attributes
	 * @param array $htmlAttributes
	 */
	public function setHtmlAttributes($htmlAttributes)
	{
		$this->htmlAttributes = $htmlAttributes;
	}

	/**
	 * Add a new HTML Attribute
	 * @param string $key
	 * @param string $val
	 */
	public function addHtmlAttribute($key, $val)
	{
		$this->getHtmlAttributes();
		$this->htmlAttributes[$key] = $val;
	}

	/**
	 * Return the HTML Conditions
	 * @return string
	 */
	public function getHtmlConditions()
	{
		if(is_null($this->htmlConditions))
		{
			$attributes = [];
			if(method_exists($this, 'getAttributes'))
			{
				$attributes = $this->getAttributes();
			}
			if(!empty($attributes))
			{
				$this->htmlConditions = zbase_data_get($attributes, 'html.conditions', []);
			}
			else
			{
				$this->htmlConditions = '';
			}
		}
		return $this->htmlConditions;
	}

	/**
	 * Wrap content in an HTML Conditions
	 * @param string $content
	 * @return string
	 */
	public function wrapWithHtmlConditions($content)
	{
		$htmlConditions = $this->getHtmlConditions();
		if(!empty($htmlConditions))
		{
			if(is_array($htmlConditions))
			{
				return '<!-- ' . __METHOD__ . ' - Conditions Not Implemented. Change condition to string-only value -->' . $content;
			}
			else
			{
				return '<!--[' . $htmlConditions . ']>' . $content . '<![endif]-->';
			}
		}
		return $content;
	}

	/**
	 * Set the HTML Conditions
	 * @param string $htmlConditions
	 */
	public function setHtmlConditions($htmlConditions)
	{
		$this->htmlConditions = $htmlConditions;
	}

}
