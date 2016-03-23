<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Command AssetsCommand
 *
 * AssetsCommand
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AssetsCommand.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface AssetsCommandInterface
{

	/**
	 * Assets commands
	 * @param string $phpCommand The PHP Command
	 * @param array $options Some Options
	 */
	public function assetsCommand($phpCommand, $options = []);
}
