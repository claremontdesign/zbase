<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Entity Interface
 *
 * EntityInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file EntityInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface EntityLogInterface
{

	/**
	 * Log
	 * @param string $msg
	 * @param string $type
	 * @param array $options
	 *
	 * @return EntityInterface
	 */
	public function log($msg, $type, $options = []);
}
