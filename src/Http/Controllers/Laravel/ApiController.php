<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * ApiController
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PageModuleController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Zbase\Interfaces;
use Zbase\Traits;

class ApiController extends Controller implements Interfaces\AttributeInterface
{

	use Traits\Attribute, Traits\Api;

	/**
	 * Telegram Hook
	 * http://dermasecrets-local.biz/telegram/237861793:AAFW6v2ZRg5oPHiQWuedtXuqs2GAguCSZU8
	 */
	public function telegramHook()
	{
		zbase()->telegram()->receiveMessage();
	}

	public function index()
	{
		return $this->apiIndex();
	}
}
