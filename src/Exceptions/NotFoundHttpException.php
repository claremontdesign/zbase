<?php

namespace Zbase\Exceptions;

/**
 * Zbase-Exceptions-PropertyNotFoundException
 *
 * PropertyNotFoundException
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file NotFoundException.php
 * @project Zbase
 * @package Zbase/Exceptions
 */
use Zbase\Exceptions\Exception;

class NotFoundHttpException extends Exception
{
	protected $statusCode = 404;

	public function render($request, Exception $e)
	{
		return response()->view(zbase_view_file('errors.404'), compact('request', 'e'));
	}
}
