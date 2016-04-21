<?php

namespace Zbase\Exceptions;

/**
 * Zbase-Exceptions-Exception
 *
 * Exception
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Exception.php
 * @project Zbase
 * @package Zbase/Exceptions/Exception
 */
class Exception extends \RuntimeException
{

	protected $statusCode = 500;

	public function getStatusCode()
	{
		return $this->statusCode;
	}
}