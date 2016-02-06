<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-HTML Interface
 *
 * ZbaseInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AttributeInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface ZbaseInterface
{

	/**
	 * REturn configuration files to be included and merge_replace to
	 * the zbase configuration
	 * @return array
	 */
	public function config();

	/**
	 * Path to this package src
	 * @return string
	 */
	public function path();
}
