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

	protected $statusMessage = 'Page not found.';
}
