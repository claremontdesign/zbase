<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Command InstallCommandInterface
 *
 * InstallCommandInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file InstallCommandInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface InstallCommandInterface
{

	/**
	 * The install command
	 * @param string $phpCommand The PHP Command
	 */
	public function installCommand($phpCommand);
}
