<?php

namespace Packagename;

/**
 * Packagename Main
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Packagename.php
 * @project Packagename
 * @package Packagename
 */
use Zbase\Interfaces;

class Packagename implements Interfaces\ZbaseInterface
{

	/**
	 * Return all configuration files included for this packages
	 * @return array
	 */
	public function config()
	{
		return [__DIR__ . '/../config/config.php'];
	}

	/**
	 * Path to this package src
	 * @return string
	 */
	public function path()
	{
		return __DIR__ . '/../';
	}

}
