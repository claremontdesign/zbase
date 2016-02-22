<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Command MigrateCommandInterface
 *
 * MigrateCommandInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file MigrateCommandInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface MigrateCommandInterface
{

	/**
	 * The migration command
	 * @param string $phpCommand The PHP Command
	 */
	public function migrateCommand($phpCommand);
}
