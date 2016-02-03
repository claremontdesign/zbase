<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Auth
 *
 * AuthInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AuthInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface AuthInterface
{

	/**
	 * Check if user has access
	 *
	 * @return boolean
	 */
	public function hasAccess();
}
