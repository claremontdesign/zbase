<?php

namespace Zbase\Models;

/**
 * Zbase-Model-Request
 *
 * Request Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Request.php
 * @project Zbase
 * @package Zbase/Model
 */
use Zbase\Models;

class Request
{

	/**
	 * The current module
	 * @var Models\Module
	 */
	protected $module = null;

	public function __construct()
	{

	}

	/**
	 * Start
	 */
	public function start()
	{

	}

	/**
	 * Return the Form INputs
	 * @return array
	 */
	public function formInputs()
	{
		return zbase_form_inputs();
	}

	/**
	 * Return all inputs
	 * @return array
	 */
	public function inputs()
	{
		return zbase_request_inputs();
	}

}
