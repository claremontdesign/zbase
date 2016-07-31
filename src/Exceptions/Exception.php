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
	protected $statusMessage = 'Server Error.';

	public function __construct($message = "", $code = 0, Exception $previous = null)
	{
		$this->setStatusMessage($message);
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function setStatusCode($statusCode)
	{
		return $this->statusCode = $statusCode;
	}

	public function getStatusMessage()
	{
		return $this->statusMessage;
	}

	public function setStatusMessage($statusMessage)
	{
		$this->statusMessage = $statusMessage;
		return $this;
	}

	public function render($request, Exception $e)
	{
		return response()->view(zbase_view_file('errors.' . $this->getStatusCode()), compact('request', 'e'));
	}

}
