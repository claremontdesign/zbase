<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Id
 *
 * PlaceholderInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PlaceholderInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface PlaceholderInterface
{

	/**
	 * Set the placeholder
	 *
	 * @return string
	 */
	public function setPlaceholder($placeholder);

	/**
	 * Return placeholder
	 *
	 * @return string
	 */
	public function getPlaceholder();
}
