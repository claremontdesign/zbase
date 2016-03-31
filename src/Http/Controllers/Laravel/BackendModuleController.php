<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * BackendModule Controller
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file BackendModuleController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Zbase\Interfaces;
use Zbase\Traits;

class BackendModuleController extends Controller implements Interfaces\AttributeInterface
{

	use Traits\Attribute,
	 Traits\Module;

	public function index()
	{
		return $this->controllerIndex();
	}

}
