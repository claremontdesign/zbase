<?php

namespace Zbase\Traits;

/**
 * Zbase-Id
 *
 * ReUsable Traits - Id
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Id.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait EntityLog
{

	/**
	 * Log
	 * @param string $msg
	 * @param string $type
	 * @param array $options
	 *
	 * @return EntityInterface
	 */
	public function log($msg, $type, $options = [])
	{
		return $this;
	}

}
