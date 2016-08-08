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
interface EntityDataValue
{

	/**
	 * Get entity value
	 *
	 * @return mixed
	 */
	public static function entityDataValue(\Zbase\Interfaces\EntityInterface $row, \Zbase\Models\Data\Data $data);
}
