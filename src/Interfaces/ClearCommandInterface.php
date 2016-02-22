<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Command ClearCommandInterface
 *
 * ClearCommandInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ClearCommandInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface ClearCommandInterface
{

	/**
	 *  Clear commands
	 * @param string $phpCommand The PHP Command
	 */
	public function clearCommand($phpCommand);
}
