<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Id
 *
 * IdInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file IdInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface IdInterface
{
	/**
	 * Retrieve the attribute::id
	 * @return string
	 */
	public function id();

	/**
	 * Retrieve the object attribute::name
	 * @return string
	 */
	public function name();

	/**
	 * Return the object attribute::title
	 * @return string
	 */
	public function title();

	/**
	 * Return the object attribute::description
	 *
	 * @return string
	 */
	public function description();
}
