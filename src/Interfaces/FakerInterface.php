<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-HTML Interface
 *
 * FakerInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file FakerInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface FakerInterface
{

	/**
	 * Return a fake data
	 */
	public function faker();

	/**
	 * return data type
	 * @return string
	 */
	public function getType();

	/**
	 * Return data subType
	 * @return string
	 */
	public function getSubType();

	/**
	 * Retur the data type length
	 * @return integer
	 */
	public function getLength();

	/**
	 * Return the Assoc Map of values
	 * @return aray
	 */
	public function getValueMap();
}
