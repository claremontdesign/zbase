<?php

namespace Zbase\Traits;

/**
 * Zbase-Attribute
 *
 * Reusable Methods Attribute
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Attribute.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Attribute
{

	/**
	 * The Attributes
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Set an attribute value
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 * @return object
	 */
	public function setAttribute($name, $value)
	{
		return $this->__set($name, $value);
	}

	/**
	 * Set multiple attributes
	 *
	 * @param array $attributes Key-Value attributes
	 * @return void
	 */
	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
		if(is_array($attributes) && !empty($attributes))
		{
			foreach ($attributes as $name => $value)
			{
				$this->__set($name, $value);
			}
		}
	}

	/**
	 * Return the Attributes
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Return an Attribute value
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute($name)
	{
		return $this->__get($name);
	}

	/**
	 * __get
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function __get($name)
	{
		$method = zbase_string_camel_case('get_' . $name);
		if(method_exists($this, $method))
		{
			return $this->$method();
		}
		if(property_exists($this, $name))
		{
			return $this->$name;
		}
		if(!empty($this->attributes[$name]))
		{
			return $this->attributes[$name];
		}
		return null;
	}

	/**
	 * __set
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 * @return object
	 */
	public function __set($name, $value)
	{
		$method = zbase_string_camel_case('set_' . $name);
		if(method_exists($this, $method))
		{
			$this->$method($value);
			return $this;
		}
		if(property_exists($this, $name))
		{
			$this->$name = $value;
			return $this;
		}
		$this->attributes[$name] = $value;
	}

	/**
	 * __call
	 *
	 * @param string $name
	 * @param mixed $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments = null)
	{
		if(zbase_string_starts_with($name, 'get'))
		{
			$names = str_replace('get_', '', zbase_string_snake_case($name));
			return $this->__get(zbase_string_camel_case($names));
		}
		if(zbase_string_starts_with($name, 'set'))
		{
			$names = str_replace('set_', '', zbase_string_snake_case($name));
			return $this->__set(zbase_string_camel_case($names), $arguments);
		}
	}

}
