<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-HTML Interface
 *
 * AttributeInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AttributeInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface AttributeInterface
{

	/**
	 * Set an attribute value
	 *
	 * @param string $name Attribute name
	 * @param mixed $value Attribute value
	 * @return object
	 */
	public function setAttribute($name, $value);

	/**
	 * Return an Attribute value
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute($name);
}
